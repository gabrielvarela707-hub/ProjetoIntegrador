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
<body style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #0f172a; margin: 0; padding: 20px 0; font-family: sans-serif; box-sizing: border-box;">

    <div style="background: #ffffff; padding: 30px; border-radius: 8px; width: 100%; max-width: 400px; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
        <h2 style="margin-top: 0; color: #0f172a; text-align: center;">Criar Conta</h2>
        
        <?php if(isset($erro)): ?>
            <p style="color: red; text-align: center;"><?php echo $erro; ?></p>
        <?php endif; ?>

        <form method="POST" action="cadastro.php" style="display: flex; flex-direction: column; gap: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; color: #333;">Nome Completo</label>
                <input type="text" name="nome" placeholder="Seu Nome" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>
            
            <div>
                <label style="display: block; margin-bottom: 5px; color: #333;">E-mail</label>
                <input type="email" name="email" placeholder="Seu E-mail" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 5px; color: #333;">Senha</label>
                <input type="password" name="senha" placeholder="Sua Senha" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 5px; color: #333;">Repetir Senha</label>
                <input type="password" name="repetir_senha" placeholder="Confirme sua senha" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 5px; color: #333;">Telefone</label>
                <input type="tel" name="telefone" placeholder="(00) 00000-0000" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 5px; color: #333;">CPF</label>
                <input type="text" name="cpf" placeholder="000.000.000-00" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 5px; color: #333;">Data de Nascimento</label>
                <input type="date" name="data_nascimento" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; background-color: #fff;">
            </div>

            <div>
                <label style="display: block; margin-bottom: 5px; color: #333;">Gênero</label>
                <select name="genero" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; background-color: #fff;">
                    <option value="" disabled selected>Selecione...</option>
                    <option value="masculino">Masculino</option>
                    <option value="feminino">Feminino</option>
                    <option value="outro">Outro</option>
                    <option value="nao_informar">Prefiro não informar</option>
                </select>
            </div>

            <button type="submit" style="background: #0284c7; color: white; border: none; padding: 12px; border-radius: 4px; cursor: pointer; font-weight: bold; margin-top: 10px;">Cadastrar</button>
        </form>

        <p style="text-align: center; margin-top: 15px; font-size: 14px;">
            Já tem uma conta? <a href="login.php" style="color: #0284c7;">Faça Login</a>
        </p>
    </div>

</body>
</html>