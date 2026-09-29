<?php
session_start();
include('conexao.php');

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Consultas ao Banco de Dados
$sql = "SELECT id, nome, email, assunto, mensagem, status, data_cadastro FROM orcamentos ORDER BY id DESC";
$result = $conn->query($sql);

$sql_contatos = "SELECT id, nome, email, telefone, mensagem, status, data_cadastro FROM contatos ORDER BY id DESC";
$result_contatos = $conn->query($sql_contatos);

$sql_usuarios = "SELECT id, nome, email, telefone, endereco, status, data_cadastro FROM usuarios ORDER BY id DESC";
$result_usuarios = $conn->query($sql_usuarios);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle - AdaTech</title>
    <link rel="stylesheet" href="adatech.css">
</head>
<body style="background-color: #0f172a; color: #ffffff; font-family: sans-serif; margin: 0; padding: 20px;">

    <div style="max-width: 1200px; margin: 0 auto; background: #1e293b; padding: 25px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.3);">
        
        <!-- CABEÇALHO -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #334155; padding-bottom: 15px; margin-bottom: 20px;">
            <h2 style="margin: 0; color: #38bdf8;">Painel de Controle - AdaTech</h2>
            <div>
                <a href="index.php" style="color: #94a3b8; text-decoration: none; margin-right: 15px; font-weight: bold;">Ver Site</a>
                <a href="logout.php" style="background: #ef4444; color: white; padding: 8px 14px; text-decoration: none; border-radius: 4px; font-weight: bold;">Sair</a>
            </div>
        </div>

        <!-- ORÇAMENTOS -->
        <h3 style="color: #38bdf8; margin-top: 20px;">Orçamentos Recebidos</h3>
        <table style="width: 100%; border-collapse: collapse; text-align: left; background: #0f172a; border-radius: 6px; overflow: hidden; margin-bottom: 40px;">
            <thead>
                <tr style="background: #0284c7; color: white;">
                    <th style="padding: 12px;">ID</th>
                    <th style="padding: 12px;">Data</th>
                    <th style="padding: 12px;">Nome</th>
                    <th style="padding: 12px;">E-mail</th>
                    <th style="padding: 12px;">Assunto</th>
                    <th style="padding: 12px;">Status</th>
                    <th style="padding: 12px; text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr id="registro-<?php echo $row['id']; ?>" style="border-bottom: 1px solid #334155;">
                            <td style="padding: 12px;"><?php echo $row['id']; ?></td>
                            <td style="padding: 12px;"><?php echo !empty($row['data_cadastro']) ? date('d/m/Y H:i', strtotime($row['data_cadastro'])) : '-'; ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($row['nome']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($row['assunto']); ?></td>
                            <td style="padding: 12px;">
                                <span style="background: #0369a1; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                    <?php echo htmlspecialchars($row['status'] ?? 'Pendente'); ?>
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <button onclick="deletar(<?php echo $row['id']; ?>)" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">Excluir</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="padding: 20px; text-align: center; color: #94a3b8;">Nenhum orçamento encontrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- CONTATOS -->
        <h3 style="color: #38bdf8; margin-top: 20px;">Contatos Cadastrados</h3>
        <table style="width: 100%; border-collapse: collapse; text-align: left; background: #0f172a; border-radius: 6px; overflow: hidden; margin-bottom: 40px;">
            <thead>
                <tr style="background: #0284c7; color: white;">
                    <th style="padding: 12px;">ID</th>
                    <th style="padding: 12px;">Data</th>
                    <th style="padding: 12px;">Nome</th>
                    <th style="padding: 12px;">E-mail</th>
                    <th style="padding: 12px;">Telefone</th>
                    <th style="padding: 12px;">Status</th>
                    <th style="padding: 12px; text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_contatos && $result_contatos->num_rows > 0): ?>
                    <?php while($c = $result_contatos->fetch_assoc()): ?>
                        <tr id="contato-<?php echo $c['id']; ?>" style="border-bottom: 1px solid #334155;">
                            <td style="padding: 12px;"><?php echo $c['id']; ?></td>
                            <td style="padding: 12px;"><?php echo !empty($c['data_cadastro']) ? date('d/m/Y H:i', strtotime($c['data_cadastro'])) : '-'; ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($c['nome']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($c['email']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($c['telefone'] ?? '-'); ?></td>
                            <td style="padding: 12px;">
                                <span style="background: #0369a1; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                    <?php echo htmlspecialchars($c['status'] ?? 'Pendente'); ?>
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <a href="editar_contato.php?id=<?php echo $c['id']; ?>" style="background: #f59e0b; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: bold; margin-right: 5px;">Editar</a>
                                <button onclick="deletarContato(<?php echo $c['id']; ?>)" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">Excluir</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="padding: 20px; text-align: center; color: #94a3b8;">Nenhum contato encontrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- CONTAS CADASTRADAS -->
        <h3 style="color: #38bdf8; margin-top: 20px;">Contas Cadastradas (Administradores)</h3>
        <table style="width: 100%; border-collapse: collapse; text-align: left; background: #0f172a; border-radius: 6px; overflow: hidden;">
            <thead>
                <tr style="background: #0284c7; color: white;">
                    <th style="padding: 12px;">ID</th>
                    <th style="padding: 12px;">Data Cadastro</th>
                    <th style="padding: 12px;">Nome</th>
                    <th style="padding: 12px;">E-mail / Usuário</th>
                    <th style="padding: 12px;">Telefone</th>
                    <th style="padding: 12px;">Endereço</th>
                    <th style="padding: 12px;">Status</th>
                    <th style="padding: 12px; text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_usuarios && $result_usuarios->num_rows > 0): ?>
                    <?php while($u = $result_usuarios->fetch_assoc()): ?>
                        <tr id="usuario-<?php echo $u['id']; ?>" style="border-bottom: 1px solid #334155;">
                            <td style="padding: 12px;"><?php echo $u['id']; ?></td>
                            <td style="padding: 12px;"><?php echo !empty($u['data_cadastro']) ? date('d/m/Y H:i', strtotime($u['data_cadastro'])) : '-'; ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($u['nome'] ?? '-'); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($u['email']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($u['telefone'] ?? '-'); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars($u['endereco'] ?? '-'); ?></td>
                            <td style="padding: 12px;">
                                <span style="background: #16a34a; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                    <?php echo htmlspecialchars($u['status'] ?? 'Ativo'); ?>
                                </span>
                            </td>
                            <td style="padding: 12px; text-align: center;">
                                <a href="editar_conta.php?id=<?php echo $u['id']; ?>" style="background: #f59e0b; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: bold; margin-right: 5px;">Editar</a>
                                <button onclick="deletarUsuario(<?php echo $u['id']; ?>)" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">Excluir</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="8" style="padding: 20px; text-align: center; color: #94a3b8;">Nenhuma conta encontrada.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>

    <!-- SCRIPTS DE EXCLUSÃO VIA AJAX -->
    <script>
    function deletar(id) {
        if (!confirm("Deseja deletar este orçamento?")) return;
        fetch('deletar.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: 'id=' + encodeURIComponent(id) })
        .then(r => r.text()).then(data => { if (data.trim() === 'sucesso') document.getElementById('registro-' + id).remove(); });
    }

    function deletarContato(id) {
        if (!confirm("Deseja deletar este contato?")) return;
        fetch('deletar_contato.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: 'id=' + encodeURIComponent(id) })
        .then(r => r.text()).then(data => { if (data.trim() === 'sucesso') document.getElementById('contato-' + id).remove(); });
    }

    function deletarUsuario(id) {
        if (!confirm("Tem certeza que deseja deletar esta conta?")) return;
        fetch('deletar_conta.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: 'id=' + encodeURIComponent(id) })
        .then(r => r.text()).then(data => {
            if (data.trim() === 'sucesso') {
                document.getElementById('usuario-' + id).remove();
            } else if (data.trim() === 'erro_propria_conta') {
                alert("Você não pode excluir a conta que está usando no momento!");
            } else {
                alert("Erro ao excluir conta.");
            }
        });
    }
    </script>
</body>
</html>