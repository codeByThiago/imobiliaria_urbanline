// Menu
let menuIcon = document.getElementById('menu');

menuIcon.addEventListener('click', () => {
    const navbarItems = document.querySelector('.navbar-items');
    navbarItems.classList.toggle('active');
});

const tipoBtn = document.querySelector('.tipo-imovel-btn');
const tipoContainer = document.querySelector('.tipo-imovel');
const todosCheckbox = document.getElementById('tipo-todos');
const checkboxes = document.querySelectorAll('.tipo-imovel input[name="tipo[]"]');

// Abrir/fechar dropdown
tipoBtn.addEventListener('click', () => {
    tipoContainer.classList.toggle('active');
});
