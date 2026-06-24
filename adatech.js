document.addEventListener('DOMContentLoaded', () => {
    // Elementos Originais do Formulário
    const form = document.getElementById('form-contato');
    const selectAssunto = document.getElementById('assunto');
    const feedbackMsg = document.getElementById('form-feedback');
    const botoesOrcamento = document.querySelectorAll('.btn-orcamento');

    // Mapeamento de Preços Simulados (Padrão Dell Corporativo)
    const precosProdutos = {
        "Dell Inspiron 15": 3899.00,
        "Dell Vostro Desktop": 4599.00,
        "Dell Latitude 3440": 5299.00
    };

    // Estado da Aplicação (Carrinho de Compras)
    let carrinho = [];

    // Elementos Novos do Carrinho
    const btnOpenCart = document.getElementById('open-cart');
    const btnCloseCart = document.getElementById('close-cart');
    const cartModal = document.getElementById('cart-modal');
    const cartItemsContainer = document.getElementById('cart-items');
    const cartCount = document.getElementById('cart-count');
    const cartTotal = document.getElementById('cart-total');
    const btnCheckout = document.getElementById('checkout-cart');

    // Funcionalidade: Captura o produto e adiciona diretamente ao carrinho (Fluxo E-commerce)
    botoesOrcamento.forEach(botao => {
        // Altera o comportamento visual/texto do botão original mantendo as classes
        botao.textContent = "Adicionar ao Carrinho";
        botao.classList.remove('btn-secondary');
        botao.classList.add('btn-primary');

        botao.addEventListener('click', (e) => {
            e.preventDefault();
            const produtoSelecionado = botao.getAttribute('data-produto');
            if (produtoSelecionado) {
                adicionarAoCarrinho(produtoSelecionado);
                abrirCarrinho();
            }
        });
    });

    // Funções de Gerenciamento do Carrinho
    function adicionarAoCarrinho(nomeProduto) {
        const itemExistente = carrinho.find(item => item.nome === nomeProduto);

        if (itemExistente) {
            itemExistente.quantidade++;
        } else {
            carrinho.push({
                nome: nomeProduto,
                preco: precosProdutos[nomeProduto] || 0,
                quantidade: 1
            });
        }
        atualizarInterfaceCarrinho();
    }

    function removerDoCarrinho(nomeProduto) {
        carrinho = carrinho.filter(item => item.nome !== nomeProduto);
        atualizarInterfaceCarrinho();
    }

    function atualizarInterfaceCarrinho() {
        // Atualiza contador de itens totais
        const totalItens = carrinho.reduce((acc, item) => acc + item.quantidade, 0);
        cartCount.textContent = totalItens;

        // Renderiza itens na tela
        cartItemsContainer.innerHTML = '';
        let valorTotal = 0;

        carrinho.forEach(item => {
            valorTotal += item.preco * item.quantidade;
            const itemElement = document.createElement('div');
            itemElement.className = 'cart-item';
            itemElement.innerHTML = `
                <div class="cart-item-info">
                    <h4>${item.nome}</h4>
                    <p>${item.quantidade}x - R$ ${item.preco.toFixed(2)}</p>
                </div>
                <button class="remove-item" data-produto="${item.nome}">Remover</button>
            `;
            cartItemsContainer.appendChild(itemElement);
        });

        cartTotal.textContent = `R$ ${valorTotal.toFixed(2)}`;

        // Reatribui eventos aos botões de remover gerados dinamicamente
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', () => {
                removerDoCarrinho(btn.getAttribute('data-produto'));
            });
        });
    }

    function abrirCarrinho() { cartModal.classList.add('active'); }
    function fecharCarrinho() { cartModal.classList.remove('active'); }

    // Eventos de Controle do Modal do Carrinho
    btnOpenCart.addEventListener('click', abrirCarrinho);
    btnCloseCart.addEventListener('click', fecharCarrinho);
    cartModal.addEventListener('click', (e) => { if (e.target === cartModal) fecharCarrinho(); });

    // Checkout Integrado com o Formulário de Orçamento Original
    btnCheckout.addEventListener('click', () => {
        if (carrinho.length === 0) {
            alert("Seu carrinho está vazio!");
            return;
        }
        fecharCarrinho();
        // Preenche o campo assunto automaticamente com o resumo do carrinho
        selectAssunto.value = carrinho.map(i => `${i.quantidade}x ${i.nome}`).join(', ');
        // Rola a tela até o formulário de finalização de orçamento
        document.getElementById('contato').scrollIntoView({ behavior: 'smooth' });
    });

    // Processamento do Formulário Original (Preservado e adaptado para múltiplos itens)
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const dadosFormulario = {
            nome: document.getElementById('nome').value.trim(),
            email: document.getElementById('email').value.trim(),
            assunto: selectAssunto.value,
            mensagem: document.getElementById('mensagem').value.trim(),
            itensCarrinho: carrinho
        };

        exibirFeedback("Enviando solicitação de orçamento...", "feedback-success");

        setTimeout(() => {
            console.log("=== Novo Lead Recebido (AdaTech) ===");
            console.table(dadosFormulario);

            exibirFeedback(
                `Obrigado, ${dadosFormulario.nome}! Sua solicitação sobre "${dadosFormulario.assunto}" foi recebida. Entraremos em contato em até 24 horas.`, 
                "feedback-success"
            );

            form.reset();
            carrinho = []; // Limpa o carrinho após fechar a cotação
            atualizarInterfaceCarrinho();
        }, 1200);
    });

    function exibirFeedback(mensagem, classeStatus) {
        feedbackMsg.textContent = mensagem;
        feedbackMsg.className = `feedback-msg ${classeStatus}`;
        feedbackMsg.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
});