document.addEventListener('DOMContentLoaded', () => {

    /* ==================================================
       ANIMAÇÃO DE ENTRADA AO ROLAR A PÁGINA (reveal)
    =================================================== */
    const elementosReveal = document.querySelectorAll('.reveal');

    if (elementosReveal.length && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        elementosReveal.forEach(el => observer.observe(el));
    } else {
        // Navegadores sem suporte: mostra tudo direto
        elementosReveal.forEach(el => el.classList.add('visible'));
    }

    /* ==================================================
       SOMBRA NA NAVBAR AO ROLAR A PÁGINA
    =================================================== */
    const navbar = document.querySelector('.navbar');

    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 10);
        });
    }

});
