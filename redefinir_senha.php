<?php
include('conexao.php');

$mensagem = "";
$token_valido = false;
$token = $_GET['token'] ?? '';

if (!empty($token)) {
    // Validar token e verificar se a validade ainda é futura (NOW())
    $sql = "SELECT id FROM usuarios WHERE token_recuperacao = ? AND token_validade > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $token_valido = true;
        $usuario = $resultado->fetch_assoc();
        $usuario_id = $usuario['id'];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nova_senha = $_POST['nova_senha'];
            $confirma_senha = $_POST['confirma_senha'];

            if ($nova_senha !== $confirma_senha) {
                $mensagem = "<div style='background: #fee2e2; color: #ef4444; padding: 10px; border-radius: 4px; font-size: 14px; margin-bottom: 15px; text-align: center; font-weight: bold;'>As senhas não coincidem!</div>";
            } else {
                $hash_senha = password_hash($nova_senha, PASSWORD_DEFAULT);

                // Atualizar senha e limpar o token usado para que não possa ser reutilizado
                $sql_update = "UPDATE usuarios SET senha = ?, token_recuperacao = NULL, token_validade = NULL WHERE id = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("si", $hash_senha, $usuario_id);

                if ($stmt_update->execute()) {
                    echo "<script>alert('Senha redefinida com sucesso!'); window.location.href='login.php';</script>";
                    exit;
                } else {
                    $mensagem = "<div style='background: #fee2e2; color: #ef4444; padding: 10px; border-radius: 4px; font-size: 14px; margin-bottom: 15px; text-align: center; font-weight: bold;'>Erro ao atualizar a senha.</div>";
                }
            }
        }
    } else {
        $mensagem = "<div style='background: #fee2e2; color: #ef4444; padding: 10px; border-radius: 4px; font-size: 14px; margin-bottom: 15px; text-align: center; font-weight: bold;'>Link inválido ou expirado! Solicite uma nova recuperação.</div>";
    }
} else {
    $mensagem = "<div style='background: #fee2e2; color: #ef4444; padding: 10px; border-radius: 4px; font-size: 14px; margin-bottom: 15px; text-align: center; font-weight: bold;'>Token não fornecido.</div>";
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
        <h2 style="margin-top: 0; color: #38bdf8; text-align: center;">Nova Senha</h2>
        
        <?php echo $mensagem; ?>

        <?php if ($token_valido): ?>
        <form method="POST" style="display: flex; flex-direction: column; gap: 15px;">
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
        <?php endif; ?>

        <p style="text-align: center; margin-top: 20px; font-size: 14px;">
            <a href="login.php" style="color: #38bdf8; text-decoration: none;">&larr; Voltar ao Login</a>
        </p>
    </div>

</body>
</html>