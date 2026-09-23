<?php
session_start();
include('conexao.php');

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(403);
    exit('acesso_negado');
}

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $sql = "DELETE FROM orcamentos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "sucesso";
    } else {
        echo "erro";
    }
}
?>