function adicionarItensCarrinho(grupoId) {
    let formulariosProdutos = document.querySelectorAll('.product-group' + grupoId);
    let itensParaCarrinho = [];

    formulariosProdutos.forEach(function(formulario) {
        let quantidadeInputs = formulario.querySelectorAll('.quant');

        quantidadeInputs.forEach(function(quantidadeInput) {
            let quantidade = quantidadeInput.value;
            if (parseInt(quantidade) > 0) {
                let produto = quantidadeInput.closest('form').querySelector('input[name="produto"]').value;
                let codPro = quantidadeInput.closest('form').querySelector('input[name="cod_pro"]').value;
                let preco = quantidadeInput.closest('form').querySelector('input[name="preco"]').value;
                let codGruest = quantidadeInput.closest('form').querySelector('input[name="cod_gruest"]').value;

                itensParaCarrinho.push({
                    produto: produto,
                    cod_pro: codPro,
                    preco: preco,
                    cod_gruest: codGruest,
                    quantidade: quantidade
                });
            }
        });
    });

    // Criar um formulário para enviar os dados via POST
    let form = document.createElement('form');
    form.method = 'POST';
    form.action = 'garcon.php';

    // Criar um input oculto para enviar os itens para o carrinho
    let inputItens = document.createElement('input');
    inputItens.type = 'hidden';
    inputItens.name = 'itens_para_carrinho';
    inputItens.value = JSON.stringify(itensParaCarrinho);

    // Adicionar o input ao formulário e submeter
    form.appendChild(inputItens);
    document.body.appendChild(form);
    form.submit();
}


function mostrapedidos(){
    let ped = document.getElementById('produtos');
    let car = document.getElementById('carrossel');
    ped.style.display = 'flex'
    car.style.display = 'none'
}

function voltartelainicial(){
    let ped = document.getElementById('produtos');
    let car = document.getElementById('carrossel');
    ped.style.display = 'none'
    car.style.display = 'flex'
}

function alteraQuantidade(inputId, quantidade) {
    let input = document.getElementById(inputId);

    if (input) {
        let valorAtual = parseInt(input.value, 10);
        let novaQuantidade = valorAtual + quantidade;
        novaQuantidade = Math.max(0, novaQuantidade);
        input.value = novaQuantidade;
    }
}

function escondediv(num) {
    let div = document.getElementById(num);
    let btns = document.getElementById('carrossel');
    let confere = document.getElementById("conferepedido");
    if (div.style.display === 'none' || div.style.display === '') {
        div.style.display = 'flex';
        btns.style.display = 'none';
        confere.style.display = 'none';
    } else {
        div.style.display = 'none';
        btns.style.display = 'flex';
        confere.style.display = 'none';
    }
}

function fadeInObservacao(index) {
    let inputObs = document.querySelector(`#carrinho-${index} .inputobs`);
    let btn = document.getElementById("btn" + index);

    if (inputObs.style.display === "none") {
        inputObs.style.display = "inline-block";
        btn.style.display = "inline-block";
    } else {
        inputObs.style.display = "none";
        btn.style.display = "none";
    }
}

function adicionarObservacao(index) {
    let inputObs = document.querySelector(`#carrinho-${index} .inputobs`);
    let observacao = inputObs.value.trim();

    let formData = new FormData();
    formData.append('observacao[' + index + ']', observacao);

    fetch('garcon.php', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao adicionar observação');
            }
            return response.text();
        })
        .then(data => {
            // Atualize a interface do usuário conforme necessário, se desejar
            console.log('Observação adicionada com sucesso:');
            window.location.reload();
        })
        .catch(error => {
            console.error('Erro:', error);
        });
}

function toggleObservacao(index) {
    let observacaoDiv = document.querySelector(`#observacao-${index}`);
    let seta = observacaoDiv.previousElementSibling.querySelector('span');

    if (observacaoDiv.style.display === "none") {
        observacaoDiv.style.display = "flex";
        seta.classList.add('.gira');
    } else {
        observacaoDiv.style.display = "none";
        seta.classList.remove('.gira');
    }
}

function mostraconclusao(para){
    let idpedido = document.getElementById(para);
    let idconc = document.getElementById("conferepedido");

    idpedido.style.display= 'none';
    idconc.style.display= 'flex';

}

function mudanum(numero){
    let inp = document.getElementById(numero);
    inp.value = inp.value + 1;
}

document.addEventListener('DOMContentLoaded', function() {
    // Adicione um listener para cada input de observação
    document.querySelectorAll('.inputobs').forEach(function(input, index) {
        input.addEventListener('input', function(event) {
            handleInput(event, index);
        });
    });
});

function handleInput(event, index) {
    if (event.inputType === 'insertLineBreak') {
        event.preventDefault();
        hideKeyboard();
        mostrarObservacao(index);
    }
}

function hideKeyboard() {
    if ("ontouchstart" in document.documentElement) {
        let activeElement = document.activeElement;
        if (activeElement.tagName.toLowerCase() == "input" || activeElement.tagName.toLowerCase() == "textarea") {
            activeElement.blur();
        }
    }
}
