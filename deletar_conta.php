<?php
session_start();
include('conexao.php');

// Verifica se está logado e se é requisição POST
if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'erro';
    exit;
}

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Bloqueia a exclusão da própria conta logada
    if ($id === intval($_SESSION['usuario_id'])) {
        echo 'erro_propria_conta';
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo 'sucesso';
    } else {
        echo 'erro';
    }
} else {
    echo 'erro';
}
?>