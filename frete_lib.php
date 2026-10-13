<?php
// =========================================================
// Funções de FRETE (cálculo por CEP) — usadas pelo carrinho e pelo pagamento.
// Origem simulada: São Paulo/SP. Para usar preços reais dos Correios/Melhor Envio
// basta trocar o conteúdo de frete_opcoes() por uma chamada à API da transportadora.
// =========================================================
require_once __DIR__ . '/catalogo.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Compras a partir deste valor têm frete econômico grátis
if (!defined('FRETE_GRATIS_ACIMA')) define('FRETE_GRATIS_ACIMA', 5000.00);
// Valor adicional por cada unidade extra no carrinho (mais peso/volume)
if (!defined('FRETE_ADICIONAL_ECON')) define('FRETE_ADICIONAL_ECON', 9.90);
if (!defined('FRETE_ADICIONAL_EXPR')) define('FRETE_ADICIONAL_EXPR', 14.90);

function frete_limpar_cep($cep) {
    return preg_replace('/\D/', '', (string) $cep);
}

function frete_formatar_cep($cep) {
    $c = frete_limpar_cep($cep);
    return substr($c, 0, 5) . '-' . substr($c, 5, 3);
}

// Descobre o estado pela faixa do CEP (tabela oficial dos Correios)
function frete_uf_por_cep($cep) {
    $c = frete_limpar_cep($cep);
    if (strlen($c) !== 8) return null;
    $n = (int) substr($c, 0, 5);

    $faixas = [
        [1000, 19999, 'SP'], [20000, 28999, 'RJ'], [29000, 29999, 'ES'], [30000, 39999, 'MG'],
        [40000, 48999, 'BA'], [49000, 49999, 'SE'], [50000, 56999, 'PE'], [57000, 57999, 'AL'],
        [58000, 58999, 'PB'], [59000, 59999, 'RN'], [60000, 63999, 'CE'], [64000, 64999, 'PI'],
        [65000, 65999, 'MA'], [66000, 68899, 'PA'], [68900, 68999, 'AP'], [69000, 69299, 'AM'],
        [69300, 69399, 'RR'], [69400, 69899, 'AM'], [69900, 69999, 'AC'], [70000, 72799, 'DF'],
        [72800, 72999, 'GO'], [73000, 73699, 'DF'], [73700, 76799, 'GO'], [76800, 76999, 'RO'],
        [77000, 77995, 'TO'], [78000, 78899, 'MT'], [78900, 78999, 'RO'], [79000, 79999, 'MS'],
        [80000, 87999, 'PR'], [88000, 89999, 'SC'], [90000, 99999, 'RS'],
    ];
    foreach ($faixas as $f) {
        if ($n >= $f[0] && $n <= $f[1]) return $f[2];
    }
    return null;
}

