<?php
// Endpoint do frete (chamado pelo carrinho via JavaScript). Responde em JSON.
require_once __DIR__ . '/frete_lib.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function responder($dados, $codigo = 200) {
    http_response_code($codigo);
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(['ok' => false, 'erro' => 'Método inválido.'], 405);
}

$acao   = $_POST['acao'] ?? '';
$resumo = frete_resumo_carrinho();

if ($resumo['qtd'] <= 0) {
    responder(['ok' => false, 'erro' => 'Seu carrinho está vazio.']);
}

if ($acao === 'calcular') {
    $cep = frete_limpar_cep($_POST['cep'] ?? '');
    if (strlen($cep) !== 8 || !frete_uf_por_cep($cep)) {
        responder(['ok' => false, 'erro' => 'CEP inválido. Digite os 8 números do seu CEP.']);
    }

    $endereco = frete_buscar_endereco($cep);
    if ($endereco === false) {
        responder(['ok' => false, 'erro' => 'CEP não encontrado. Confira os números e tente de novo.']);
    }

    $opcoes = array_values(frete_opcoes($cep, $resumo['subtotal'], $resumo['qtd']));
    $faltaGratis = max(0, FRETE_GRATIS_ACIMA - $resumo['subtotal']);

    responder([
        'ok'          => true,
        'cep'         => frete_formatar_cep($cep),
        'uf'          => frete_uf_por_cep($cep),
        'endereco'    => $endereco ?: null,
        'opcoes'      => $opcoes,
        'falta_gratis'=> $faltaGratis,
        'falta_gratis_fmt' => frete_reais($faltaGratis),
    ]);
}

if ($acao === 'selecionar') {
    $cep  = frete_limpar_cep($_POST['cep'] ?? '');
    $tipo = $_POST['tipo'] ?? '';
    $ops  = frete_opcoes($cep, $resumo['subtotal'], $resumo['qtd']);

    if (!isset($ops[$tipo])) {
        responder(['ok' => false, 'erro' => 'Opção de frete inválida.']);
    }

    $_SESSION['frete'] = ['cep' => $cep, 'tipo' => $tipo];
    $op = $ops[$tipo];
    $total = $resumo['subtotal'] + $op['valor'];

    responder([
        'ok'        => true,
        'valor'     => $op['valor'],
        'valor_fmt' => $op['valor'] > 0 ? frete_reais($op['valor']) : 'Grátis',
        'total_fmt' => frete_reais($total),
    ]);
}

if ($acao === 'remover') {
    unset($_SESSION['frete']);
    responder([
        'ok'        => true,
        'total_fmt' => frete_reais($resumo['subtotal']),
    ]);
}

responder(['ok' => false, 'erro' => 'Ação inválida.']);
