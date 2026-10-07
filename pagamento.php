<?php
// A conexão com o banco não é usada nesta página (o pagamento não depende
// de nenhuma tabela), então ela é opcional: se o banco não estiver
// configurado neste ambiente, a página de pagamento continua funcionando normalmente.
try {
    include('conexao.php');
} catch (\Throwable $e) {
    $conn = null;
}

$access_token = "APP_USR-1354507277436164-080615-d1f8e0bd13d944ab3e0369936d7ed627-3596816680"; 

// O pedido agora vem do carrinho (guardado na sessão). Preços vêm do catálogo no servidor.
require_once 'catalogo.php';
session_start();

$itensPedido = [];
$valor = 0;
foreach (($_SESSION['carrinho'] ?? []) as $id => $qtd) {
    if (!isset($CATALOGO[$id])) continue;
    $itensPedido[] = [
        "title"      => $CATALOGO[$id]['nome'],
        "quantity"   => (int) $qtd,
        "unit_price" => (float) $CATALOGO[$id]['valor']
    ];
    $valor += $CATALOGO[$id]['valor'] * $qtd;
}

// Sem itens no carrinho não há o que pagar: volta para o carrinho.
if (empty($itensPedido)) {
    header("Location: carrinho.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['gerar_pagamento'])) {
    $preferenceData = [
        "items" => $itensPedido,
        "back_urls" => [
            "success" => "http://localhost/gabriel/Projeto/index.php",
            "failure" => "http://localhost/gabriel/Projeto/index.php",
            "pending" => "http://localhost/gabriel/Projeto/index.php"
        ]
    ];

    $ch = curl_init('https://api.mercadopago.com/checkout/preferences');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer " . trim($access_token),
        "Content-Type: application/json",
        "Accept: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($preferenceData));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        $erro = 'Erro cURL: ' . curl_error($ch);
    } else {
        $data = json_decode($response, true);
        
        // Verifica se o init_point veio dentro da resposta da API
        if (isset($data['init_point']) && !empty($data['init_point'])) {
            header("Location: " . $data['init_point']);
            exit;
        } else {
            $erro = "Erro ao gerar preferência. Resposta: " . htmlspecialchars($response);
        }
    }
    curl_close($ch);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pagamento - AdaTech</title>
</head>
<body style="background: #0f172a; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; font-family: sans-serif;">
    <div style="background: white; color: #333; padding: 30px; border-radius: 8px; max-width: 400px; width: 100%; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
        <h2 style="color: #008b8b; margin-bottom: 20px;">Finalizar Pedido</h2>
        
        <?php if (isset($erro)): ?>
            <p style="color: red; font-size: 14px; margin-bottom: 15px;"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST">
            <?php foreach ($itensPedido as $it): ?>
                <p style="font-size: 16px; font-weight: bold; margin-bottom: 5px;"><?php echo (int) $it['quantity']; ?>x <?php echo htmlspecialchars($it['title']); ?></p>
            <?php endforeach; ?>
            <p style="font-size: 20px; font-weight: bold; color: #008b8b; margin-bottom: 20px;">Total: R$ <?php echo number_format($valor, 2, ',', '.'); ?></p>
            
            <input type="hidden" name="gerar_pagamento" value="1">

            <button type="submit" style="background: #008b8b; color: white; border: none; padding: 12px; width: 100%; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 16px;">Pagar com Mercado Pago</button>
        </form>

        <a href="carrinho.php" style="display: block; margin-top: 15px; color: #64748b; text-decoration: none; font-size: 14px;">← Voltar para o carrinho</a>
    </div>
</body>
</html>