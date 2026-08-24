<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdaTech | Notebooks Dell, Desktops e Suporte em TI</title>
    <link rel="stylesheet" href="adatech.css">
</head>
<body>
 
    <header class="navbar">
        <div class="container nav-container">
            <a href="#" class="logo">Ada<span>Tech</span></a>
            <nav class="main-nav">
                <a href="index.php">Home</a>
                <a href="#produtos">Produtos</a>
                <a href="#servicos">Serviços</a>
                <a href="contato.php" class="nav-cta">Fale Conosco</a>
            </nav>
            <div class="nav-actions">
                <!-- Ícone do Carrinho -->
                <div class="cart-icon-container" id="open-cart">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    <span class="cart-count" id="cart-count">0</span>
                </div>
 
                <!-- Bloco do Usuário e Botão do Admin -->
                <?php if(isset($_SESSION['usuario_nome'])): ?>
                    <span class="user-greeting">Olá, <?php echo $_SESSION['usuario_nome']; ?></span>
 
                    <?php if(isset($_SESSION['usuario_nivel']) && $_SESSION['usuario_nivel'] === 'admin'): ?>
                        <a href="painel.php" class="btn-chip btn-chip-blue">Painel Admin</a>
                    <?php endif; ?>
 
                    <a href="logout.php" class="btn-chip btn-chip-red">Sair</a>
                <?php else: ?>
                    <a href="login.php" class="btn-chip btn-chip-ghost">Login</a>
                    <a href="cadastro.php" class="btn-chip btn-chip-blue">Cadastrar</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
 
    <!-- Modal do Carrinho -->
    <div class="cart-modal-overlay" id="cart-modal">
        <div class="cart-modal">
            <div class="cart-modal-header">
                <h2>Seu Carrinho</h2>
                <button class="close-cart" id="close-cart">&times;</button>
            </div>
            <div class="cart-items" id="cart-items">
                <!-- Itens injetados via JavaScript -->
            </div>
            <div class="cart-modal-footer">
                <div class="cart-total">Total: <span id="cart-total">R$ 0,00</span></div>
 
                <!-- Botão de Fechar Pedido integrado ao adatech.js -->
                <button id="checkout-cart" class="btn-fechar-pedido">
                    Fechar Pedido
                </button>
            </div>
        </div>
    </div>
 
    <section id="home" class="hero">
        <div class="hero-bg">
            <img src="img/banner-hero.jpg" alt="AdaTech - Notebooks, Desktops e Infraestrutura de TI" class="hero-bg-img">
            <div class="hero-overlay"></div>
        </div>
        <div class="container hero-content">
            <span class="hero-tag">Revenda Autorizada Dell</span>
            <h1>Tecnologia e Suporte de <span class="text-gradient">Alta Performance</span></h1>
            <p>Venda autorizada de notebooks Dell, desktops corporativos e soluções completas de infraestrutura e suporte em TI para o seu negócio.</p>
            <div class="hero-buttons">
                <a href="#produtos" class="btn-primary">Ver Catálogo</a>
                <a href="#contato" class="btn-outline">Solicitar Orçamento</a>
            </div>
        </div>
    </section>
 
    <section id="produtos" class="section-padding">
        <div class="container">
            <span class="eyebrow">Nossa linha</span>
            <h2 class="section-title">Nossos Produtos</h2>
            <p class="section-subtitle">Equipamentos originais Dell com garantia, prontos para elevar a produtividade da sua empresa.</p>
            <div class="grid-layout">
                <div class="card product-card">
                    <div class="product-badge">Dell</div>
                    <h3>Notebook Dell Inspiron 16</h3>
                    <p>Processador Intel Core i7, 8GB RAM, SSD 512GB. Perfeito para produtividade diária e estudos.</p>
                    <a href="#contato" class="btn-secondary btn-orcamento" data-produto="Dell Inspiron 15">Solicitar Orçamento</a>
                </div>
                <div class="card product-card">
                    <div class="product-badge">Dell</div>
                    <h3>Dell Vostro Desktop</h3>
                    <p>Processador Intel Core i7, 16GB RAM, SSD 512GB. Desempenho robusto e segurança para sua empresa.</p>
                    <a href="#contato" class="btn-secondary btn-orcamento" data-produto="Dell Vostro Desktop">Solicitar Orçamento</a>
                </div>
                <div class="card product-card">
                    <div class="product-badge">Dell</div>
                    <h3>Notebook Dell Latitude 3440</h3>
                    <p>Processador Intel Core i5, 16GB RAM, SSD 256GB. Mobilidade e segurança avançada para o ambiente corporativo.</p>
                    <a href="#contato" class="btn-secondary btn-orcamento" data-produto="Dell Latitude 3440">Solicitar Orçamento</a>
                </div>
            </div>
        </div>
    </section>
 
    <section id="servicos" class="section-bg section-padding">
        <div class="container">
            <span class="eyebrow">O que fazemos</span>
            <h2 class="section-title">Serviços de Suporte em TI</h2>
            <p class="section-subtitle">Do hardware à rede, cuidamos de cada detalhe para sua operação nunca parar.</p>
            <div class="grid-layout">
                <div class="card service-card">
                    <div class="service-icon">🛠️</div>
                    <h3>Manutenção de Hardware</h3>
                    <p>Diagnóstico preciso, substituição de componentes danificados, limpeza interna e upgrades de armazenamento (SSD) e memória RAM.</p>
                </div>
                <div class="card service-card">
                    <div class="service-icon">💾</div>
                    <h3>Formatação e Otimização</h3>
                    <p>Instalação limpa de sistemas operacionais (Windows/Linux), backup seguro de dados, aplicação de drivers oficiais e remoção de malwares.</p>
                </div>
                <div class="card service-card">
                    <div class="service-icon">🌐</div>
                    <h3>Suporte Técnico Local e Remoto</h3>
                    <p>Atendimento ágil para resolução de falhas de conectividade, configuração de redes locais, impressoras e suporte ao usuário final.</p>
                </div>
            </div>
        </div>
    </section>
 
    <section id="contato" class="section-padding">
        <div class="container form-container">
            <span class="eyebrow">Vamos conversar</span>
            <h2 class="section-title">Solicite um Orçamento</h2>
            <p class="form-subtitle">Preencha os campos abaixo. Nossa equipe técnica retornará o contato o mais breve possível.</p>
 
            <form id="form-contato" class="card" action="salvar_orcamento.php" method="POST">
                <div class="form-group">
                    <label for="nome">Nome Completo *</label>
                    <input type="text" id="nome" name="nome" required placeholder="Ex: João Silva">
                </div>
                <div class="form-group">
                    <label for="email">E-mail Corporativo ou Pessoal *</label>
                    <input type="email" id="email" name="email" required placeholder="Ex: joao@empresa.com">
                </div>
                <div class="form-group">
                    <label for="assunto">Interesse Principal *</label>
                    <select id="assunto" name="assunto" required>
                        <option value="">Selecione uma opção...</option>
                        <option value="Notebook Dell Inspiron 15">Compra: Notebook Dell Inspiron 15</option>
                        <option value="Dell Vostro Desktop">Compra: Dell Vostro Desktop</option>
                        <option value="Dell Latitude 3440">Compra: Notebook Dell Latitude 3440</option>
                        <option value="Manutenção de Hardware">Serviço: Manutenção de Hardware</option>
                        <option value="Formatação e Otimização">Serviço: Formatação e Otimização</option>
                        <option value="Suporte Técnico">Serviço: Suporte Técnico Geral / Outros</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="mensagem">Detalhes do Pedido / Mensagem</label>
                    <textarea id="mensagem" name="mensagem" rows="5" placeholder="Descreva sua necessidade ou especificações adicionais..."></textarea>
                </div>
                <button type="submit" class="btn-primary btn-block">Enviar Solicitação</button>
            </form>
            <div id="form-feedback" class="feedback-msg hidden"></div>
        </div>
    </section>
 
    <footer class="footer">
        <div class="container footer-content">
            <p>&copy; 2026 AdaTech - Soluções em TI. Projeto Integrador | Técnico em Informática Senac.</p>
        </div>
    </footer>
 
    <script src="adatech.js"></script>
</body>
</html>