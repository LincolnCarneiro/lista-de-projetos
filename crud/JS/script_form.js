class Projeto {
    constructor() {
        this.id = 0;
        this.nomeProjeto = "";
        this.dataInicio = "";
        this.dataTermino = "";
        this.valorProjeto = "";
        this.risco = "";
        this.participantes = "";
    }
    //LEVA PARA A PÁGINA cadastro.php
    cadastrar() {
        window.location.href = "cadastro.php";
    }
    //LEVA PARA A PÁGINA index.php
    voltar_inicio() {
        window.location.href = "index.php";
    }
}

var projeto = new Projeto();