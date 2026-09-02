<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$pedidoId = $_POST['pedido_id'] ?? '';
$produto = $_POST['produto'] ?? 'Pedido AdaTech';
$valor = isset($_POST['valor']) ? (float) $_POST['valor'] : 0.00;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento aprovado - AdaTech</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #0f172a;
            color: #1e293b;
        }
        .card {
            width: 100%;
            max-width: 520px;
            background: #fff;
            border-radius: 16px;
            padding: 34px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,.28);
        }
        .icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ccfbf1;
            color: #0f766e;
            font-size: 38px;
            font-weight: 800;
        }
        h1 { margin: 0 0 10px; color: #0f172a; }
        .muted { color: #64748b; }
        .resumo {
            text-align: left;
            margin: 24px 0;
            padding: 16px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .resumo p { margin: 7px 0; }
        .btn {
            display: inline-block;
            width: 100%;
            padding: 13px 16px;
            border-radius: 8px;
            background: #0284c7;
            color: #fff;
            text-decoration: none;
            font-weight: 700;
        }
        .notice {
            margin-top: 16px;
            color: #92400e;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <main class="card">
        <div class="icon">✓</div>
        <h1>Pagamento aprovado!</h1>
        <p class="muted">Simulação concluída com sucesso.</p>

        <section class="resumo">
            <p><strong>Pedido:</strong> <?php echo htmlspecialchars($produto, ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>ID:</strong> <?php echo htmlspecialchars($pedidoId, ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Valor:</strong> R$ <?php echo number_format($valor, 2, ',', '.'); ?></p>
            <p><strong>Status:</strong> Aprovado (teste)</p>
        </section>

        <a class="btn" href="index.php">Voltar para a loja</a>
        <p class="notice">Ambiente de demonstração: nenhum pagamento real foi processado.</p>
    </main>
</body>
</html>
