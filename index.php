<?php
session_start();

/*
|--------------------------------------------------------------------------
| CONFIGURAÇÃO DA IMAGEM DO BANNER
|--------------------------------------------------------------------------
*/

$heroImage = 'banner-hero.jpg';

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

                <a href="#clientes">
                    Clientes
                </a>

                <a href="#servicos">
                    Serviços
                </a>

                <a href="contato.php" class="nav-cta">
                    Fale Conosco
                </a>

            </nav>


            <div class="nav-actions">


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

            <div class="hero-orb hero-orb-1"></div>
            <div class="hero-orb hero-orb-2"></div>

        </div>



        <div class="container hero-content">


            <span class="hero-tag">
                <span class="pulse-dot"></span>
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
                    href="#clientes"
                    class="btn-primary btn-glow"
                >
                    Nossos Clientes
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
         CLIENTES
    =========================================================== -->

    <section
        id="clientes"
        class="section-padding reveal"
    >

        <div class="container">


            <span class="eyebrow">
                Quem confia na gente
            </span>


            <h2 class="section-title">
                Nossos Clientes
            </h2>


            <p class="section-subtitle">

                Empresas que já contam com a AdaTech para
                manter a operação de TI funcionando sem parar.

            </p>


            <div class="grid-layout">


                <!-- ==================================================
                     CLIENTE 1
                =================================================== -->

                <div class="card client-card">

                    <span class="client-quote-mark">&ldquo;</span>

                    <div class="client-stars">★★★★★</div>

                    <p>
                        Depoimento do cliente sobre o serviço
                        prestado pela AdaTech.
                    </p>

                    <div class="client-profile">

                        <img
                            src="https://ui-avatars.com/api/?name=Carlos+Mendes&background=0284c7&color=fff&bold=true&size=128"
                            alt="Foto de Carlos Mendes"
                            class="client-avatar"
                        >

                        <div class="client-info">
                            <h3>Carlos Mendes</h3>
                            <span class="client-role">Diretor de TI &middot; Grupo Fortaleza</span>
                        </div>

                    </div>

                </div>



                <!-- ==================================================
                     CLIENTE 2
                =================================================== -->

                <div class="card client-card">

                    <span class="client-quote-mark">&ldquo;</span>

                    <div class="client-stars">★★★★★</div>

                    <p>
                        Depoimento do cliente sobre o serviço
                        prestado pela AdaTech.
                    </p>

                    <div class="client-profile">

                        <img
                            src="https://ui-avatars.com/api/?name=Marina+Souza&background=008b8b&color=fff&bold=true&size=128"
                            alt="Foto de Marina Souza"
                            class="client-avatar"
                        >

                        <div class="client-info">
                            <h3>Marina Souza</h3>
                            <span class="client-role">Gerente Administrativa &middot; Construtora Horizonte</span>
                        </div>

                    </div>

                </div>



                <!-- ==================================================
                     CLIENTE 3
                =================================================== -->

                <div class="card client-card">

                    <span class="client-quote-mark">&ldquo;</span>

                    <div class="client-stars">★★★★★</div>

                    <p>
                        Depoimento do cliente sobre o serviço
                        prestado pela AdaTech.
                    </p>

                    <div class="client-profile">

                        <img
                            src="https://ui-avatars.com/api/?name=Rafael+Lima&background=075985&color=fff&bold=true&size=128"
                            alt="Foto de Rafael Lima"
                            class="client-avatar"
                        >

                        <div class="client-info">
                            <h3>Rafael Lima</h3>
                            <span class="client-role">CEO &middot; TechStart Soluções</span>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- ==========================================================
         SERVIÇOS
    =========================================================== -->

    <section
        id="servicos"
        class="section-bg section-padding reveal"
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
        class="section-padding reveal"
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