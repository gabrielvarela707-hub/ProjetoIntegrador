document.addEventListener('DOMContentLoaded', () => {
    const selectAssunto = document.getElementById('assunto');
    const botoesOrcamento = document.querySelectorAll('.btn-orcamento');

    const precosProdutos = {
        "Dell Inspiron 15": 3899.00,
        "Dell Vostro Desktop": 4599.00,
        "Dell Latitude 3440": 5299.00
    };

    let carrinho = [];

    const btnOpenCart = document.getElementById('open-cart');
    const btnCloseCart = document.getElementById('close-cart');
    const cartModal = document.getElementById('cart-modal');
    const cartItemsContainer = document.getElementById('cart-items');
    const cartCount = document.getElementById('cart-count');
    const cartTotal = document.getElementById('cart-total');
    const btnCheckout = document.getElementById('checkout-cart');

    if (botoesOrcamento) {
        botoesOrcamento.forEach(botao => {
            botao.textContent = "Adicionar ao Carrinho";
            botao.classList.remove('btn-secondary');
            botao.classList.add('btn-primary');

            botao.addEventListener('click', (e) => {
                e.preventDefault(); // <-- Impede que a página pule para o contato
                const produtoSelecionado = botao.getAttribute('data-produto');
                if (produtoSelecionado) {
                    adicionarAoCarrinho(produtoSelecionado);
                    abrirCarrinho();
                }
            });
        });
    }

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
        if (!cartCount || !cartItemsContainer || !cartTotal) return;

        const totalItens = carrinho.reduce((acc, item) => acc + item.quantidade, 0);
        cartCount.textContent = totalItens;

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

        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', () => {
                removerDoCarrinho(btn.getAttribute('data-produto'));
            });
        });
    }

    function abrirCarrinho() { if (cartModal) cartModal.classList.add('active'); }
    function fecharCarrinho() { if (cartModal) cartModal.classList.remove('active'); }

    if (btnOpenCart) btnOpenCart.addEventListener('click', abrirCarrinho);
    if (btnCloseCart) btnCloseCart.addEventListener('click', fecharCarrinho);
    if (cartModal) {
        cartModal.addEventListener('click', (e) => { if (e.target === cartModal) fecharCarrinho(); });
    }

    // Botão de Fechar Pedido (Gera o form dinâmico com o valor exato do carrinho)
    if (btnCheckout) {
        btnCheckout.addEventListener('click', () => {
            if (carrinho.length === 0) {
                alert("Seu carrinho está vazio!");
                return;
            }

            // 1. Calcula o valor total numérico exato
            let valorTotal = carrinho.reduce((acc, item) => acc + (item.preco * item.quantidade), 0);
            
            // 2. Cria a lista descritiva dos produtos
            let descricaoProdutos = carrinho.map(i => `${i.quantidade}x ${i.nome}`).join(', ');

            // 3. Cria um formulário dinamicamente para enviar os dados via POST para o pagamento.php
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'pagamento.php';

            form.target = '_blank';

            // Input do Valor
            const inputValor = document.createElement('input');
            inputValor.type = 'hidden';
            inputValor.name = 'valor';
            inputValor.value = valorTotal.toFixed(2);
            form.appendChild(inputValor);

            // Input do Produto
            const inputProduto = document.createElement('input');
            inputProduto.type = 'hidden';
            inputProduto.name = 'produto';
            inputProduto.value = "Carrinho: " + descricaoProdutos;
            form.appendChild(inputProduto);

            // Input de gatilho para gerar o pagamento
            const inputGerar = document.createElement('input');
            inputGerar.type = 'hidden';
            inputGerar.name = 'gerar_pagamento';
            inputGerar.value = '1';
            form.appendChild(inputGerar);

            // Adiciona o formulário na página e envia
            document.body.appendChild(form);
            form.submit();
        });
    }
});