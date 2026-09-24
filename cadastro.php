<?php
include('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $repetir_senha = $_POST['repetir_senha'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];
    $data_nascimento = $_POST['data_nascimento'];
    $genero = $_POST['genero'];

    // Validação básica se as senhas coincidem
    if ($senha !== $repetir_senha) {
        $erro = "As senhas não coincidem!";
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Certifique-se de que a tabela 'usuarios' tenha essas colunas
        $sql = "INSERT INTO usuarios (nome, email, senha, telefone, cpf, data_nascimento, genero) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $nome, $email, $senha_hash, $telefone, $cpf, $data_nascimento, $genero);

        if ($stmt->execute()) {
            header("Location: login.php?msg=sucesso");
            exit;
        } else {
            $erro = "Erro ao cadastrar: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - AdaTech</title>
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

            <h1>Crie sua conta e tenha a AdaTech ao seu lado.</h1>

            <p>
                Cadastre-se para solicitar orçamentos, acompanhar
                atendimentos e falar direto com nosso time de suporte.
            </p>

            <ul class="auth-brand-list">
                <li>Cadastro rápido e gratuito</li>
                <li>Seus dados protegidos</li>
                <li>Acompanhe seus orçamentos</li>
            </ul>

        </div>

        <!-- ==================================================
             FORMULÁRIO DE CADASTRO
        =================================================== -->
        <div class="auth-form-panel">

            <div class="auth-card">

                <a href="index.php" class="auth-back-link">&larr; Voltar ao site</a>

                <h2>Criar conta</h2>
                <p class="auth-subtitle">Preencha seus dados para começar.</p>

                <?php if(isset($erro)): ?>
                    <div class="auth-msg auth-msg-error"><?php echo $erro; ?></div>
                <?php endif; ?>

                <form method="POST" action="cadastro.php" class="auth-form">

                    <div class="form-group">
                        <label>Nome Completo</label>
                        <input type="text" name="nome" placeholder="Seu nome" required>
                    </div>

                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" name="email" placeholder="voce@empresa.com" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Senha</label>
                            <input type="password" name="senha" placeholder="Sua senha" required>
                        </div>

                        <div class="form-group">
                            <label>Repetir Senha</label>
                            <input type="password" name="repetir_senha" placeholder="Confirme" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Telefone</label>
                            <input type="tel" name="telefone" placeholder="(00) 00000-0000">
                        </div>

                        <div class="form-group">
                            <label>CPF</label>
                            <input type="text" name="cpf" placeholder="000.000.000-00">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Data de Nascimento</label>
                            <input type="date" name="data_nascimento">
                        </div>

                        <div class="form-group">
                            <label>Gênero</label>
                            <select name="genero">
                                <option value="" disabled selected>Selecione...</option>
                                <option value="masculino">Masculino</option>
                                <option value="feminino">Feminino</option>
                                <option value="outro">Outro</option>
                                <option value="nao_informar">Prefiro não informar</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="auth-submit-btn">Cadastrar</button>

                </form>

                <p class="auth-switch">
                    Já tem uma conta? <a href="login.php">Faça Login</a>
                </p>

            </div>

        </div>

    </div>

</body>
</html>