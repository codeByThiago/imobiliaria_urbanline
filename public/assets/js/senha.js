function eyePasswordView() {
    const mostraSenha = document.getElementById("mostra-senha-btn");
    const mostraConfirmeSenha = document.getElementById("mostra-confirme-senha-btn");
    const iconeOlhoSenha = document.getElementById('eye-senha');
    const iconeOlhoConfirmeSenha = document.getElementById('eye-confirme-senha');

    mostraSenha.addEventListener("click", () => {
        const senhaInput = document.getElementById("senha");
        if (senhaInput && iconeOlhoSenha) { 
            senhaInput.type = senhaInput.type === "password" ? "text" : "password";
            iconeOlhoSenha.classList.toggle('fa-eye');
            iconeOlhoSenha.classList.toggle('fa-eye-slash');
        }
    });

    mostraConfirmeSenha.addEventListener("click", () => {
        const confirmeSenhaInput = document.getElementById("confirme-senha");
        if (confirmeSenhaInput && iconeOlhoConfirmeSenha) { 
            confirmeSenhaInput.type = confirmeSenhaInput.type === "password" ? "text" : "password";
            iconeOlhoConfirmeSenha.classList.toggle('fa-eye');
            iconeOlhoConfirmeSenha.classList.toggle('fa-eye-slash');
        }
    });
}

document.addEventListener("DOMContentLoaded", eyePasswordView);