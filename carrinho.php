<?php
session_start();
require 'catalogo.php';
require_once 'frete_lib.php';

if (!isset($_SESSION['carrinho']) || !is_array($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// ---------- Ações do carrinho (adicionar, aumentar, diminuir, remover, limpar) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    $id   = $_POST['id'] ?? '';

    if ($acao === 'limpar') {
        $_SESSION['carrinho'] = [];
        unset($_SESSION['frete']);
    } elseif (isset($CATALOGO[$id])) {
        $atual = $_SESSION['carrinho'][$id] ?? 0;

        if ($acao === 'adicionar' || $acao === 'aumentar') {
            $_SESSION['carrinho'][$id] = min($atual + 1, 99);
            if ($acao === 'adicionar' && ($_POST['retorno'] ?? '') === 'produtos') {
                $_SESSION['carrinho_aviso'] = $CATALOGO[$id]['nome'] . ' adicionado ao carrinho';
                header('Location: index.php#produtos');
                exit;
            }
        } elseif ($acao === 'diminuir') {
            if ($atual > 1) {
                $_SESSION['carrinho'][$id] = $atual - 1;
            } else {
                unset($_SESSION['carrinho'][$id]);
            }
        } elseif ($acao === 'remover') {
            unset($_SESSION['carrinho'][$id]);
        }
    }

    // Padrão POST-Redirect-GET: evita reenviar o formulário ao atualizar a página
    header('Location: carrinho.php');
    exit;
}

// ---------- Monta os itens e o total ----------
$itens = [];
$total = 0;
foreach ($_SESSION['carrinho'] as $id => $qtd) {
    if (!isset($CATALOGO[$id])) continue;
    $subtotal = $CATALOGO[$id]['valor'] * $qtd;
    $total += $subtotal;
    $itens[] = ['id' => $id, 'nome' => $CATALOGO[$id]['nome'], 'valor' => $CATALOGO[$id]['valor'], 'qtd' => $qtd, 'subtotal' => $subtotal];
}
$qtdCarrinho = array_sum(array_column($itens, 'qtd'));
$reais = fn($v) => 'R$ ' . number_format($v, 2, ',', '.');

$freteSel   = frete_selecionado();
$freteValor = $freteSel ? $freteSel['valor'] : 0;
$totalFinal = $total + $freteValor;
$faltouFrete = isset($_GET['falta_frete']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho | AdaTech</title>
    <link rel="stylesheet" href="adatech.css">
</head>
<body>

<header class="navbar">
    <div class="container nav-container">
        <a href="index.php" class="logo">Ada<span>Tech</span></a>
        <a href="index.php#produtos" class="btn-chip btn-chip-ghost">← Continuar comprando</a>
    </div>
</header>

<main class="section-padding">
    <div class="container cart-page">

        <h1 class="section-title" style="text-align:center;">Meu Carrinho</h1>

        <?php if (empty($itens)): ?>

            <div class="card cart-empty">
                <p>Seu carrinho está vazio.</p>
                <a href="index.php#produtos" class="btn-secondary">Ver produtos</a>
            </div>

        <?php else: ?>

            <div class="card cart-box">

                <?php foreach ($itens as $item): ?>
                    <div class="cart-item">

                        <div class="cart-item-info">
                            <strong><?php echo htmlspecialchars($item['nome']); ?></strong>
                            <span><?php echo $reais($item['valor']); ?> cada</span>
                        </div>

                        <div class="cart-qty">
                            <form method="POST" action="carrinho.php">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
                                <button type="submit" name="acao" value="diminuir" class="qty-btn" aria-label="Diminuir">−</button>
                            </form>
                            <span class="qty-num"><?php echo (int) $item['qtd']; ?></span>
                            <form method="POST" action="carrinho.php">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
                                <button type="submit" name="acao" value="aumentar" class="qty-btn" aria-label="Aumentar">+</button>
                            </form>
                        </div>

                        <div class="cart-item-total"><?php echo $reais($item['subtotal']); ?></div>

                        <form method="POST" action="carrinho.php">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
                            <button type="submit" name="acao" value="remover" class="cart-remove">Remover</button>
                        </form>

                    </div>
                <?php endforeach; ?>

                <!-- ================= FRETE ================= -->
                <div class="frete-box" id="freteBox" data-cep="<?php echo $freteSel ? htmlspecialchars($freteSel['cep']) : ''; ?>" data-tipo="<?php echo $freteSel ? htmlspecialchars($freteSel['tipo']) : ''; ?>">

                    <div class="frete-head">
                        <h3>Calcular frete</h3>
                        <span class="frete-gratis">Frete grátis acima de <?php echo $reais(FRETE_GRATIS_ACIMA); ?></span>
                    </div>

                    <div class="frete-input<?php echo $faltouFrete ? ' frete-alerta' : ''; ?>" id="freteInputWrap">
                        <input type="text" id="cepInput" placeholder="00000-000" inputmode="numeric" maxlength="9" autocomplete="postal-code" aria-label="CEP" value="<?php echo $freteSel ? htmlspecialchars($freteSel['cep']) : ''; ?>">
                        <button type="button" id="btnFrete">Calcular</button>
                    </div>

                    <div class="frete-links">
                        <a href="https://buscacepinter.correios.com.br/app/endereco/index.php" target="_blank" rel="noopener">Não sei o CEP</a>
                    </div>

                    <p class="frete-msg<?php echo $faltouFrete ? ' erro' : ''; ?>" id="freteMsg"><?php echo $faltouFrete ? 'Calcule e escolha o frete para continuar a compra.' : ''; ?></p>
                    <p class="frete-endereco" id="freteEndereco"></p>
                    <div class="frete-opcoes" id="freteOpcoes"></div>
                </div>

                <!-- ================= RESUMO ================= -->
                <div class="cart-resumo">
                    <div class="cart-resumo-linha">
                        <span>Subtotal</span>
                        <span><?php echo $reais($total); ?></span>
                    </div>
                    <div class="cart-resumo-linha">
                        <span>Frete</span>
                        <span id="freteValorTxt"><?php echo $freteSel ? ($freteValor > 0 ? $reais($freteValor) : 'Grátis') : 'Calcule o frete'; ?></span>
                    </div>
                </div>

                <div class="cart-total">
                    <span>Total</span>
                    <strong id="totalTxt"><?php echo $reais($totalFinal); ?></strong>
                </div>

                <div class="cart-actions">
                    <form method="POST" action="carrinho.php">
                        <input type="hidden" name="acao" value="limpar">
                        <button type="submit" class="btn-secondary">Esvaziar carrinho</button>
                    </form>

                    <form method="POST" action="pagamento.php">
                        <input type="hidden" name="finalizar" value="1">
                        <button type="submit" class="btn-primary" id="btnComprar"<?php echo $freteSel ? '' : ' disabled'; ?>>Comprar</button>
                    </form>
                </div>

            </div>

        <?php endif; ?>

    </div>
</main>


<script>
(function () {
    var box = document.getElementById('freteBox');
    if (!box) return;

    var cepInput = document.getElementById('cepInput');
    var btn      = document.getElementById('btnFrete');
    var msg      = document.getElementById('freteMsg');
    var endr     = document.getElementById('freteEndereco');
    var lista    = document.getElementById('freteOpcoes');
    var freteTxt = document.getElementById('freteValorTxt');
    var totalTxt = document.getElementById('totalTxt');
    var comprar  = document.getElementById('btnComprar');
    var cepAtual = '';

    function reais(v) { return v > 0 ? v.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) : 'Grátis'; }
    function esc(t) { var d = document.createElement('div'); d.textContent = t == null ? '' : t; return d.innerHTML; }
    function post(dados) {
        var corpo = Object.keys(dados).map(function (k) { return encodeURIComponent(k) + '=' + encodeURIComponent(dados[k]); }).join('&');
        return fetch('frete.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: corpo })
            .then(function (r) { return r.json(); });
    }
    function aviso(t, erro) { msg.textContent = t || ''; msg.className = 'frete-msg' + (erro ? ' erro' : ''); }

    // máscara 00000-000
    cepInput.addEventListener('input', function () {
        var n = cepInput.value.replace(/\D/g, '').slice(0, 8);
        cepInput.value = n.length > 5 ? n.slice(0, 5) + '-' + n.slice(5) : n;
        document.getElementById('freteInputWrap').classList.remove('frete-alerta');
    });
    cepInput.addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); calcular(); } });
    btn.addEventListener('click', calcular);

    function calcular(tipoPreSelecionado) {
        var cep = cepInput.value.replace(/\D/g, '');
        if (cep.length !== 8) { aviso('Digite os 8 números do seu CEP.', true); return; }

        btn.disabled = true; btn.textContent = 'Calculando...';
        aviso(''); endr.textContent = ''; lista.innerHTML = '';

        post({ acao: 'calcular', cep: cep }).then(function (d) {
            btn.disabled = false; btn.textContent = 'Calcular';
            if (!d.ok) { aviso(d.erro, true); return; }
            cepAtual = cep;

            if (d.endereco) {
                var partes = [d.endereco.logradouro, d.endereco.bairro, d.endereco.cidade + ' - ' + (d.endereco.uf || d.uf)].filter(Boolean);
                endr.textContent = 'Entregar em: ' + partes.join(', ') + ' (' + d.cep + ')';
            } else {
                endr.textContent = 'Entregar no CEP ' + d.cep + ' (' + d.uf + ')';
            }

            var pre = typeof tipoPreSelecionado === 'string' ? tipoPreSelecionado : '';
            lista.innerHTML = d.opcoes.map(function (o) {
                var prazo = o.prazo_min === o.prazo_max ? o.prazo_max + ' dia(s) úteis' : o.prazo_min + ' a ' + o.prazo_max + ' dias úteis';
                return '<label class="frete-opcao">' +
                    '<input type="radio" name="frete" value="' + esc(o.tipo) + '"' + (o.tipo === pre ? ' checked' : '') + '>' +
                    '<span class="frete-opcao-info"><strong>' + esc(o.nome) + '</strong>' +
                    '<small>Receba em ' + esc(prazo) + ' · até ' + esc(o.entrega_ate) + '</small></span>' +
                    '<span class="frete-opcao-preco' + (o.valor > 0 ? '' : ' gratis') + '">' + reais(o.valor) + '</span>' +
                    '</label>';
            }).join('');

            if (d.falta_gratis > 0) {
                var dica = document.createElement('p');
                dica.className = 'frete-dica';
                dica.textContent = 'Faltam ' + d.falta_gratis_fmt + ' para ganhar frete grátis na entrega econômica.';
                lista.appendChild(dica);
            }

            lista.querySelectorAll('input[name=frete]').forEach(function (r) {
                r.addEventListener('change', function () { selecionar(r.value); });
            });
        }).catch(function () {
            btn.disabled = false; btn.textContent = 'Calcular';
            aviso('Não foi possível calcular agora. Tente novamente.', true);
        });
    }

    function selecionar(tipo) {
        post({ acao: 'selecionar', cep: cepAtual, tipo: tipo }).then(function (d) {
            if (!d.ok) { aviso(d.erro, true); return; }
            aviso('');
            freteTxt.textContent = d.valor_fmt;
            totalTxt.textContent = d.total_fmt;
            if (comprar) comprar.disabled = false;
        });
    }

    // Se já havia um frete escolhido, mostra de novo as opções com ele marcado
    if (box.getAttribute('data-cep')) { calcular(box.getAttribute('data-tipo')); }
})();
</script>

</body>
</html>
