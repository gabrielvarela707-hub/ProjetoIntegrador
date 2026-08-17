<?php
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $mensagem = $_POST['mensagem'] ?? '';

    if (!empty($nome) && !empty($email)) {
        $stmt = $conn->prepare("INSERT INTO contatos (nome, email, telefone, mensagem) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $email, $telefone, $mensagem);

        if ($stmt->execute()) {
            echo "<script>alert('Contato salvo!'); window.location.href='index.php';</script>";
        } else {
            echo "Erro: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>