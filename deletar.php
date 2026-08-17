<?php
session_start();
include('conexao.php');

// Exibe erros do PHP diretamente na tela
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_nivel'] ?? '') !== 'admin') {
    exit("Acesso negado.");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    if ($id === intval($_SESSION['usuario_id'])) {
        exit("<script>alert('Você não pode excluir sua própria conta!'); window.location.href='painel.php';</script>");
    }

    // Desativa temporariamente a verificação de chaves estrangeiras para permitir apagar
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");

    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Reativa a verificação e redireciona
        $conn->query("SET FOREIGN_KEY_CHECKS = 1");
        header("Location: painel.php");
        exit;
    } else {
        $conn->query("SET FOREIGN_KEY_CHECKS = 1");
        echo "<b>Erro MySQL detalhado:</b> " . $stmt->error;
    }
} else {
    header("Location: painel.php");
    exit;
}
?>