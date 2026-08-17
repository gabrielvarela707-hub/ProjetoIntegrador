<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fale Conosco - AdaTech</title>
    <link rel="stylesheet" href="adatech.css">
</head>
<body style="margin: 0; font-family: sans-serif; background-color: #f8fafc; color: #0f172a;">

<!-- MENU SUPERIOR -->
<header style="display: flex; justify-content: space-between; align-items: center; padding: 20px 40px; background: #ffffff; box-shadow: 0 2px 5px rgba(0,0,0,0.05); position: relative;">
    <!-- Logo à esquerda -->
    <a href="index.php" style="text-decoration: none;">
        <h2 style="margin: 0; color: #0f172a;">AdaTech</h2>
    </a>

    <!-- Menu Centralizado -->
    <nav style="display: flex; gap: 25px; align-items: center; position: absolute; left: 50%; transform: translateX(-50%);">
        <a href="index.php" style="text-decoration: none; color: #334155; font-weight: 500;">Home</a>
        <a href="index.php#produtos" style="text-decoration: none; color: #334155; font-weight: 500;">Produtos</a>
        <a href="index.php#servicos" style="text-decoration: none; color: #334155; font-weight: 500;">Serviços</a>
        <a href="contato.php" style="text-decoration: none; color: #0284c7; font-weight: bold;">Fale Conosco</a>
    </nav>

    <!-- Item da direita (Login / Painel com verificação de nível) -->
    <div style="display: flex; align-items: center; gap: 15px;">
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <span style="color: #334155; font-weight: 600;">Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); ?></span>
            
            <?php if (isset($_SESSION['usuario_nivel']) && $_SESSION['usuario_nivel'] === 'admin'): ?>
                <a href="painel.php" style="background: #0284c7; color: #fff; padding: 8px 14px; text-decoration: none; border-radius: 4px; font-weight: bold;">Painel Admin</a>
            <?php endif; ?>

            <a href="logout.php" style="background: #ef4444; color: #fff; padding: 8px 14px; text-decoration: none; border-radius: 4px; font-weight: bold;">Sair</a>
        <?php else: ?>
            <a href="login.php" style="text-decoration: none; color: #334155; font-weight: 500;">Login</a>
        <?php endif; ?>
    </div>
</header>

    <!-- FORMULÁRIO DE CONTATO -->
    <main style="display: flex; justify-content: center; align-items: center; padding: 60px 20px;">
        <div style="background: #ffffff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); width: 100%; max-width: 500px;">
            <h2 style="color: #0284c7; text-align: center; margin-top: 0; margin-bottom: 25px;">Fale Conosco</h2>
            
            <form method="POST" action="salvar_contato.php" style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; color: #334155; font-weight: bold;">Nome *</label>
                    <input type="text" name="nome" required style="width: 100%; padding: 12px; border-radius: 4px; border: 1px solid #cbd5e1; color: #0f172a; background: #fff; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 5px; color: #334155; font-weight: bold;">E-mail *</label>
                    <input type="email" name="email" required style="width: 100%; padding: 12px; border-radius: 4px; border: 1px solid #cbd5e1; color: #0f172a; background: #fff; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 5px; color: #334155; font-weight: bold;">Telefone / WhatsApp</label>
                    <input type="text" name="telefone" style="width: 100%; padding: 12px; border-radius: 4px; border: 1px solid #cbd5e1; color: #0f172a; background: #fff; box-sizing: border-box;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 5px; color: #334155; font-weight: bold;">Mensagem</label>
                    <textarea name="mensagem" style="width: 100%; padding: 12px; border-radius: 4px; border: 1px solid #cbd5e1; color: #0f172a; background: #fff; height: 100px; box-sizing: border-box;"></textarea>
                </div>

                <button type="submit" style="background: #0284c7; color: #fff; padding: 14px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; margin-top: 10px;">
                    Enviar Mensagem
                </button>
            </form>
        </div>
    </main>

</body>
</html>