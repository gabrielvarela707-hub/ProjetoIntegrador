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

// ---------- Apenas apresentação: organiza os dados para a tela ----------
$orcamentos = [];
if ($result) { while ($r = $result->fetch_assoc()) { $orcamentos[] = $r; } }

$contatos = [];
if ($result_contatos) { while ($r = $result_contatos->fetch_assoc()) { $contatos[] = $r; } }

$usuarios = [];
if ($result_usuarios) { while ($r = $result_usuarios->fetch_assoc()) { $usuarios[] = $r; } }

$orcPendentes = 0;
foreach ($orcamentos as $o) {
    if (stripos($o['status'] ?? 'Pendente', 'pend') !== false) { $orcPendentes++; }
}

function fmtData($d) {
    return !empty($d) ? date('d/m/Y H:i', strtotime($d)) : '-';
}

function classeStatus($s) {
    $s = mb_strtolower(trim((string) $s), 'UTF-8');
    if ($s === '' || strpos($s, 'pend') !== false) return 'pn-badge-pend';
    if (strpos($s, 'ativo') !== false && strpos($s, 'inativo') === false) return 'pn-badge-ok';
    if (strpos($s, 'conclu') !== false || strpos($s, 'resolv') !== false || strpos($s, 'respond') !== false) return 'pn-badge-ok';
    if (strpos($s, 'andamento') !== false || strpos($s, 'analis') !== false) return 'pn-badge-warn';
    return 'pn-badge-off';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle - AdaTech</title>
    <link rel="stylesheet" href="painel.css">
</head>
<body>

    <!-- ================= NAVBAR ================= -->
    <header class="pn-navbar">
        <div class="pn-container pn-nav">
            <a href="painel.php" class="pn-brand" aria-label="AdaTech - Painel">
                <span class="pn-logo">Ada<span>Tech</span></span>
                <span class="pn-tag">Painel</span>
            </a>

            <nav class="pn-nav-links" aria-label="Seções do painel">
                <a class="pn-link" href="#orcamentos">Orçamentos</a>
                <a class="pn-link" href="#contatos">Contatos</a>
                <a class="pn-link" href="#contas">Contas</a>
            </nav>

            <div class="pn-actions">
                <a href="index.php" class="pn-chip pn-chip-ghost">Ver Site</a>
                <a href="logout.php" class="pn-chip pn-chip-solid">Sair</a>
            </div>
        </div>
    </header>

    <!-- ================= HERO ================= -->
    <section class="pn-hero">
        <div class="pn-container pn-hero-inner">
            <span class="pn-eyebrow">Área administrativa</span>
            <h1>Painel de <span class="pn-gradient">Controle</span></h1>
            <p>Acompanhe orçamentos, contatos e contas da AdaTech em um só lugar.</p>

            <div class="pn-quick">
                <a href="#orcamentos">Orçamentos Recebidos</a>
                <a href="#contatos">Contatos</a>
                <a href="#contas">Administradores</a>
            </div>
        </div>
    </section>

    <main class="pn-container">

        <!-- ================= RESUMO ================= -->
        <div class="pn-stats">
            <div class="pn-stat">
                <div class="pn-stat-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="14" y2="17"/></svg>
                </div>
                <div>
                    <div class="pn-stat-num"><?php echo count($orcamentos); ?></div>
                    <div class="pn-stat-label">Orçamentos recebidos</div>
                    <div class="pn-stat-sub"><?php echo (int) $orcPendentes; ?> pendente(s)</div>
                </div>
            </div>

            <div class="pn-stat">
                <div class="pn-stat-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <div>
                    <div class="pn-stat-num"><?php echo count($contatos); ?></div>
                    <div class="pn-stat-label">Contatos cadastrados</div>
                </div>
            </div>

            <div class="pn-stat">
                <div class="pn-stat-ico">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div>
                    <div class="pn-stat-num"><?php echo count($usuarios); ?></div>
                    <div class="pn-stat-label">Contas de administradores</div>
                </div>
            </div>
        </div>

        <div class="pn-main">

            <!-- ================= ORÇAMENTOS ================= -->
            <section class="pn-card" id="orcamentos">
                <div class="pn-card-head">
                    <div class="pn-title">
                        <h2>Orçamentos Recebidos</h2>
                        <span class="pn-count" id="count-orcamentos"><?php echo count($orcamentos); ?></span>
                    </div>
                    <div class="pn-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="search" placeholder="Buscar orçamento..." data-filtro="tabela-orcamentos" aria-label="Buscar orçamentos">
                    </div>
                </div>

                <div class="pn-table-wrap">
                    <table class="pn-table" id="tabela-orcamentos">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Data</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Assunto</th>
                                <th>Status</th>
                                <th class="pn-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($orcamentos) > 0): ?>
                                <?php foreach ($orcamentos as $row): ?>
                                    <tr id="registro-<?php echo $row['id']; ?>">
                                        <td class="pn-id"><?php echo $row['id']; ?></td>
                                        <td class="pn-date"><?php echo fmtData($row['data_cadastro']); ?></td>
                                        <td class="pn-strong"><?php echo htmlspecialchars($row['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['assunto']); ?></td>
                                        <td>
                                            <span class="pn-badge <?php echo classeStatus($row['status'] ?? 'Pendente'); ?>">
                                                <?php echo htmlspecialchars($row['status'] ?? 'Pendente'); ?>
                                            </span>
                                        </td>
                                        <td class="pn-center">
                                            <div class="pn-btns">
                                                <button class="pn-btn pn-btn-del" onclick="deletar(<?php echo $row['id']; ?>)">Excluir</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="pn-empty"><td colspan="7">Nenhum orçamento encontrado.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- ================= CONTATOS ================= -->
            <section class="pn-card" id="contatos">
                <div class="pn-card-head">
                    <div class="pn-title">
                        <h2>Contatos Cadastrados</h2>
                        <span class="pn-count" id="count-contatos"><?php echo count($contatos); ?></span>
                    </div>
                    <div class="pn-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="search" placeholder="Buscar contato..." data-filtro="tabela-contatos" aria-label="Buscar contatos">
                    </div>
                </div>

                <div class="pn-table-wrap">
                    <table class="pn-table" id="tabela-contatos">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Data</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>Status</th>
                                <th class="pn-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($contatos) > 0): ?>
                                <?php foreach ($contatos as $c): ?>
                                    <tr id="contato-<?php echo $c['id']; ?>">
                                        <td class="pn-id"><?php echo $c['id']; ?></td>
                                        <td class="pn-date"><?php echo fmtData($c['data_cadastro']); ?></td>
                                        <td class="pn-strong"><?php echo htmlspecialchars($c['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($c['email']); ?></td>
                                        <td class="pn-nowrap"><?php echo htmlspecialchars($c['telefone'] ?? '-'); ?></td>
                                        <td>
                                            <span class="pn-badge <?php echo classeStatus($c['status'] ?? 'Pendente'); ?>">
                                                <?php echo htmlspecialchars($c['status'] ?? 'Pendente'); ?>
                                            </span>
                                        </td>
                                        <td class="pn-center">
                                            <div class="pn-btns">
                                                <a href="editar_contato.php?id=<?php echo $c['id']; ?>" class="pn-btn pn-btn-edit">Editar</a>
                                                <button class="pn-btn pn-btn-del" onclick="deletarContato(<?php echo $c['id']; ?>)">Excluir</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="pn-empty"><td colspan="7">Nenhum contato encontrado.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- ================= CONTAS CADASTRADAS ================= -->
            <section class="pn-card" id="contas">
                <div class="pn-card-head">
                    <div class="pn-title">
                        <h2>Contas Cadastradas (Administradores)</h2>
                        <span class="pn-count" id="count-contas"><?php echo count($usuarios); ?></span>
                    </div>
                    <div class="pn-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="search" placeholder="Buscar conta..." data-filtro="tabela-contas" aria-label="Buscar contas">
                    </div>
                </div>

                <div class="pn-table-wrap">
                    <table class="pn-table" id="tabela-contas">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Data Cadastro</th>
                                <th>Nome</th>
                                <th>E-mail / Usuário</th>
                                <th>Telefone</th>
                                <th>Endereço</th>
                                <th>Status</th>
                                <th class="pn-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($usuarios) > 0): ?>
                                <?php foreach ($usuarios as $u): ?>
                                    <tr id="usuario-<?php echo $u['id']; ?>">
                                        <td class="pn-id"><?php echo $u['id']; ?></td>
                                        <td class="pn-date"><?php echo fmtData($u['data_cadastro']); ?></td>
                                        <td class="pn-strong"><?php echo htmlspecialchars($u['nome'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                                        <td class="pn-nowrap"><?php echo htmlspecialchars($u['telefone'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($u['endereco'] ?? '-'); ?></td>
                                        <td>
                                            <span class="pn-badge <?php echo classeStatus($u['status'] ?? 'Ativo'); ?>">
                                                <?php echo htmlspecialchars($u['status'] ?? 'Ativo'); ?>
                                            </span>
                                        </td>
                                        <td class="pn-center">
                                            <div class="pn-btns">
                                                <a href="editar_conta.php?id=<?php echo $u['id']; ?>" class="pn-btn pn-btn-edit">Editar</a>
                                                <button class="pn-btn pn-btn-del" onclick="deletarUsuario(<?php echo $u['id']; ?>)">Excluir</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="pn-empty"><td colspan="8">Nenhuma conta encontrada.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>

        <p class="pn-footer">© <?php echo date('Y'); ?> AdaTech — Tecnologia e Suporte de Alta Performance</p>
    </main>

    <div class="pn-toasts" id="pnToasts" aria-live="polite"></div>

    <!-- SCRIPTS DE EXCLUSÃO VIA AJAX (mesmos endpoints de antes) -->
    <script>
    function avisar(msg, erro) {
        var box = document.getElementById('pnToasts');
        var t = document.createElement('div');
        t.className = 'pn-toast' + (erro ? ' err' : '');
        t.textContent = msg;
        box.appendChild(t);
        setTimeout(function () { t.classList.add('out'); setTimeout(function () { t.remove(); }, 400); }, 3200);
    }

    function removerLinha(idLinha, idContador) {
        var tr = document.getElementById(idLinha);
        if (!tr) return;
        tr.classList.add('pn-removing');
        setTimeout(function () {
            tr.remove();
            var c = document.getElementById(idContador);
            if (c) c.textContent = Math.max(0, parseInt(c.textContent, 10) - 1);
        }, 350);
    }

    function deletar(id) {
        if (!confirm("Deseja deletar este orçamento?")) return;
        fetch('deletar.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: 'id=' + encodeURIComponent(id) })
        .then(r => r.text()).then(data => {
            if (data.trim() === 'sucesso') { removerLinha('registro-' + id, 'count-orcamentos'); avisar('Orçamento excluído com sucesso.'); }
            else { avisar('Erro ao excluir orçamento.', true); }
        });
    }

    function deletarContato(id) {
        if (!confirm("Deseja deletar este contato?")) return;
        fetch('deletar_contato.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: 'id=' + encodeURIComponent(id) })
        .then(r => r.text()).then(data => {
            if (data.trim() === 'sucesso') { removerLinha('contato-' + id, 'count-contatos'); avisar('Contato excluído com sucesso.'); }
            else { avisar('Erro ao excluir contato.', true); }
        });
    }

    function deletarUsuario(id) {
        if (!confirm("Tem certeza que deseja deletar esta conta?")) return;
        fetch('deletar_conta.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: 'id=' + encodeURIComponent(id) })
        .then(r => r.text()).then(data => {
            if (data.trim() === 'sucesso') {
                removerLinha('usuario-' + id, 'count-contas');
                avisar('Conta excluída com sucesso.');
            } else if (data.trim() === 'erro_propria_conta') {
                alert("Você não pode excluir a conta que está usando no momento!");
            } else {
                alert("Erro ao excluir conta.");
            }
        });
    }

    // Busca rápida dentro de cada tabela (apenas visual, não altera dados)
    document.querySelectorAll('input[data-filtro]').forEach(function (campo) {
        campo.addEventListener('input', function () {
            var termo = campo.value.toLowerCase().trim();
            var tabela = document.getElementById(campo.getAttribute('data-filtro'));
            var linhas = tabela.querySelectorAll('tbody tr:not(.pn-empty):not(.pn-semresultado)');
            var visiveis = 0;
            linhas.forEach(function (tr) {
                var ok = tr.textContent.toLowerCase().indexOf(termo) !== -1;
                tr.style.display = ok ? '' : 'none';
                if (ok) visiveis++;
            });

            var aviso = tabela.querySelector('tbody tr.pn-semresultado');
            if (linhas.length > 0 && visiveis === 0) {
                if (!aviso) {
                    aviso = document.createElement('tr');
                    aviso.className = 'pn-empty pn-semresultado';
                    var td = document.createElement('td');
                    td.colSpan = tabela.querySelectorAll('thead th').length;
                    td.textContent = 'Nenhum resultado para a busca.';
                    aviso.appendChild(td);
                    tabela.querySelector('tbody').appendChild(aviso);
                }
            } else if (aviso) {
                aviso.remove();
            }
        });
    });
    </script>
</body>
</html>
