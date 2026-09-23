<?php
include('conexao.php');

$email = $_GET['email'] ?? '';
$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $nova_senha = $_POST['nova_senha'];
    $confirma_senha = $_POST['confirma_senha'];

    if (!empty($email) && !empty($nova_senha) && !empty($confirma_senha)) {
        if ($nova_senha !== $confirma_senha) {
            $erro = "As senhas não coincidem!";
        } else {
            // Encriptar a palavra-passe com segurança
            $hash_senha = password_hash($nova_senha, PASSWORD_DEFAULT);

            $sql = "UPDATE usuarios SET senha = ? WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $hash_senha, $email);

            if ($stmt->execute()) {
                echo "<script>alert('Senha redefinida com sucesso!'); window.location.href='login.php';</script>";
                exit;
            } else {
                $erro = "Erro ao redefinir a senha no banco de dados.";
            }
        }
    } else {
        $erro = "Preencha todos os campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Senha - AdaTech</title>
    <link rel="stylesheet" href="adatech.css">
</head>
<body style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #0f172a; margin: 0; font-family: sans-serif;">

    <div style="background: #1e293b; padding: 30px; border-radius: 8px; width: 100%; max-width: 400px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); color: #ffffff;">
        <h2 style="margin-top: 0; color: #38bdf8; text-align: center;">Criar Nova Senha</h2>
        
        <p style="color: #94a3b8; font-size: 14px; margin-bottom: 20px; text-align: center;">
            Redefinindo senha para:<br><strong style="color: #38bdf8;"><?php echo htmlspecialchars($email); ?></strong>
        </p>

        <?php if(!empty($erro)): ?>
            <div style="background: #fee2e2; color: #ef4444; padding: 10px; border-radius: 4px; font-size: 14px; margin-bottom: 15px; text-align: center; font-weight: bold;">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="redefinir_senha.php?email=<?php echo urlencode($email); ?>" style="display: flex; flex-direction: column; gap: 15px;">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

            <div>
                <label style="display: block; margin-bottom: 5px; color: #cbd5e1; font-size: 14px;">Nova Senha</label>
                <input type="password" name="nova_senha" placeholder="Digite a nova senha" required style="width: 100%; padding: 10px; background: #0f172a; border: 1px solid #334155; color: white; border-radius: 4px; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 5px; color: #cbd5e1; font-size: 14px;">Confirmar Nova Senha</label>
                <input type="password" name="confirma_senha" placeholder="Confirme a nova senha" required style="width: 100%; padding: 10px; background: #0f172a; border: 1px solid #334155; color: white; border-radius: 4px; box-sizing: border-box;">
            </div>
            
            <button type="submit" style="background: #0284c7; color: white; border: none; padding: 12px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; margin-top: 5px;">Salvar Nova Senha</button>
        </form>

        <p style="text-align: center; margin-top: 20px; font-size: 14px;">
            <a href="login.php" style="color: #38bdf8; text-decoration: none;">&larr; Voltar ao Login</a>
        </p>
    </div>

</body>
</html>