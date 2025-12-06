import { formatarTelefone } from "./funcoes.js";

let telefoneInput = document.getElementById('telefone');

telefoneInput.addEventListener('input', () => {
    let valorFormatado = formatarTelefone(telefoneInput.value);
    
    telefoneInput.value = valorFormatado;
});