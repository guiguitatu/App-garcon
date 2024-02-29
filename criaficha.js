function mudapagina(bool){
    if (bool){
        let div = document.getElementById('formficha');
        let btns = document.getElementById('btns');
        div.style.display = 'flex';
        btns.style.display = 'none';
    } else {
        window.location.href = '//192.168.1.38';
    }
}