<?php
include('conexao.php');

$email = $_GET['email'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $nova_senha = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);

    $sql = "UPDATE usuarios SET senha = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $nova_senha, $email);

    if ($stmt->execute()) {
        header("Location: login.php?msg=senha_alterada");
        exit;
    } else {
        $erro = "Erro ao redefinir a senha.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Senha - AdaTech</title>
    <link rel="stylesheet" href="adatech.css">
</head>
<body style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #0f172a; margin: 0; font-family: sans-serif;">

    <div style="background: #ffffff; padding: 30px; border-radius: 8px; width: 100%; max-width: 400px; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
        <h2 style="margin-top: 0; color: #0f172a; text-align: center;">Criar Nova Senha</h2>
        
        <?php if(isset($erro)): ?>
            <p style="color: red; text-align: center;"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST" action="redefinir_senha.php" style="display: flex; flex-direction: column; gap: 15px;">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            
            <p style="color: #555; font-size: 14px; margin: 0; text-align: center;">
                Redefinindo senha para:<br><strong><?php echo htmlspecialchars($email); ?></strong>
            </p>

            <div>
                <label style="display: block; margin-bottom: 5px; color: #333;">Nova Senha</label>
                <input type="password" name="nova_senha" placeholder="Digite a nova senha" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>
            
            <button type="submit" style="background: #0284c7; color: white; border: none; padding: 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">Salvar Nova Senha</button>
        </form>

        <p style="text-align: center; margin-top: 15px; font-size: 14px;">
            <a href="login.php" style="color: #0284c7;">Cancelar</a>
        </p>
    </div>

</body>
</html>