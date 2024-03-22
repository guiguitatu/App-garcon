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

    let form = document.createElement('form');
    form.method = 'POST';
    form.action = 'garcon.php';

    let inputItens = document.createElement('input');
    inputItens.type = 'hidden';
    inputItens.name = 'itens_para_carrinho';
    inputItens.value = JSON.stringify(itensParaCarrinho);

    form.appendChild(inputItens);
    document.body.appendChild(form);
    form.submit();
}


function mostrapedidos(){
    let ped = document.getElementById('produtos');
    let btns = document.getElementById('200');
    let btn = document.getElementById('btnverpedido');
    let carrinho = document.getElementById('carrinhodiv');
    ped.style.display = 'flex';
    btns.style.display = 'none';
    btn.style.display = 'none';
    carrinho.style.display = 'none';
}

function telabtns(){
    let btns = document.getElementById('200');
    let pedido = document.getElementById('produtos');
    let btnpedido = document.getElementById('btnverpedido');

    btns.style.display = 'flex';
    pedido.style.display = 'none';
    btnpedido.style.display = 'block';
}

function voltartelainicial(){
    let ped = document.getElementById("produtos");
    let car = document.getElementById("carrinhodiv");
    let btns = document.getElementById("200");
    let btn = document.getElementById("btnverpedido");
    let confere = document.getElementById("conferepedido");
    btns.style.display = 'none';
    ped.style.display = 'none';
    confere.style.display = 'none';
    car.style.display = 'flex';
    btn.style.display = 'block';
}

function alteraQuantidade(inputId, quantidade) {
    let input = document.getElementById(inputId);
    let btnmais = document.getElementById('mais' + inputId)
    if (quantidade === 1) {
        btnmais.style.backgroundColor = '#e37069';
    } else {
        if (input.value <= 1){
            btnmais.style.backgroundColor = '#35518c';
        }
    }

    if (input) {
        let valorAtual = parseInt(input.value, 10);
        let novaQuantidade = valorAtual + quantidade;
        novaQuantidade = Math.max(0, novaQuantidade);
        input.value = novaQuantidade;
    }
}

function escondediv(num) {
    let div = document.getElementById(num);
    let carrinho;
    if (num <= 100){
        carrinho = document.getElementById("200");
    } else {
        carrinho = document.getElementById("carrinhodiv");
    }
    let confere = document.getElementById("conferepedido");
    let btn = document.getElementById("btnverpedido");
    confere.style.display = 'none';
    if (div.style.display === 'none' || div.style.display === '') {
        div.style.display = 'flex';
        carrinho.style.display = 'none';
        confere.style.display = 'none';
        btn.style.display = 'none';
    } else {
        div.style.display = 'none';
        carrinho.style.display = 'flex';
        btn.style.display = 'block';
        confere.style.display = 'none';
    }
}

function mostrabtns(num){
    console.log("funcmostrabtns")
    let btns = document.getElementById(num);
    let carrinho = document.getElementById("carrinhodiv");
    let pedido = document.getElementById('100');


    carrinho.style.display = 'none';
    btns.style.display = 'flex';

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
    let idpedido = document.getElementById('carrinhodiv');
    let idconc = document.getElementById("conferepedido");
    let btnpedido = document.getElementById("btnverpedido");

    idpedido.style.display= 'none';
    idconc.style.display= 'flex';
    btnpedido.style.display = 'none';

}

function mudanum(numero){
    let inp = document.getElementById(numero);
    inp.value = inp.value + 1;
}
