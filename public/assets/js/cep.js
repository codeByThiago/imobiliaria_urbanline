import { buscarCep, formatarCep } from './funcoes.js';

document.addEventListener("DOMContentLoaded", () => {
    const cepInput = document.getElementById('cep');

    // Só continua se existir o input de CEP
    if (!cepInput) return;

    // Formata o CEP enquanto digita
    cepInput.addEventListener('input', () => {
        cepInput.value = formatarCep(cepInput.value);
    });

    // Busca CEP ao apertar Enter
    cepInput.addEventListener('keydown', async (event) => {
        if (event.key === "Enter") {
            event.preventDefault();
            await preencherEndereco();
        }
    });

    // Busca CEP ao sair do campo
    cepInput.addEventListener('blur', preencherEndereco);

    // Função que preenche os campos do endereço
    async function preencherEndereco() {
        const data = await buscarCep(cepInput.value);
        if (!data) return;

        const campos = ['uf', 'cidade', 'bairro', 'logradouro'];
        campos.forEach(id => {
            const campo = document.getElementById(id);
            if (!campo) return; // ignora se não existir

            // mapeia cidade -> localidade da API
            campo.value = data[id === 'cidade' ? 'localidade' : id] || "";

            // define se o campo fica editável ou não
            if (campo.value !== "") {
                campo.setAttribute('readonly', true);
                campo.classList.add('disabled');
            } else {
                campo.removeAttribute('readonly');
                campo.classList.remove('disabled');
            }
        });
    }
});