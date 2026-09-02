<?php
session_start();

$valor = isset($_POST['valor']) ? (float) str_replace(',', '.', $_POST['valor']) : 0.00;
$produto = isset($_POST['produto']) ? trim($_POST['produto']) : 'Carrinho AdaTech';

if ($valor <= 0) {
    $valor = 0.00;
}

// Código apenas demonstrativo para o Projeto Integrador.
// Não representa uma cobrança PIX real.
$pedidoId = strtoupper(substr(hash('sha256', $produto . '|' . $valor . '|' . microtime(true)), 0, 12));
$pixDemo = 'ADATECH-DEMO|' . $pedidoId . '|R$' . number_format($valor, 2, '.', '') . '|' . $produto;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento PIX - AdaTech</title>
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
            padding: 32px;
            box-shadow: 0 20px 50px rgba(0,0,0,.28);
        }
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            background: #e0f2fe;
            color: #0369a1;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 14px;
        }
        h1 {
            margin: 0 0 8px;
            color: #0f172a;
            font-size: 28px;
        }
        .sub {
            margin: 0 0 24px;
            color: #64748b;
            font-size: 14px;
        }
        .resumo {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }
        .resumo p { margin: 6px 0; }
        .valor {
            color: #008b8b;
            font-weight: 800;
            font-size: 24px;
        }
        #qrcode {
            width: 220px;
            min-height: 220px;
            margin: 20px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px;
            background: #fff;
        }
        .copy-row {
            display: flex;
            gap: 8px;
            margin: 16px 0;
        }
        .copy-row input {
            flex: 1;
            min-width: 0;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background: #f8fafc;
            color: #334155;
        }
        button, .btn-link {
            border: 0;
            border-radius: 8px;
            padding: 12px 16px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }
        .btn-copy { background: #0284c7; color: #fff; }
        .btn-confirm {
            width: 100%;
            background: #008b8b;
            color: #fff;
            font-size: 16px;
            margin-top: 8px;
        }
        .btn-back {
            display: block;
            margin-top: 12px;
            color: #64748b;
            background: transparent;
        }
        .notice {
            margin-top: 18px;
            padding: 12px;
            border-radius: 10px;
            background: #fff7ed;
            color: #9a3412;
            font-size: 12px;
            line-height: 1.5;
        }
        @media (max-width: 560px) {
            .card { padding: 22px; }
            .copy-row { flex-direction: column; }
            #qrcode { width: 190px; min-height: 190px; }
        }
    </style>
</head>
<body>
    <main class="card">
        <span class="badge">PIX • DEMONSTRAÇÃO</span>
        <h1>Finalizar pedido</h1>
        <p class="sub">Fluxo de pagamento simulado para apresentação do Projeto Integrador.</p>

        <section class="resumo">
            <p><strong>Pedido:</strong> <?php echo htmlspecialchars($produto, ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>ID:</strong> <?php echo htmlspecialchars($pedidoId, ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="valor">R$ <?php echo number_format($valor, 2, ',', '.'); ?></p>
        </section>

        <div id="qrcode" aria-label="QR Code de demonstração"></div>

        <div class="copy-row">
            <input id="pix-code" type="text" readonly value="<?php echo htmlspecialchars($pixDemo, ENT_QUOTES, 'UTF-8'); ?>">
            <button type="button" class="btn-copy" onclick="copiarPix()">Copiar código</button>
        </div>

        <form method="POST" action="pagamento_sucesso.php">
            <input type="hidden" name="pedido_id" value="<?php echo htmlspecialchars($pedidoId, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="produto" value="<?php echo htmlspecialchars($produto, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="valor" value="<?php echo number_format($valor, 2, '.', ''); ?>">
            <button type="submit" class="btn-confirm">Confirmar pagamento de teste</button>
        </form>

        <a class="btn-link btn-back" href="index.php">← Voltar para o site AdaTech</a>

        <div class="notice">
            Este PIX é apenas uma simulação. Nenhuma cobrança real é criada e nenhum dinheiro é movimentado.
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        const codigoPix = <?php echo json_encode($pixDemo, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

        new QRCode(document.getElementById('qrcode'), {
            text: codigoPix,
            width: 200,
            height: 200
        });

        function copiarPix() {
            const input = document.getElementById('pix-code');
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value).then(() => {
                alert('Código PIX de demonstração copiado!');
            }).catch(() => {
                document.execCommand('copy');
                alert('Código PIX de demonstração copiado!');
            });
        }
    </script>
</body>
</html>
