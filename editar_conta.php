<?php
session_start();
include('conexao.php');

if (!isset($_SESSION['usuario_id'])) { 
    header("Location: login.php"); 
    exit; 
}

$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    
    if (!empty($_POST['senha'])) {
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET email=?, telefone=?, endereco=?, senha=? WHERE id=?");
        $stmt->bind_param("ssssi", $email, $telefone, $endereco, $senha, $id);
    } else {
        $stmt = $conn->prepare("UPDATE usuarios SET email=?, telefone=?, endereco=? WHERE id=?");
        $stmt->bind_param("sssi", $email, $telefone, $endereco, $id);
    }
    
    $stmt->execute();
    header("Location: painel.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Conta - AdaTech</title>
</head>
<body style="background:#0f172a; color:#fff; font-family:sans-serif; margin:0; padding:40px; display:flex; justify-content:center;">

    <div style="background:#1e293b; padding:30px; border-radius:8px; width:100%; max-width:500px; box-shadow:0 4px 10px rgba(0,0,0,0.3);">
        <h2 style="color:#38bdf8; margin-top:0; border-bottom:1px solid #334155; padding-bottom:10px;">
            Editar Conta #<?php echo $usuario['id']; ?>
        </h2>

        <form method="POST" action="editar_conta.php" style="display:flex; flex-direction:column; gap:15px; margin-top:20px;">
            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">

            <div>
                <label style="display:block; margin-bottom:5px; color:#94a3b8; font-weight:bold;">E-mail / Usuário</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required style="width:100%; padding:10px; border-radius:4px; border:1px solid #334155; background:#0f172a; color:#fff; box-sizing:border-box;">
            </div>

            <div>
                <label style="display:block; margin-bottom:5px; color:#94a3b8; font-weight:bold;">Telefone</label>
                <input type="text" name="telefone" value="<?php echo htmlspecialchars($usuario['telefone'] ?? ''); ?>" style="width:100%; padding:10px; border-radius:4px; border:1px solid #334155; background:#0f172a; color:#fff; box-sizing:border-box;">
            </div>

            <div>
                <label style="display:block; margin-bottom:5px; color:#94a3b8; font-weight:bold;">Endereço</label>
                <input type="text" name="endereco" value="<?php echo htmlspecialchars($usuario['endereco'] ?? ''); ?>" style="width:100%; padding:10px; border-radius:4px; border:1px solid #334155; background:#0f172a; color:#fff; box-sizing:border-box;">
            </div>

            <div>
                <label style="display:block; margin-bottom:5px; color:#94a3b8; font-weight:bold;">Nova Senha (deixe em branco para não alterar)</label>
                <input type="password" name="senha" style="width:100%; padding:10px; border-radius:4px; border:1px solid #334155; background:#0f172a; color:#fff; box-sizing:border-box;">
            </div>

            <div style="display:flex; gap:10px; margin-top:10px;">
                <button type="submit" style="background:#0284c7; color:#fff; border:none; padding:10px 18px; border-radius:4px; cursor:pointer; font-weight:bold; flex:1;">
                    Salvar Alterações
                </button>
                <a href="painel.php" style="background:#64748b; color:#fff; text-decoration:none; padding:10px 18px; border-radius:4px; font-weight:bold; text-align:center;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

</body>
</html>