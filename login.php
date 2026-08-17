<?php
session_start();
include('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Adicionado o campo 'nivel' na consulta SQL
    $sql = "SELECT id, nome, senha, nivel FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($senha, $user['senha'])) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario_nome'] = $user['nome'];
            $_SESSION['usuario_nivel'] = $user['nivel']; // Salva o nível (admin/cliente) na sessão
            
            header("Location: index.php");
            exit;
        }
    }
    $erro = "E-mail ou senha incorretos!";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AdaTech</title>
    <link rel="stylesheet" href="adatech.css">
</head>
<body style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #0f172a; margin: 0; font-family: sans-serif;">

    <div style="background: #ffffff; padding: 30px; border-radius: 8px; width: 100%; max-width: 400px; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
        <h2 style="margin-top: 0; color: #0f172a; text-align: center;">Acessar AdaTech</h2>
        
        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'sucesso'): ?>
            <p style="color: green; text-align: center;">Conta criada com sucesso! Faça login abaixo.</p>
        <?php endif; ?>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'senha_alterada'): ?>
            <p style="color: green; text-align: center;">Senha redefinida com sucesso! Faça login abaixo.</p>
        <?php endif; ?>

        <?php if(isset($erro)): ?>
            <p style="color: red; text-align: center;"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php" style="display: flex; flex-direction: column; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #333;">E-mail</label>
                <input type="email" name="email" placeholder="Seu E-mail" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; color: #333;">Senha</label>
                <input type="password" name="senha" placeholder="Sua Senha" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                <div style="text-align: right; margin-top: 5px;">
                    <a href="esqueci_senha.php" style="color: #0284c7; font-size: 13px; text-decoration: none;">Esqueceu a senha?</a>
                </div>
            </div>
            <button type="submit" style="background: #0284c7; color: white; border: none; padding: 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">Entrar</button>
        </form>

        <p style="text-align: center; margin-top: 15px; font-size: 14px;">
            Não tem uma conta? <a href="cadastro.php" style="color: #0284c7;">Cadastre-se</a>
        </p>
    </div>

</body>
</html>