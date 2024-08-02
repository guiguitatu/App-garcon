function adicionarTodosItensCarrinho() {
    let formulariosProdutos = document.querySelectorAll('.product form');
    let itensParaCarrinho = [];

    formulariosProdutos.forEach(function (formulario) {
        let quantidadeInput = formulario.querySelector('.quant');
        let quantidade = parseInt(quantidadeInput.value, 10);

        console.log('Produto:', formulario.querySelector('input[name="produto"]').value, 'Quantidade:', quantidade); // Log de depuração

        if (quantidade > 0) {
            let produto = formulario.querySelector('input[name="produto"]').value;
            let codPro = formulario.querySelector('input[name="cod_pro"]').value;
            let preco = formulario.querySelector('input[name="preco"]').value;
            let codGruest = formulario.querySelector('input[name="cod_gruest"]').value;

            itensParaCarrinho.push({
                produto: produto, cod_pro: codPro, preco: preco, cod_gruest: codGruest, quantidade: quantidade
            });
        }
    });

    console.log('Itens para Carrinho:', itensParaCarrinho); // Depuração final

    if (itensParaCarrinho.length > 0) {
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
    } else {
        alert('Nenhum item selecionado para adicionar ao carrinho.');
    }
}

function mostrapedidos() {
    let ped = document.getElementById('produtos');
    let btns = document.getElementById('200');
    let btn = document.getElementById('btnverpedido');
    let carrinho = document.getElementById('carrinhodiv');
    ped.style.display = 'flex';
    btns.style.display = 'none';
    btn.style.display = 'none';
    carrinho.style.display = 'none';
}

function telabtns() {
    let btns = document.getElementById('200');
    let pedido = document.getElementById('produtos');
    let btnpedido = document.getElementById('btnverpedido');

    btns.style.display = 'flex';
    pedido.style.display = 'none';
    btnpedido.style.display = 'block';
}

function voltartelainicial() {
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
        if (input.value <= 1) {
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
    let envia = document.getElementById("adicionar-todos-carrinho")
    let carrinho;
    if (num <= 100) {
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
        envia.style.display = "block"
    } else {
        div.style.display = 'none';
        carrinho.style.display = 'flex';
        btn.style.display = 'block';
        confere.style.display = 'none';
        envia.style.display = "none"
    }
}

function mostrabtns(num) {
    console.log("funcmostrabtns")
    let btns = document.getElementById(num);
    let carrinho = document.getElementById("carrinhodiv");


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
        method: 'POST', body: formData
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

function mostraconclusao(para) {
    let idpedido = document.getElementById('carrinhodiv');
    let idconc = document.getElementById("conferepedido");
    let btnpedido = document.getElementById("btnverpedido");

    idpedido.style.display = 'none';
    idconc.style.display = 'flex';
    btnpedido.style.display = 'none';

}

function mudanum(numero) {
    let inp = document.getElementById(numero);
    inp.value = inp.value + 1;
}

window.onload = function () {
    let botaoAdicionarCarrinho = document.getElementById('adicionar-todos-carrinho');
    botaoAdicionarCarrinho.style.display = 'none'; // Esconder o botão inicialmente
}

function searchFunction() {
    let input = document.getElementById('search').value;
    let botaoAdicionarCarrinho = document.getElementById('adicionar-todos-carrinho');

    if (input.length > 0) {
        botaoAdicionarCarrinho.style.display = 'block';

        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                try {
                    let data = JSON.parse(this.responseText);
                    console.log('Resposta JSON:', data); // Depuração
                    let resultDiv = document.getElementById("result");
                    resultDiv.innerHTML = ''; // Limpa os resultados anteriores

                    if (Array.isArray(data)) {
                        let produtosAgrupados = {};
                        for (let i = 0; i < data.length; i++) {
                            let produto = data[i];
                            let cod_gruest = produto.COD_GRUEST;
                            if (!produtosAgrupados[cod_gruest]) {
                                produtosAgrupados[cod_gruest] = [];
                            }
                            produtosAgrupados[cod_gruest].push(produto);
                        }

                        for (let cod_gruest in produtosAgrupados) {
                            let count = produtosAgrupados[cod_gruest];
                            let groupDiv = document.createElement('div');
                            groupDiv.className = 'product-group product-group' + cod_gruest;
                            groupDiv.id = cod_gruest;
                            groupDiv.style.display = 'block';
                            let pElement = document.createElement('p');
                            pElement.textContent = "Categoria: " + count[cod_gruest].NOME;
                            pElement.style.margin = '20px 0 10px 25px';
                            pElement.style.fontSize = '60px';
                            groupDiv.appendChild(pElement);
                            let produtos = produtosAgrupados[cod_gruest];
                            for (let j = 0; j < produtos.length; j++) {
                                let produto = produtos[j];
                                let produtoId = 'produto_' + produto.COD_PROAPP + '_' + cod_gruest;
                                let itemDiv = document.createElement('div');
                                itemDiv.className = 'product';

                                let form = document.createElement('form');
                                form.action = '';
                                form.className = 'produto';
                                form.method = 'post';
                                form.onkeydown = function() {
                                    return event.key !== 'Enter';
                                };

                                let valorFormatado = typeof produto.VALOR === 'number' ? produto.VALOR.toFixed(2).replace('.', ',') : "0,00";

                                form.innerHTML = `
                                    <input type="hidden" name="produto" value="${produto.DESCRICAO}">
                                    <input type="hidden" name="cod_pro" value="${produto.COD_PROAPP}">
                                    <input type="hidden" name="preco" value="${valorFormatado}">
                                    <input type="hidden" name="cod_gruest" value="${cod_gruest}">
                                    <input type="button" class="btnquant" id="mais${produtoId}" name="mais" onclick="alteraQuantidade('${produtoId}', 1)" value="+">
                                    <input name="quantidade" class="quant" id="${produtoId}" value="0" min="0">
                                    <input type="button" class="btnquant" name="menos" onclick="alteraQuantidade('${produtoId}', -1)" value="-">
                                    <p style="font-size: 55px">${produto.DESCRICAO}</p>
                                `;
                                itemDiv.appendChild(form);
                                groupDiv.appendChild(itemDiv);
                            }

                            resultDiv.appendChild(groupDiv);
                        }
                    } else {
                        resultDiv.textContent = 'Nenhum dado encontrado';
                    }
                } catch (error) {
                    console.error('Erro ao analisar a resposta:', error);
                    console.log('Resposta:', this.responseText);
                }
            }
        };
        xhttp.open("GET", "busca.php?q=" + encodeURIComponent(input), true);
        xhttp.send();
    } else {
        botaoAdicionarCarrinho.style.display = 'none';
        document.getElementById("result").innerHTML = "";
    }
}

function searchInGroup(cod_gruest) {
    let input = document.querySelector('.product-group' + cod_gruest + ' .search-group').value.toLowerCase();

    let produtos = document.querySelectorAll('.product-group' + cod_gruest + ' .product');

    produtos.forEach(function (produto) {
        let descricao = produto.querySelector('input[name="produto"]').value.toLowerCase();

        if (descricao.includes(input)) {
            produto.style.display = 'block';
        } else {
            produto.style.display = 'none';
        }
    });
}