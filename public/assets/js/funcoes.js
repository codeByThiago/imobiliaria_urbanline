export async function buscarCep(cep) {
    cep = cep.replace(/\D/g, "");

    if (cep.length !== 8) {
        alert("Por favor, insira um CEP válido de 8 dígitos");
        return null;     
    }

    try {
        const response = await fetch(`https://viacep.com.br/ws/${cep}/json`);
        const data = await response.json();

        if (data.erro) {
            alert("CEP não encontrado");
            return null;
        }

        return data;
    } catch (erro) {
        console.error("Erro ao buscar o CEP: ", erro);
        return null;
    }
}

// Função para formatar CEP automaticamente
export function formatarCep(cep) {
    cep = cep.replace(/\D/g, "");
    if (cep.length > 5) cep = cep.slice(0,5) + "-" + cep.slice(5,8);
    return cep;
}

export function formatarTelefone(telefone) {
    // 1. Limpeza: Remove tudo que não é dígito e limita a 11
    let value = telefone.replace(/\D/g, '');
    value = value.substring(0, 11);

    // 2. Formatação progressiva baseada no tamanho (melhor para máscara em tempo real)
    
    // Se digitou mais de 10 dígitos (9º dígito móvel): (XX) XXXXX-XXXX
    if (value.length === 11) {
        // (XX) XXXXX-XXXX
        value = value.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
    } 
    // Se digitou 10 dígitos (DDD + 8 fixo): (XX) XXXX-XXXX
    else if (value.length === 10) {
        // (XX) XXXX-XXXX
        value = value.replace(/^(\d{2})(\d{4})(\d{4})$/, '($1) $2-$3');
    } 
    // Se digitou apenas o DDD e parte do número: (XX) XXXX...
    else if (value.length > 2) {
        // (XX) XXXX
        value = '(' + value.substring(0, 2) + ') ' + value.substring(2);
    } 
    // Se digitou apenas o DDD: (XX
    else if (value.length > 0) {
         // (XX
        value = '(' + value.substring(0, 2);
    }
    // Caso contrário, retorna o que foi digitado (1 ou 2 dígitos)
    
    return value;
}

export function formatarCpfCnpj(documento) {
    documento = documento.replace(/\D/g, "");

    if (documento.length <= 11) {
        // CPF: XXX.XXX.XXX-XX
        documento = documento.slice(0, 11);
        documento = documento.replace(/(\d{3})(\d)/, "$1.$2");
        documento = documento.replace(/(\d{3})(\d)/, "$1.$2");
        documento = documento.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    } else {
        // CNPJ: XX.XXX.XXX/XXXX-XX
        documento = documento.slice(0, 14);
        documento = documento.replace(/^(\d{2})(\d)/, "$1.$2");
        documento = documento.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
        documento = documento.replace(/\.(\d{3})(\d)/, ".$1/$2");
        documento = documento.replace(/(\d{4})(\d)/, "$1-$2");
    }
    return documento;
}