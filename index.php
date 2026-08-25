<?php
session_start();

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO DA IMAGEM DO BANNER
|--------------------------------------------------------------------------
*/

$heroImage = '<banner-hero class=""></banner-hero>jpg';

/*
 * Verifica se a imagem realmente existe no servidor.
 * Isso ajuda a identificar rapidamente erro de caminho/nome.
 */
$heroImageExists = file_exists(__DIR__ . '/' . $heroImage);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AdaTech | Notebooks Dell, Desktops e Suporte em TI</title>

    <link rel="stylesheet" href="adatech.css">

    <!--
    ==========================================================
    CORREÇÃO DO BANNER
    ==========================================================
    Essas regras ficam aqui para garantir que a imagem apareça
    mesmo que exista algum problema no CSS original.
    ==========================================================
    -->

    <style>

        .hero {
            position: relative !important;
            overflow: hidden !important;
            min-height: 640px;
            isolation: isolate;
        }

        .hero-bg {
            position: absolute !important;
            inset: 0 !important;
            width: 100% !important;
            height: 100% !important;
            overflow: hidden !important;
            z-index: 0 !important;
        }

        .hero-bg-img {
            position: absolute !important;
            inset: 0 !important;

            display: block !important;

            width: 100% !important;
            height: 100% !important;

            min-width: 100% !important;
            min-height: 100% !important;

            object-fit: cover !important;
            object-position: center center !important;

            opacity: 1 !important;
            visibility: visible !important;

            z-index: 0 !important;
        }

        .hero-overlay {
            position: absolute !important;
            inset: 0 !important;

            width: 100% !important;
            height: 100% !important;

            z-index: 1 !important;

            /*
             * Deixa a imagem visível, mas mantém o texto legível.
             */
            background:
                linear-gradient(
                    90deg,
                    rgba(10, 18, 32, 0.88) 0%,
                    rgba(20, 30, 45, 0.62) 45%,
                    rgba(20, 30, 45, 0.30) 100%
                ) !important;
        }

        .hero-content {
            position: relative !important;
            z-index: 2 !important;
        }

        .hero-tag,
        .hero h1,
        .hero p,
        .hero-buttons {
            position: relative;
            z-index: 3;
        }

        /*
         * Caso a imagem não exista, mostra um fundo de segurança.
         */
        .hero-bg-fallback {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;

            background:
                linear-gradient(
                    135deg,
                    #111827,
                    #374151,
                    #94a3b8
                );

            z-index: -1;
        }

    </style>

</head>

