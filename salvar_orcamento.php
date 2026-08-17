<?php
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $assunto = $_POST['assunto'] ?? '';
    $mensagem = $_POST['mensagem'] ?? '';

    if (!empty($nome) && !empty($email) && !empty($assunto)) {
        $stmt = $conn->prepare("INSERT INTO orcamentos (nome, email, assunto, mensagem) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $email, $assunto, $mensagem);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Orçamento enviado com sucesso!');
                    window.location.href = 'index.php';
                  </script>";
        } else {
            echo "Erro no banco: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Preencha todos os campos obrigatórios!";
    }
}
?>