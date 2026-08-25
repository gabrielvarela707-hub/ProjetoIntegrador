<?php
// Configurações de segurança do cookie de sessão (devem vir antes de session_start)
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja Virtual - Página Inicial</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <!-- Header / Navbar -->
    <header class="navbar">
        <div class="logo">
            <a href="index.php"><h1>MinhaLoja</h1></a>
        </div>
        
        <div class="search-bar">
            <form action="produtos.php" method="GET">
                <input type="text" name="busca" placeholder="Buscar produtos...">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        
        <nav class="nav-links">
            <a href="index.php">Início</a>
            <a href="produtos.php">Produtos</a>
            <a href="carrinho.php" class="cart-icon">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count">
                    <?php echo isset($_SESSION['carrinho']) ? count($_SESSION['carrinho']) : 0; ?>
                </span>
            </a>
            
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <span class="user-greeting">Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                <?php if (isset($_SESSION['usuario_nivel']) && $_SESSION['usuario_nivel'] === 'admin'): ?>
                    <a href="admin/dashboard.php">Painel Admin</a>
                <?php endif; ?>
                <a href="logout.php">Sair</a>
            <?php else: ?>
                <a href="login.php">Entrar</a>
                <a href="cadastro.php">Cadastrar</a>
            <?php endif; ?>
        </nav>
    </header>

    <!-- Banner Principal -->
    <section class="hero-banner">
        <div class="hero-content">
            <h2>As melhores ofertas da semana!</h2>
            <p>Confira nossos produtos em destaque com descontos imperdíveis.</p>
            <a href="produtos.php" class="btn-primary">Ver Ofertas</a>
        </div>
    </section>

    <!-- Conteúdo Principal / Destaques -->
    <main class="main-content">
        <h2>Produtos em Destaque</h2>
        <div class="product-grid">
            <!-- Os produtos serão carregados dinamicamente via PHP/Banco de dados aqui -->
            <p>Carregando ofertas...</p>
        </div>
    </main>

    <!-- Rodapé -->
    <footer class="footer">
        <p>&copy; <?php echo date('Y'); ?> MinhaLoja. Todos os direitos reservados.</p>
    </footer>

</body>
</html>