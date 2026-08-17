<?php
session_start();
include('conexao.php');

// Trava de segurança: só admin pode excluir registros pelo painel
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_nivel'] ?? '') !== 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Previne que o admin exclua a própria conta em uso
    if ($id === $_SESSION['usuario_id']) {
        echo "<script>alert('Você não pode excluir sua própria conta enquanto está logado!'); window.location.href='painel.php';</script>";
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: painel.php");
        exit;
    } else {
        echo "<script>alert('Erro MySQL: " . addslashes($conn->error) . "'); window.location.href='painel.php';</script>";
    }
} else {
    header("Location: painel.php");
    exit;
}
?>