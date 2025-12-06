import { formatarCpfCnpj } from "./funcoes.js";

document.addEventListener('DOMContentLoaded', () => {
    const cpfCnpjInput = document.getElementById('cpf');
    if (cpfCnpjInput) {
        cpfCnpjInput.addEventListener('input', () => {
            cpfCnpjInput.value = formatarCpfCnpj(cpfCnpjInput.value);
        });
    }
});