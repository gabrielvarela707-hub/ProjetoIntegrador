<?php
session_start();
require 'catalogo.php';

if (!isset($_SESSION['carrinho']) || !is_array($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// ---------- Ações do carrinho (adicionar, aumentar, diminuir, remover, limpar) ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    $id   = $_POST['id'] ?? '';

    if ($acao === 'limpar') {
        $_SESSION['carrinho'] = [];
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

                <div class="cart-total">
                    <span>Total</span>
                    <strong><?php echo $reais($total); ?></strong>
                </div>

                <div class="cart-actions">
                    <form method="POST" action="carrinho.php">
                        <input type="hidden" name="acao" value="limpar">
                        <button type="submit" class="btn-secondary">Esvaziar carrinho</button>
                    </form>

                    <form method="POST" action="pagamento.php">
                        <input type="hidden" name="finalizar" value="1">
                        <button type="submit" class="btn-primary">Comprar</button>
                    </form>
                </div>

            </div>

        <?php endif; ?>

    </div>
</main>

</body>
</html>
