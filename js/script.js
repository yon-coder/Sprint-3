function abrirLogin() {
    document.getElementById("login-modal").style.display = "flex";
}

function abrirCadastro() {
    document.getElementById("cadastro-modal").style.display = "flex";
}

function fecharModal() {
    const modals = document.querySelectorAll(".modal");
    modals.forEach(modal => modal.style.display = "none");
}

function login() {
    const usuario = document.getElementById("usuario").value;
    const senha = document.getElementById("senha").value;
    alert("Login: " + usuario + "\nSenha: " + senha);
}

function cadastrar() {
    const nome = document.getElementById("nome").value;
    const usuario = document.getElementById("novoUsuario").value;
    const idade = document.getElementById("idade").value;
    const peso = document.getElementById("peso").value;
    const altura = document.getElementById("altura").value;
    const objetivo = document.getElementById("objetivo").value;
    const senha = document.getElementById("novaSenha").value;
    alert(`Cadastro realizado!\nNome: ${nome}\nUsuário: ${usuario}\nObjetivo: ${objetivo}`);
    fecharModal();
}

window.onclick = function(event) {
    if (event.target.classList.contains("modal")) {
        fecharModal();
    }
}
