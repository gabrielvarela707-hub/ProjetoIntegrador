<?php
// Ativar exibição de erros para encontrarmos o problema
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include('conexao.php'); // <-- ESSA LINHA CONECTA AO BANCO DE DADOS

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
<body>

    <div class="auth-wrapper">

        <!-- ==================================================
             PAINEL DE MARCA
        =================================================== -->
        <div class="auth-brand-panel">

            <div class="hero-orb hero-orb-1"></div>
            <div class="hero-orb hero-orb-2"></div>

            <a href="index.php" class="auth-brand-logo">
                Ada<span>Tech</span>
            </a>

            <h1>Tecnologia e suporte de TI que não deixa sua empresa parar.</h1>

            <p>
                Acesse sua conta para acompanhar orçamentos,
                falar com nosso time e acompanhar o atendimento.
            </p>

            <ul class="auth-brand-list">
                <li>Suporte técnico especializado</li>
                <li>Revenda autorizada Dell</li>
                <li>Atendimento rápido e humano</li>
            </ul>

        </div>

        <!-- ==================================================
             FORMULÁRIO DE LOGIN
        =================================================== -->
        <div class="auth-form-panel">

            <div class="auth-card">

                <a href="index.php" class="auth-back-link">&larr; Voltar ao site</a>

                <h2>Acessar conta</h2>
                <p class="auth-subtitle">Entre com seus dados para continuar.</p>

                <?php if(isset($_GET['msg']) && $_GET['msg'] == 'sucesso'): ?>
                    <div class="auth-msg auth-msg-success">Conta criada com sucesso! Faça login abaixo.</div>
                <?php endif; ?>

                <?php if(isset($_GET['msg']) && $_GET['msg'] == 'senha_alterada'): ?>
                    <div class="auth-msg auth-msg-success">Senha redefinida com sucesso! Faça login abaixo.</div>
                <?php endif; ?>

                <?php if(isset($erro)): ?>
                    <div class="auth-msg auth-msg-error"><?php echo $erro; ?></div>
                <?php endif; ?>

                <form method="POST" action="login.php" class="auth-form">

                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" name="email" placeholder="voce@empresa.com" required>
                    </div>

                    <div class="form-group">
                        <label>Senha</label>
                        <input type="password" name="senha" placeholder="Sua senha" required>
                        <div class="form-forgot">
                            <a href="esqueci_senha.php">Esqueceu a senha?</a>
                        </div>
                    </div>

                    <button type="submit" class="auth-submit-btn">Entrar</button>

                </form>

                <p class="auth-switch">
                    Não tem uma conta? <a href="cadastro.php">Cadastre-se</a>
                </p>

            </div>

        </div>

    </div>

</body>
</html>