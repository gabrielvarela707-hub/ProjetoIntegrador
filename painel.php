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

// Consulta para as Avaliações
$sql_avaliacoes = "SELECT id, nome, nota, comentario, data_criacao FROM avaliacoes ORDER BY id DESC";
$result_avaliacoes = $conn->query($sql_avaliacoes);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - AdaTech</title>
    <link rel="stylesheet" href="adatech.css">
    <style>
        body {
            background-color: #0f172a;
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        .crm-wrapper {
            display: flex;
            min-height: 100vh;
        }
        /* Sidebar Esquerda */
        .crm-sidebar {
            width: 260px;
            background-color: #1e293b;
            border-right: 1px solid #334155;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
        }
        .crm-brand {
            padding: 24px;
            font-size: 22px;
            font-weight: bold;
            color: #38bdf8;
            border-bottom: 1px solid #334155;
            text-decoration: none;
        }
        .crm-brand span { color: #f8fafc; }
        .crm-nav {
            list-style: none;
            padding: 0;
            margin: 0;
            flex: 1;
        }
        .crm-nav li a {
            display: block;
            padding: 16px 24px;
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 4px solid transparent;
            font-weight: 600;
            cursor: pointer;
        }
        .crm-nav li a:hover, .crm-nav li a.active {
            background-color: #334155;
            color: #f8fafc;
            border-left-color: #38bdf8;
        }
        .crm-sidebar-footer {
            padding: 20px;
            border-top: 1px solid #334155;
        }
        /* Conteúdo Principal */
        .crm-main {
            margin-left: 260px;
            flex: 1;
            padding: 40px;
            background-color: #0f172a;
        }
        .crm-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #334155;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .section-card {
            background: #1e293b;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            margin-bottom: 40px;
            display: none; /* Oculto por padrão */
        }
        .section-card.active {
            display: block; /* Mostra apenas a ativa */
        }
        .crm-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            background: #0f172a;
            border-radius: 6px;
            overflow: hidden;
        }
        .crm-table th {
            background: #0284c7;
            color: white;
            padding: 12px;
        }
        .crm-table td {
            padding: 12px;
            border-bottom: 1px solid #334155;
            font-size: 14px;
        }
        .crm-table tr:hover {
            background-color: #273548;
        }
    </style>
