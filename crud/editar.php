<?php
require('db/conexao.php');

//ANTI SQL INJECTION
//$nome_projeto = "Projeto Teste";
//$dataInicio = "19-05-2022";
//$dataTermino = "22-05-2022";
 
//$sql = $pdo->prepare("INSERT INTO projetos VALUES (null,?,?,?)");
//$sql->execute(array($nome_projeto,$dataInicio,$dataTermino));

$erroNome = "";
$erroDataInicio = "";
$erroDataTermino = "";
$erroValorProjeto = "";
$erroRisco = "";
$erroParticipantes = "";
$atualizado = "";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $usuario_id = $_POST['usuario_id'];
        $nome_projeto = $_POST['nome'];
        $data_inicio = $_POST['data-inicio'];
        $data_termino = $_POST['data-termino'];
        $valor_projeto = $_POST['numero'];
        $risco = $_POST['risco'];
        $participantes = $_POST['participantes'];
            
        //VERIFICAR SE O POST NOME ESTÁ VAZIO
        if(empty($_POST['nome'])){
            $erroNome = "Por favor, preencha o nome do projeto";
        }else{
            //PEGAR O VALOR VINDO DO POST E LIMPAR
            $nome = limpaPost($_POST['nome']);
            //VERIFICAR SE TEM SOMENTE LETRAS
            if(!preg_match('/^[a-zA-Z0-9 \p{L}]+$/ui',$nome)){
                $erroNome = "Apenas aceitamos letras e espaços em branco";
            }
        }
        //VERIFICAR SE O POST DATA-INICIO ESTÁ VAZIO
        if($_POST['data-inicio'] == ""){
            $erroDataInicio = "Preencha com a data de início do projeto";
        }
        //VERIFICAR SE O POST DATA-TERMINO ESTÁ VAZIO
        if($_POST['data-termino'] == ""){
            $erroDataTermino = "Preencha com a data de término do projeto";
        }
        //VERIFICAR SE O NUMERO ESTÁ VAZIO
        if(empty($_POST['numero'])){
            $erroValorProjeto = "Por favor, informe o valor do projeto";
        }else{$erroValorProjeto = "";}
        //VERIFICAR SE O POST RISCO ESTÁ VAZIO
        if($_POST['risco']==""){
            $erroRisco = "Por favor, informe o risco associado";
        }
        //VERIFICAR SE O POST PARTICIPANTES ESTÁ VAZIO, SENÃO, SANITIZAR O POST
        if(empty($_POST['participantes'])){
            $erroParticipantes = "Por favor, informe os participantes";
        }else{
            $participantes = limpaPost($_POST['participantes']);
        }
        //CASO TODAS AS ENTRADAS SEJAM VÁLIDAS (SEM ERROS), ATUALIZAR COM OS NOVOS DADOS E VOLTAR PARA A PÁGINA INICIAL
        if(($erroNome=="") && ($erroDataInicio=="") && ($erroDataTermino=="") && ($erroValorProjeto=="") && ($erroRisco=="") && ($erroParticipantes=="")){
            //$sql = $pdo->prepare("INSERT INTO projetos VALUES (null,?,?,?,?,?,?)");
            //$sql->execute(array($_POST['nome'],$_POST['data-inicio'],$_POST['data-termino'],$_POST['numero'],$_POST['risco'],$_POST['participantes']));
            //$sucesso = "validado com sucesso!";
            $query = "UPDATE projetos SET nome_projeto=:nome_projeto, data_inicio=:data_inicio, data_termino=:data_termino, valor_projeto=:valor_projeto, risco=:risco, participantes=:participantes WHERE id=:user_id LIMIT 1";
            $sql = $pdo->prepare($query);

            $data = [
                ':nome_projeto' => $nome_projeto,
                ':data_inicio' => $data_inicio,
                ':data_termino' => $data_termino,
                ':valor_projeto' => $valor_projeto,
                ':risco' => $risco,
                ':participantes' => $participantes,
                ':user_id' => $usuario_id
            ];

            $query_execute = $sql->execute($data);
            header('Location: index.php');
        }
    }
    //FUNÇÃO QUE SANITIZA O POST (ANTI-HTML INJECTION)
    function limpaPost($valor){
        $valor = trim($valor);
        $valor = stripslashes($valor);
        $valor = htmlspecialchars($valor);
        return $valor;
    }
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Projeto</title>
    <link href = "CSS/estilo-editar.css" rel="stylesheet"></link>
    <script src = "JS/script_form.js"></script>
    <!-- IMPORTA O BOOTSTRAP CSS E JS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <!-- IMPORTA A FONTE UTILIZADA NO PROJETO-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab&display=swap" rel="stylesheet"> 
