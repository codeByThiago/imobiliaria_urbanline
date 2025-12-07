<?php 
// O PHP permanece o mesmo
if(isset($_SESSION['sucess_message'])) {
    // Adicione um ID e a classe 'js-alert' para facilitar a manipulação via JavaScript
    echo "<div id='global-alert' class='alert alert-sucess js-alert'>✔️ {$_SESSION['sucess_message']}</div>";
    unset($_SESSION['sucess_message']);
} 

if(isset($_SESSION['error_message'])) {
    // Adicione um ID e a classe 'js-alert' para facilitar a manipulação via JavaScript
    echo "<div id='global-alert' class='alert alert-error js-alert'>⚠️ {$_SESSION['error_message']}</div>";
    unset($_SESSION['error_message']);
}
?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alertElement = document.querySelector('.js-alert');
        
        if (alertElement) {
            
            const displayTime = 5000;
            // O tempo da animação de saída (deve ser o mesmo do CSS: 500 milissegundos)
            const fadeOutTime = 500; 

            // Adiciona a classe de saída após o tempo de exibição
            setTimeout(() => {
                alertElement.classList.add('alert-hide');
                
                // Remove o elemento do DOM (da página) após a animação de saída terminar
                setTimeout(() => {
                    alertElement.remove();
                }, fadeOutTime);

            }, displayTime);
        }
    });
</script>