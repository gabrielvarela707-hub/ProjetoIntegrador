document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('btn-personalize');
    btn.addEventListener('click', () => {
        const tel = "5511999999999"; // Coloque seu número aqui
        const msg = "Olá! Gostaria de um orçamento para serviços da ADATECH.";
        window.open(`https://wa.me/${tel}?text=${encodeURIComponent(msg)}`, '_blank');
    });
});