</head>
<body>

    <div class="crm-wrapper">
        <!-- MENU LATERAL (SIDEBAR) -->
        <aside class="crm-sidebar">
            <a href="painel.php" class="crm-brand">Ada<span>Tech</span></a>
            <ul class="crm-nav">
                <li><a onclick="mudarSecao('orcamentos', this)" class="active" id="nav-orcamentos">Orçamentos Recebidos</a></li>
                <li><a onclick="mudarSecao('contatos', this)" id="nav-contatos">Contatos Cadastrados</a></li>
                <li><a onclick="mudarSecao('avaliacoes', this)" id="nav-avaliacoes">Avaliações Recebidas</a></li>
                <li><a onclick="mudarSecao('usuarios', this)" id="nav-usuarios">Contas (Administradores)</a></li>
            </ul>
            <div class="crm-sidebar-footer">
                <a href="index.php" style="color: #94a3b8; text-decoration: none; display: block; margin-bottom: 12px; font-weight: bold;">&larr; Ver Site</a>
                <a href="logout.php" style="display: block; text-align: center; background: #ef4444; color: white; padding: 10px; text-decoration: none; border-radius: 6px; font-weight: bold;">Sair do Sistema</a>
            </div>
        </aside>

        <!-- CONTEÚDO PRINCIPAL -->
        <main class="crm-main">
            <!-- CABEÇALHO -->
            <div class="crm-header">
                <h2 style="margin: 0; color: #38bdf8;">Painel de Controle /h2>
                <span style="color: #94a3b8; font-weight: bold;">Sessão Ativa</span>
            </div>

            <!-- ORÇAMENTOS -->
            <div id="orcamentos" class="section-card active">
                <h3 style="color: #38bdf8; margin-top: 0; margin-bottom: 20px;">Orçamentos Recebidos</h3>
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Assunto</th>
                            <th>Status</th>
                            <th style="text-align: center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr id="registro-<?php echo $row['id']; ?>">
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo !empty($row['data_cadastro']) ? date('d/m/Y H:i', strtotime($row['data_cadastro'])) : '-'; ?></td>
                                    <td><?php echo htmlspecialchars($row['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['assunto']); ?></td>
                                    <td>
                                        <span style="background: #0369a1; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                            <?php echo htmlspecialchars($row['status'] ?? 'Pendente'); ?>
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <button onclick="deletar(<?php echo $row['id']; ?>)" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">Excluir</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="7" style="text-align: center; color: #94a3b8; padding: 20px;">Nenhum orçamento encontrado.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- CONTATOS -->
            <div id="contatos" class="section-card">
                <h3 style="color: #38bdf8; margin-top: 0; margin-bottom: 20px;">Contatos Cadastrados</h3>
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Status</th>
                            <th style="text-align: center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_contatos && $result_contatos->num_rows > 0): ?>
                            <?php while($c = $result_contatos->fetch_assoc()): ?>
                                <tr id="contato-<?php echo $c['id']; ?>">
                                    <td><?php echo $c['id']; ?></td>
                                    <td><?php echo !empty($c['data_cadastro']) ? date('d/m/Y H:i', strtotime($c['data_cadastro'])) : '-'; ?></td>
                                    <td><?php echo htmlspecialchars($c['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($c['email']); ?></td>
                                    <td><?php echo htmlspecialchars($c['telefone'] ?? '-'); ?></td>
                                    <td>
                                        <span style="background: #0369a1; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                            <?php echo htmlspecialchars($c['status'] ?? 'Pendente'); ?>
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="editar_contato.php?id=<?php echo $c['id']; ?>" style="background: #f59e0b; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: bold; margin-right: 5px;">Editar</a>
                                        <button onclick="deletarContato(<?php echo $c['id']; ?>)" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">Excluir</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="7" style="text-align: center; color: #94a3b8; padding: 20px;">Nenhum contato encontrado.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- AVALIAÇÕES RECEBIDAS -->
            <div id="avaliacoes" class="section-card">
                <h3 style="color: #38bdf8; margin-top: 0; margin-bottom: 20px;">Avaliações Recebidas</h3>
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Nome</th>
                            <th>Nota</th>
                            <th>Comentário</th>
                            <th style="text-align: center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_avaliacoes && $result_avaliacoes->num_rows > 0): ?>
                            <?php while($av = $result_avaliacoes->fetch_assoc()): ?>
                                <tr id="avaliacao-<?php echo $av['id']; ?>">
                                    <td><?php echo $av['id']; ?></td>
                                    <td><?php echo !empty($av['data_criacao']) ? date('d/m/Y H:i', strtotime($av['data_criacao'])) : '-'; ?></td>
                                    <td><?php echo htmlspecialchars($av['nome']); ?></td>
                                    <td>⭐ <?php echo $av['nota']; ?> / 5</td>
                                    <td><?php echo htmlspecialchars($av['comentario']); ?></td>
                                    <td style="text-align: center;">
                                        <button onclick="deletarAvaliacao(<?php echo $av['id']; ?>)" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">Excluir</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align: center; color: #94a3b8; padding: 20px;">Nenhuma avaliação encontrada.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- CONTAS CADASTRADAS -->
            <div id="usuarios" class="section-card">
                <h3 style="color: #38bdf8; margin-top: 0; margin-bottom: 20px;">Contas Cadastradas (Administradores)</h3>
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data Cadastro</th>
                            <th>Nome</th>
                            <th>E-mail / Usuário</th>
                            <th>Telefone</th>
                            <th>Endereço</th>
                            <th>Status</th>
                            <th style="text-align: center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_usuarios && $result_usuarios->num_rows > 0): ?>
                            <?php while($u = $result_usuarios->fetch_assoc()): ?>
                                <tr id="usuario-<?php echo $u['id']; ?>">
                                    <td><?php echo $u['id']; ?></td>
                                    <td><?php echo !empty($u['data_cadastro']) ? date('d/m/Y H:i', strtotime($u['data_cadastro'])) : '-'; ?></td>
                                    <td><?php echo htmlspecialchars($u['nome'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                                    <td><?php echo htmlspecialchars($u['telefone'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($u['endereco'] ?? '-'); ?></td>
                                    <td>
                                        <span style="background: #16a34a; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                            <?php echo htmlspecialchars($u['status'] ?? 'Ativo'); ?>
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="editar_conta.php?id=<?php echo $u['id']; ?>" style="background: #f59e0b; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: bold; margin-right: 5px;">Editar</a>
                                        <button onclick="deletarUsuario(<?php echo $u['id']; ?>)" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold;">Excluir</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="8" style="text-align: center; color: #94a3b8; padding: 20px;">Nenhuma conta encontrada.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- SCRIPTS DE NAVEGAÇÃO E EXCLUSÃO VIA AJAX -->
    <script>
    function mudarSecao(secaoId, elemento) {
        // Ocultar todos os cards
        const cards = document.querySelectorAll('.section-card');
        cards.forEach(card => card.classList.remove('active'));

        // Mostrar apenas o selecionado
        document.getElementById(secaoId).classList.add('active');

        // Atualizar classe ativa no menu lateral
        const links = document.querySelectorAll('.crm-nav a');
        links.forEach(link => link.classList.remove('active'));
        elemento.classList.add('active');
    }

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

    function deletarAvaliacao(id) {
        if (!confirm("Deseja deletar esta avaliação?")) return;
        fetch('deletar_avaliacao.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: 'id=' + encodeURIComponent(id) })
        .then(r => r.text()).then(data => { if (data.trim() === 'sucesso') document.getElementById('avaliacao-' + id).remove(); });
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