function submitform(form) {
    document.getElementById(form).submit(); 
}

var el = document.getElementById('fora');
el.addEventListener('click', function(e) {
    alert(e.target.id);
});

function home() {
    location.href="home";
}
function conteudo() {
    location.href="conteudo";
}

function juiz() {
    location.href="juiz";
}

function pessoa() {
    location.href="pessoa";
}

function genero_autor() {
    location.href="genero_autor";
}

function genero_reu() {
    location.href="genero_reu";
}

function busca() {
    location.href="busca";
}

function comarca() {
    location.href="index";
}

function selecionarComarca() {
    if(selecaoComarca.comarcas.value == "none"){
        alert("Por favor, selecione uma comarca.");
        selecaoComarca.comarcas.focus();
        return false;
    }

    else {
        return true;
    }
}