<body>


    <!-- ==========================================================
         HEADER / MENU
    =========================================================== -->

    <header class="navbar">

        <div class="container nav-container">

            <a href="#" class="logo">
                Ada<span>Tech</span>
            </a>


            <nav class="main-nav">

                <a href="index.php">
                    Home
                </a>

                <a href="#produtos">
                    Produtos
                </a>

                <a href="#servicos">
                    Serviços
                </a>

                <a href="contato.php" class="nav-cta">
                    Fale Conosco
                </a>

            </nav>


            <div class="nav-actions">


                <!-- CARRINHO -->

                <div
                    class="cart-icon-container"
                    id="open-cart"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >

                        <circle
                            cx="9"
                            cy="21"
                            r="1"
                        ></circle>

                        <circle
                            cx="20"
                            cy="21"
                            r="1"
                        ></circle>

                        <path
                            d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"
                        ></path>

                    </svg>

                    <span
                        class="cart-count"
                        id="cart-count"
                    >
                        0
                    </span>

                </div>


                <!-- ==================================================
                     USUÁRIO / LOGIN / ADMIN
                =================================================== -->

                <?php if (isset($_SESSION['usuario_nome'])): ?>

                    <span class="user-greeting">

                        Olá,
                        <?php
                        echo htmlspecialchars(
                            $_SESSION['usuario_nome']
                        );
                        ?>

                    </span>


                    <?php if (
                        isset($_SESSION['usuario_nivel']) &&
                        $_SESSION['usuario_nivel'] === 'admin'
                    ): ?>

                        <a
                            href="painel.php"
                            class="btn-chip btn-chip-blue"
                        >
                            Painel Admin
                        </a>

                    <?php endif; ?>


                    <a
                        href="logout.php"
                        class="btn-chip btn-chip-red"
                    >
                        Sair
                    </a>


                <?php else: ?>

                    <a
                        href="login.php"
                        class="btn-chip btn-chip-ghost"
                    >
                        Login
                    </a>

                    <a
                        href="cadastro.php"
                        class="btn-chip btn-chip-blue"
                    >
                        Cadastrar
                    </a>

                <?php endif; ?>

            </div>

        </div>

    </header>



    <!-- ==========================================================
         MODAL DO CARRINHO
    =========================================================== -->

    <div
        class="cart-modal-overlay"
        id="cart-modal"
    >

        <div class="cart-modal">


            <div class="cart-modal-header">

                <h2>
                    Seu Carrinho
                </h2>

                <button
                    class="close-cart"
                    id="close-cart"
                >
                    &times;
                </button>

            </div>


            <div
                class="cart-items"
                id="cart-items"
            >
                <!-- Itens serão inseridos pelo JavaScript -->
            </div>


            <div class="cart-modal-footer">

                <div class="cart-total">

                    Total:

                    <span id="cart-total">
                        R$ 0,00
                    </span>

                </div>


                <button
                    id="checkout-cart"
                    class="btn-fechar-pedido"
                >
                    Fechar Pedido
                </button>

            </div>

        </div>

    </div>



    <!-- ==========================================================
         HERO / BANNER PRINCIPAL
    =========================================================== -->

    <section
        id="home"
        class="hero"
    >

        <div class="hero-bg">


            <?php if ($heroImageExists): ?>

                <!--
                ==================================================
                IMAGEM DO BANNER
                ==================================================
                -->

                <img
                    src="<?php echo htmlspecialchars($heroImage); ?>?v=<?php echo filemtime(__DIR__ . '/' . $heroImage); ?>"
                    alt="AdaTech - Tecnologia, notebooks, desktops e soluções em TI"
                    class="hero-bg-img"
                >

            <?php else: ?>

                <!--
                ==================================================
                FALLBACK
                A imagem não foi encontrada.
                ==================================================
                -->

                <div class="hero-bg-fallback"></div>

            <?php endif; ?>


            <div class="hero-overlay"></div>

        </div>



        <div class="container hero-content">


            <span class="hero-tag">
                Revenda Autorizada Dell
            </span>


            <h1>

                Tecnologia e Suporte de

                <span class="text-gradient">
                    Alta Performance
                </span>

            </h1>


            <p>

                Venda autorizada de notebooks Dell,
                desktops corporativos e soluções completas
                de infraestrutura e suporte em TI para o seu negócio.

            </p>


            <div class="hero-buttons">

                <a
                    href="#produtos"
                    class="btn-primary"
                >
                    Ver Catálogo
                </a>


                <a
                    href="#contato"
                    class="btn-outline"
                >
                    Solicitar Orçamento
                </a>

            </div>

        </div>

    </section>



    <!-- ==========================================================
         PRODUTOS
    =========================================================== -->

    <section
        id="produtos"
        class="section-padding"
    >

        <div class="container">


            <span class="eyebrow">
                Nossa linha
            </span>


            <h2 class="section-title">
                Nossos Produtos
            </h2>


            <p class="section-subtitle">

                Equipamentos originais Dell com garantia,
                prontos para elevar a produtividade da sua empresa.

            </p>


            <div class="grid-layout">


                <!-- ==================================================
                     PRODUTO 1
                =================================================== -->

                <div class="card product-card">

                    <div class="product-badge">
                        Dell
                    </div>


                    <h3>
                        Notebook Dell Inspiron 16
                    </h3>


                    <p>

                        Processador Intel Core i7,
                        8GB RAM, SSD 512GB.
                        Perfeito para produtividade diária e estudos.

                    </p>


                    <a
                        href="#contato"
                        class="btn-secondary btn-orcamento"
                        data-produto="Dell Inspiron 16"
                    >
                        Solicitar Orçamento
                    </a>

                </div>



                <!-- ==================================================
                     PRODUTO 2
                =================================================== -->

                <div class="card product-card">

                    <div class="product-badge">
                        Dell
                    </div>


                    <h3>
                        Dell Vostro Desktop
                    </h3>


                    <p>

                        Processador Intel Core i7,
                        16GB RAM, SSD 512GB.
                        Desempenho robusto e segurança para sua empresa.

                    </p>


                    <a
                        href="#contato"
                        class="btn-secondary btn-orcamento"
                        data-produto="Dell Vostro Desktop"
                    >
                        Solicitar Orçamento
                    </a>

                </div>



                <!-- ==================================================
                     PRODUTO 3
                =================================================== -->

                <div class="card product-card">

                    <div class="product-badge">
                        Dell
                    </div>


                    <h3>
                        Notebook Dell Latitude 3440
                    </h3>


                    <p>

                        Processador Intel Core i5,
                        16GB RAM, SSD 256GB.
                        Mobilidade e segurança avançada
                        para o ambiente corporativo.

                    </p>


                    <a
                        href="#contato"
                        class="btn-secondary btn-orcamento"
                        data-produto="Dell Latitude 3440"
                    >
                        Solicitar Orçamento
                    </a>

                </div>

            </div>

        </div>

    </section>



    <!-- ==========================================================
         SERVIÇOS
    =========================================================== -->

    <section
        id="servicos"
        class="section-bg section-padding"
    >

        <div class="container">


            <span class="eyebrow">
                O que fazemos
            </span>


            <h2 class="section-title">
                Serviços de Suporte em TI
            </h2>


            <p class="section-subtitle">

                Do hardware à rede, cuidamos de cada detalhe
                para sua operação nunca parar.

            </p>


            <div class="grid-layout">


                <!-- SERVIÇO 1 -->

                <div class="card service-card">

                    <div class="service-icon">
                        🛠️
                    </div>


                    <h3>
                        Manutenção de Hardware
                    </h3>


                    <p>

                        Diagnóstico preciso, substituição de
                        componentes danificados, limpeza interna
                        e upgrades de armazenamento (SSD)
                        e memória RAM.

                    </p>

                </div>



                <!-- SERVIÇO 2 -->

                <div class="card service-card">

                    <div class="service-icon">
                        💾
                    </div>


                    <h3>
                        Formatação e Otimização
                    </h3>


                    <p>

                        Instalação limpa de sistemas operacionais
                        (Windows/Linux), backup seguro de dados,
                        aplicação de drivers oficiais e remoção
                        de malwares.

                    </p>

                </div>



                <!-- SERVIÇO 3 -->

                <div class="card service-card">

                    <div class="service-icon">
                        🌐
                    </div>


                    <h3>
                        Suporte Técnico Local e Remoto
                    </h3>


                    <p>

                        Atendimento ágil para resolução de falhas
                        de conectividade, configuração de redes locais,
                        impressoras e suporte ao usuário final.

                    </p>

                </div>

            </div>

        </div>

    </section>



    <!-- ==========================================================
         CONTATO / ORÇAMENTO
    =========================================================== -->

    <section
        id="contato"
        class="section-padding"
    >

        <div class="container form-container">


            <span class="eyebrow">
                Vamos conversar
            </span>


            <h2 class="section-title">
                Solicite um Orçamento
            </h2>


            <p class="form-subtitle">

                Preencha os campos abaixo.
                Nossa equipe técnica retornará o contato
                o mais breve possível.

            </p>


            <form
                id="form-contato"
                class="card"
                action="salvar_orcamento.php"
                method="POST"
            >


                <!-- NOME -->

                <div class="form-group">

                    <label for="nome">
                        Nome Completo *
                    </label>


                    <input
                        type="text"
                        id="nome"
                        name="nome"
                        required
                        placeholder="Ex: João Silva"
                    >

                </div>



                <!-- EMAIL -->

                <div class="form-group">

                    <label for="email">
                        E-mail Corporativo ou Pessoal *
                    </label>


                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        placeholder="Ex: joao@empresa.com"
                    >

                </div>



                <!-- ASSUNTO -->

                <div class="form-group">

                    <label for="assunto">
                        Interesse Principal *
                    </label>


                    <select
                        id="assunto"
                        name="assunto"
                        required
                    >

                        <option value="">
                            Selecione uma opção...
                        </option>


                        <option value="Notebook Dell Inspiron 16">
                            Compra: Notebook Dell Inspiron 16
                        </option>


                        <option value="Dell Vostro Desktop">
                            Compra: Dell Vostro Desktop
                        </option>


                        <option value="Dell Latitude 3440">
                            Compra: Notebook Dell Latitude 3440
                        </option>


                        <option value="Manutenção de Hardware">
                            Serviço: Manutenção de Hardware
                        </option>


                        <option value="Formatação e Otimização">
                            Serviço: Formatação e Otimização
                        </option>


                        <option value="Suporte Técnico">
                            Serviço: Suporte Técnico Geral / Outros
                        </option>

                    </select>

                </div>



                <!-- MENSAGEM -->

                <div class="form-group">

                    <label for="mensagem">
                        Detalhes do Pedido / Mensagem
                    </label>


                    <textarea
                        id="mensagem"
                        name="mensagem"
                        rows="5"
                        placeholder="Descreva sua necessidade ou especificações adicionais..."
                    ></textarea>

                </div>



                <!-- BOTÃO -->

                <button
                    type="submit"
                    class="btn-primary btn-block"
                >
                    Enviar Solicitação
                </button>


            </form>


            <div
                id="form-feedback"
                class="feedback-msg hidden"
            >
            </div>

        </div>

    </section>



    <!-- ==========================================================
         RODAPÉ
    =========================================================== -->

    <footer class="footer">

        <div class="container footer-content">

            <p>

                &copy; 2026 AdaTech - Soluções em TI.
                Projeto Integrador | Técnico em Informática Senac.

            </p>

        </div>

    </footer>



    <!-- ==========================================================
         JAVASCRIPT
    =========================================================== -->

    <script src="adatech.js"></script>


</body>

</html>