// Consulta o endereço no ViaCEP (opcional: se falhar, o frete continua funcionando só com o estado).
// Retorna: array com o endereço | false se o CEP não existe | null se o serviço não respondeu.
function frete_buscar_endereco($cep) {
    $c = frete_limpar_cep($cep);
    if (!function_exists('curl_init')) return null;

    for ($tentativa = 0; $tentativa < 2; $tentativa++) {
        $ch = curl_init("https://viacep.com.br/ws/{$c}/json/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        if ($tentativa === 1) { // 2ª tentativa (ambientes locais sem certificados); dado público, sem risco
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        $resp = curl_exec($ch);
        $ok = !curl_errno($ch);
        curl_close($ch);

        if ($ok && $resp) {
            $d = json_decode($resp, true);
            if (is_array($d)) {
                if (!empty($d['erro'])) return false;
                return [
                    'logradouro' => $d['logradouro'] ?? '',
                    'bairro'     => $d['bairro'] ?? '',
                    'cidade'     => $d['localidade'] ?? '',
                    'uf'         => $d['uf'] ?? '',
                ];
            }
        }
    }
    return null;
}

// Soma dias úteis a partir de hoje (pula sábado e domingo)
function frete_data_entrega($diasUteis) {
    $d = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
    $add = 0;
    while ($add < $diasUteis) {
        $d->modify('+1 day');
        if ((int) $d->format('N') < 6) $add++;
    }
    return $d->format('d/m');
}

// Resumo do carrinho (subtotal e quantidade de itens) — preços sempre do catálogo no servidor
function frete_resumo_carrinho() {
    global $CATALOGO;
    $subtotal = 0; $qtd = 0;
    foreach (($_SESSION['carrinho'] ?? []) as $id => $q) {
        if (!isset($CATALOGO[$id])) continue;
        $subtotal += $CATALOGO[$id]['valor'] * (int) $q;
        $qtd += (int) $q;
    }
    return ['subtotal' => $subtotal, 'qtd' => $qtd];
}

// Opções de frete para um CEP
function frete_opcoes($cep, $subtotal, $qtd) {
    $uf = frete_uf_por_cep($cep);
    if (!$uf) return [];

    $zonas = [
        'SP' => 1,
        'RJ' => 2, 'MG' => 2, 'ES' => 2, 'PR' => 2, 'SC' => 2,
        'RS' => 3, 'DF' => 3, 'GO' => 3, 'MS' => 3, 'MT' => 3, 'BA' => 3,
        'SE' => 4, 'AL' => 4, 'PE' => 4, 'PB' => 4, 'RN' => 4, 'CE' => 4, 'PI' => 4, 'MA' => 4, 'TO' => 4,
        'PA' => 5, 'AP' => 5, 'AM' => 5, 'RR' => 5, 'AC' => 5, 'RO' => 5,
    ];
    $z = $zonas[$uf] ?? 5;

    $econ     = [1 => 19.90, 2 => 24.90, 3 => 34.90, 4 => 44.90, 5 => 59.90];
    $econDias = [1 => [2, 4], 2 => [4, 6], 3 => [6, 9], 4 => [8, 12], 5 => [11, 16]];
    $expr     = [1 => 34.90, 2 => 44.90, 3 => 59.90, 4 => 79.90, 5 => 99.90];
    $exprDias = [1 => [1, 2], 2 => [2, 3], 3 => [3, 5], 4 => [4, 6], 5 => [6, 9]];

    $extra = max(0, $qtd - 1);
    $vEcon = $econ[$z] + $extra * FRETE_ADICIONAL_ECON;
    $vExpr = $expr[$z] + $extra * FRETE_ADICIONAL_EXPR;
    if ($subtotal >= FRETE_GRATIS_ACIMA) $vEcon = 0.0;

    return [
        'economico' => [
            'tipo' => 'economico', 'nome' => 'Entrega Econômica', 'valor' => round($vEcon, 2),
            'prazo_min' => $econDias[$z][0], 'prazo_max' => $econDias[$z][1],
            'entrega_ate' => frete_data_entrega($econDias[$z][1]),
        ],
        'expresso' => [
            'tipo' => 'expresso', 'nome' => 'Entrega Expressa', 'valor' => round($vExpr, 2),
            'prazo_min' => $exprDias[$z][0], 'prazo_max' => $exprDias[$z][1],
            'entrega_ate' => frete_data_entrega($exprDias[$z][1]),
        ],
    ];
}

// Frete escolhido pelo cliente (recalculado no servidor a cada uso; nunca confia no navegador)
function frete_selecionado() {
    $sel = $_SESSION['frete'] ?? null;
    if (!$sel || empty($sel['cep']) || empty($sel['tipo'])) return null;

    $r = frete_resumo_carrinho();
    if ($r['qtd'] <= 0) return null;

    $ops = frete_opcoes($sel['cep'], $r['subtotal'], $r['qtd']);
    if (!isset($ops[$sel['tipo']])) return null;

    $op = $ops[$sel['tipo']];
    $op['cep'] = frete_formatar_cep($sel['cep']);
    $op['uf']  = frete_uf_por_cep($sel['cep']);
    return $op;
}

function frete_reais($v) {
    return 'R$ ' . number_format($v, 2, ',', '.');
}
