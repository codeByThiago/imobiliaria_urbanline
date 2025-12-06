let sliderInterval;

function initBackgroundSlider() {
    const listaDeImagens = document.querySelectorAll('.background-image');
    let currentIndex = 0;

    function showNextImage() {
        listaDeImagens[currentIndex].classList.remove('active');
        currentIndex = (currentIndex + 1) % listaDeImagens.length;
        listaDeImagens[currentIndex].classList.add('active');
    }

    // limpa interval antigo se existir
    if (sliderInterval) clearInterval(sliderInterval);
    sliderInterval = setInterval(showNextImage, 4000);
}

// Função para parar o slider (para mobile)
function stopBackgroundSlider() {
    if (sliderInterval) clearInterval(sliderInterval);
}

const mediaQuery = window.matchMedia("(min-width: 768px)");

function handleScreenChange(e) {
    if (e.matches) {
        initBackgroundSlider();
    } else {
        stopBackgroundSlider();
    }
}

// Inicializa ao carregar
document.addEventListener('DOMContentLoaded', () => handleScreenChange(mediaQuery));

// Escuta mudanças de tela
mediaQuery.addEventListener('change', handleScreenChange);


// Marcar/desmarcar todos
todosCheckbox.addEventListener('change', () => {
    checkboxes.forEach(cb => cb.checked = todosCheckbox.checked);
});

// Atualizar "Todos" se algum checkbox individual for desmarcado
checkboxes.forEach(cb => {
    cb.addEventListener('change', () => {
        if (!cb.checked) {
            todosCheckbox.checked = false;
        } else {
            // se todos os checkboxes estiverem marcados, marcar "Todos"
            todosCheckbox.checked = Array.from(checkboxes).every(chk => chk.checked);
        }
    });
});

const maisFiltrosBtn = document.querySelector('#mais-filtros-btn');
const maisFiltrosContainer = document.querySelector('#mais-filtros');

maisFiltrosBtn.addEventListener('click', () => {
    maisFiltrosContainer.classList.toggle('active');
});