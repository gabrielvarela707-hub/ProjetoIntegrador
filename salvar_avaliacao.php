<?php
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $nota = intval($_POST['nota'] ?? 0);
    $comentario = trim($_POST['comentario'] ?? '');

    if (!empty($nome) && $nota >= 1 && $nota <= 5) {
        $stmt = $conn->prepare("INSERT INTO avaliacoes (nome, nota, comentario) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $nome, $nota, $comentario);

        if ($stmt->execute()) {
            echo "<script>alert('Obrigado pela sua avaliação!'); window.location.href='index.php#avaliacao';</script>";
        } else {
            echo "Erro: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "<script>alert('Preencha seu nome e escolha uma nota de 1 a 5 estrelas.'); window.location.href='index.php#avaliacao';</script>";
    }
}
?>
