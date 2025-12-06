function clearErrorStyle(e) {
  const input = e.target;
  // Se o campo não estiver mais vazio E o estilo de borda for vermelho, reseta.
  if (input.value.trim() !== "" && input.style.borderColor === "red") {
    input.style.border = "2px solid var(--bg-accent-light)";
  }
}

// Função para configurar a remoção de erro em todos os inputs da etapa ativa
function setupLiveErrorRemoval(currentStep) {
  const inputs = currentStep.querySelectorAll("input");
  inputs.forEach((input) => {
    // Adiciona o listener para o evento 'input' (disparado a cada tecla digitada)
    input.removeEventListener("input", clearErrorStyle); // Evita duplicidade
    input.addEventListener("input", clearErrorStyle);
  });
}

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("auth-form");
  const steps = document.querySelectorAll(".form-step");
  const prevStepBtn = document.querySelector(".prev-step-btn");
  const nextStepBtn = document.querySelector(".next-step-btn");
  const submitBtn = document.querySelector(".auth-submit-btn");
  const progressBar = document.querySelector("#progresso");
  const progressText = document.querySelector("#progresso-text");

  const totalSteps = steps.length;
  let currentStepIndex = 0; // Começa no índice 0 (Etapa 1)-----------------------------

  // Função principal para exibir a etapa e atualizar o progresso
  function showStep(index) {
    steps.forEach((step) => step.classList.remove("active"));

    if (steps[index]) {
      steps[index].classList.add("active");
      currentStepIndex = index;
    }

    // Atualiza a visibilidade dos botões
    prevStepBtn.style.display = index === 0 ? "none" : "block";
    nextStepBtn.style.display = index === totalSteps - 1 ? "none" : "block";
    submitBtn.style.display = index === totalSteps - 1 ? "block" : "none";

    // Atualiza barra de progresso e texto
    const percentage = ((index + 1) / totalSteps) * 100;
    progressBar.style.width = percentage.toFixed(2) + "%";
    progressText.textContent = `Etapa ${index + 1} de ${totalSteps}`;

    // NOVO: Configura o listener para remoção de erro em tempo real na etapa atual
    setupLiveErrorRemoval(steps[currentStepIndex]);
  }

  // Função de validação robusta para a etapa atual
  function validateCurrentStep() {
    const currentStep = steps[currentStepIndex];
    const requiredInputs = currentStep.querySelectorAll("input[required]");
    let isValid = true;

    requiredInputs.forEach((input) => {
      // Garante que o input começa com a borda padrão antes da validação
      input.style.border = "2px solid var(--bg-accent-light)";

      // Validação básica do navegador
      if (!input.checkValidity() || input.value.trim() === "") {
        isValid = false;
        // Aplica a borda vermelha
        input.style.border = "2px solid red";
      }
    });

    // Validação extra para a Etapa 3: Confirmação de Senha
    if (currentStep.id === "step-3" && isValid) {
      const senha = document.getElementById("senha").value;
      const confirmeSenhaInput = document.getElementById("confirme-senha");

      if (senha !== confirmeSenhaInput.value) {
        isValid = false;
        confirmeSenhaInput.style.border = "2px solid red";
        alert("As senhas não coincidem!");
      } else {
        confirmeSenhaInput.style.border = "2px solid var(--bg-accent-light)";
      }
    }

    if (!isValid) {
      const firstInvalid = currentStep.querySelector('input[style*="red"]');
      if (firstInvalid) {
        firstInvalid.focus();
      }
    }

    return isValid;
  }
  nextStepBtn.addEventListener("click", () => {
    if (validateCurrentStep()) {
      if (currentStepIndex < totalSteps - 1) {
        showStep(currentStepIndex + 1);
      }
    }
  });

  prevStepBtn.addEventListener("click", () => {
    if (currentStepIndex > 0) {
      showStep(currentStepIndex - 1);
    }
  });

  form.addEventListener("submit", (e) => {
    if (!validateCurrentStep()) {
      e.preventDefault();
    }
  });

  showStep(currentStepIndex);
});
