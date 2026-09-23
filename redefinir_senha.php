<?php
include('conexao.php');

$mensagem = "";
$sucesso = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $nova_senha = $_POST['nova_senha'];
    $confirma_senha = $_POST['confirma_senha'];

    if (empty($email) || empty($nova_senha) || empty($confirma_senha)) {
        $mensagem = "<div style='color: #ef4444; background: #fee2e2; padding: 12px; border-radius: 6px;'>Preencha todos os campos!</div>";
    } elseif ($nova_senha !== $confirma_senha) {
        $mensagem = "<div style='color: #ef4444; background: #fee2e2; padding: 12px; border-radius: 6px;'>As senhas não coincidem!</div>";
    } else {
        // Verificar se o e-mail existe na base de dados
        $sql = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $usuario = $resultado->fetch_assoc();
            $usuario_id = $usuario['id'];

            // Encriptar a nova senha com segurança
            $hash_senha = password_hash($nova_senha, PASSWORD_DEFAULT);

            // Atualizar a senha no banco de dados
            $sql_update = "UPDATE usuarios SET senha = ? WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("si", $hash_senha, $usuario_id);

            if ($stmt_update->execute()) {
                $sucesso = true;
                $mensagem = "<div style='color: #16a34a; background: #dcfce7; padding: 12px; border-radius: 6px;'>Senha redefinida com sucesso! Redirecionando...</div>";
                echo "<script>setTimeout(function() { window.location.href='login.php'; }, 2000);</script>";
            } else {
                $mensagem = "<div style='color: #ef4444; background: #fee2e2; padding: 12px; border-radius: 6px;'>Erro ao atualizar a senha no banco de dados.</div>";
            }
        } else {
            // Por segurança, mensagem neutra ou aviso de e-mail não encontrado
            $mensagem = "<div style='color: #ef4444; background: #fee2e2; padding: 12px; border-radius: 6px;'>E-mail não encontrado no sistema.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha - AdaTech</title>
    <link rel="stylesheet" href="adatech.css">
</head>
<body style="background-color: #0f172a; color: #ffffff; font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0;">
    <div style="background: #1e293b; padding: 30px; border-radius: 8px; width: 100%; max-width: 400px; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
        <h2 style="color: #38bdf8; margin-top: 0;">Redefinir Senha</h2>
        <p style="color: #94a3b8; font-size: 14px;">Introduza o seu e-mail e defina uma nova senha.</p>
        
        <?php echo $mensagem; ?>

        <?php if (!$sucesso): ?>
        <form method="POST" style="margin-top: 20px;">
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-size: 14px;">E-mail do Utilizador</label>
                <input type="email" name="email" required style="width: 100%; padding: 10px; background: #0f172a; border: 1px solid #334155; color: white; border-radius: 4px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-size: 14px;">Nova Senha</label>
                <input type="password" name="nova_senha" required style="width: 100%; padding: 10px; background: #0f172a; border: 1px solid #334155; color: white; border-radius: 4px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; font-size: 14px;">Confirmar Nova Senha</label>
                <input type="password" name="confirma_senha" required style="width: 100%; padding: 10px; background: #0f172a; border: 1px solid #334155; color: white; border-radius: 4px; box-sizing: border-box;">
            </div>
            <button type="submit" style="width: 100%; background: #0284c7; color: white; border: none; padding: 12px; border-radius: 4px; font-weight: bold; cursor: pointer;">Atualizar Senha</button>
        </form>
        <?php endif; ?>

        <p style="text-align: center; margin-top: 20px;"><a href="login.php" style="color: #38bdf8; text-decoration: none; font-size: 14px;">&larr; Voltar ao Login</a></p>
    </div>
</body>
</html>