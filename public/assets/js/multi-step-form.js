document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('imovel-form');
    const steps = form.querySelectorAll('.form-step');
    const nextButtons = form.querySelectorAll('.next-step-btn');
    const prevButtons = form.querySelectorAll('.prev-step-btn');
    const indicators = document.querySelectorAll('.progress-step');
    let currentStep = 1;

    function showStep(step) {
        steps.forEach(s => s.classList.remove('active'));
        indicators.forEach(i => i.classList.remove('active'));
        
        const targetStep = form.querySelector(`[data-step="${step}"]`);
        const targetIndicator = document.getElementById(`step-${step}-indicator`);
        
        if (targetStep) {
            targetStep.classList.add('active');
        }
        if (targetIndicator) {
            targetIndicator.classList.add('active');
        }

        // Ativa indicadores dos passos anteriores
        for (let i = 1; i < step; i++) {
            document.getElementById(`step-${i}-indicator`).classList.add('active');
        }
        
        currentStep = step;
    }

    // Função para validar campos da etapa atual
    function validateCurrentStep() {
        const currentInputs = form.querySelector(`[data-step="${currentStep}"]`).querySelectorAll('[required]');
        let isValid = true;
        
        currentInputs.forEach(input => {
            if (!input.value) {
                isValid = false;
                input.reportValidity(); // Mostra o pop-up de erro do navegador
            }
        });
        
        return isValid;
    }

    // Navegação para a próxima etapa
    nextButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (validateCurrentStep()) {
                const nextStep = parseInt(this.getAttribute('data-next-step'));
                showStep(nextStep);
            }
        });
    });

    // Navegação para a etapa anterior
    prevButtons.forEach(button => {
        button.addEventListener('click', function() {
            const prevStep = parseInt(this.getAttribute('data-prev-step'));
            showStep(prevStep);
        });
    });

    // Inicializa o formulário
    showStep(currentStep);
});