</head>
<body class = "bg-dark">
    <!-- CONTAINER DO FORMULÁRIO PARA EDITAR -->
    <div class="container-cadastro">
        <h1>Editar Projeto</h1>
        <hr>
    <!-- UTILIZA O MÉDODO GET PARA SELECIONAR A LINHA DO BANCO DE DADOS 'projetos' E DISPÕES ESSES DADOS NOS INPUTS -->
        <div class="container-formulario">
            <?php
                $usuario_id = $_GET['id'];
                $query = "SELECT * FROM projetos WHERE id=:user_id LIMIT 1";
                $sql = $pdo->prepare($query);
                $data = ['user_id' => $usuario_id];
                $sql->execute($data);

                $result = $sql->fetch(PDO::FETCH_OBJ);
            ?>
            <form method="POST">
                <input type="hidden" name = "usuario_id" value="<?= $result->id ?>">
                <label for="">Nome do Projeto:</label><br>
                <input type="text" value = "<?= $result->nome_projeto; ?>" <?php if(isset($_POST['nome'])){echo "value='".$_POST['nome']."'";} ?> name="nome" placeholder="Digite seu o nome do projeto" >
                <span class = "erro"><?php echo $erroNome; ?></span>
                <br>
                <label for="">Data de Início:</label><br>
                <input type="date" value = "<?= $result->data_inicio; ?>" <?php if(isset($_POST['data-inicio'])){echo "value='".$_POST['data-inicio']."'";} ?> name="data-inicio" placeholder="Digite a data de início" >
                <span class = "erro"><?php echo $erroDataInicio ?></span>
                <br>
                <label for="">Data de Término:</label><br>
                <input type="date" value = "<?= $result->data_termino; ?>" <?php if(isset($_POST['data-termino'])){echo "value='".$_POST['data-termino']."'";} ?> name="data-termino" placeholder="Digite a data de termino" >
                <span class = "erro"><?php echo $erroDataTermino ?></span>
                <br>
                <label for="">Valor do Projeto:</label><br>
                <input type="number" step=".01" value = "<?= $result->valor_projeto; ?>" <?php if(isset($_POST['numero'])){echo "value='".$_POST['numero']."'";} ?> min="0" name="numero" placeholder="Valor do projeto em R$" >
                <span class = "erro"><?php echo $erroValorProjeto ?></span>
                <br>
                <label for="">Risco:</label><br>
                <input type="number" value = "<?= $result->risco; ?>" <?php if(isset($_POST['risco'])){echo "value='".$_POST['risco']."'";} ?> min="0" max="2" name="risco" placeholder="0 - baixo, 1 - médio, 2 - alto" >
                <span class = "erro"><?php echo $erroRisco ?></span>
                <label for="">Participantes:</label><br>
                <input type="text" value = "<?= $result->participantes; ?>" <?php if(isset($_POST['participantes'])){echo "value='".$_POST['participantes']."'";} ?> name="participantes" placeholder="Participante 1; Participante 2;..." >
                <span class = "erro"><?php echo $erroParticipantes ?></span>
                <div class="container-submit"> <!-- BOTÃO PARA SUBMETER OS DADOS EDITADOS -->
                    <button type="submit" class = "btn btn-success" name = "atualizar_projeto_botao">Atualizar Cadastro</button>
                </div>
                <span></span>
            </form>
            <div class = "container-voltar"> <!-- BOTÃO PARA VOLTAR -->
                <button class = "btn btn-warning" onclick = "projeto.voltar_inicio()" >Voltar</button> 
            </div>
        </div>
    </div>
</body>
</html>