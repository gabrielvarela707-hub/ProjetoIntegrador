<?php
session_start();
include('conexao.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM contatos WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "sucesso";
    } else {
        echo "erro";
    }
}
?>