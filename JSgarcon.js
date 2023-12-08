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


function escondediv(num) {
    let div = document.getElementById(num);
    let btns = document.getElementById('carrossel')
    if (div.style.display === 'none' || div.style.display === '') {
        div.style.display = 'block';
        btns.style.display = 'none';
    } else {
        div.style.display = 'none';
        btns.style.display = 'flex';
    }
}

function mostrarObservacao(index) {
    var observacaoElement = document.getElementById('observacao-' + index);
    var inputObservacao = document.querySelector('input[name="observacao[' + index + ']"]');

    if (observacaoElement.style.display === 'none' || observacaoElement.style.display === '') {
        observacaoElement.style.display = 'block';
        if (!inputObservacao.value.trim()) {
            inputObservacao.value = inputObservacao.dataset.valorAnterior || 'Observação: ';
        }
    } else {
        observacaoElement.style.display = 'none';
        // Armazena o valor anterior no atributo de dados do campo de entrada
        inputObservacao.dataset.valorAnterior = inputObservacao.value;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Chamada da função após o carregamento completo da página
    mostrarObservacao(index);
});





