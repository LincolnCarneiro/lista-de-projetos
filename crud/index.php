<?php
//conexão com o banco de dados SQL PDO
    require('db/conexao.php');
//SELEÇÃO DE TODOS OS DADOS DO BANCO 'projetos'
    $query="SELECT * FROM projetos";
    $sql = $pdo->prepare($query);
    $sql->execute();
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos os Projetos</title>
    <link href="CSS/estiloIndex.css" rel="stylesheet"></link>
    <script src = "JS/script_form.js"></script>
<!-- IMPORTA O BOORSTRAP CSS E JS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<!-- IMPORTA A FONTE UTILIZADA NO PROJETO-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab&display=swap" rel="stylesheet"> 
<!-- IMPORTA O JQUERY PARA O MODAL FUNCIONAR -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
</head>
<body>
<!--IMPORTA O JS COMPACTO DO BOOTSTRAP-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!--CABEÇALHO-->
    <header>
        <h1>TABELA DE PROJETOS</h1>
    </header>
<!--PRINCIPAL-->
    <main class="bg-light">
        <span id = "validar-excluido"></span>
        <div class = "title">
            <h2> Lista de Projetos </h2>
            <hr>
        </div>
<!--BOTÃO QUE LEVA ATÉ A PÁGINA DE CADASTRO POR MEIO DO REDIRECIONAMENTO window.location.href DA FUNÇÃO projeto.cadastrar-->
        <button onclick = "projeto.cadastrar()" class="btn btn-success btn-lg">Cadastrar</button>
        <div class = "content">
<!--TABELA DINÂMICA MOSTRANDO TODOS OS DADOS CONTIDOS NO BANCO DE DADOS 'projetos' DO SQL -->
            <table border="1" class="table table-striped table-dark table-hover table-bordered">
                <thead>
                    <th>Id</th>
                    <th>Nome do Projeto</th>
                    <th>Data de Início</th>
                    <th>Data de Término</th>
                    <th>Valor do Projeto</th>
                    <th>Risco</th>
                    <th>Participantes</th>
                    <th>Editar</th>
                    <th>Excluir</th>
                    <th>Simular</th>
                <tbody>
                    <!-- TRANSFORMA AS LINHAS DO BANCO DE DADOS 'projetos' EM OBJETOS -->
                    <?php
                        $sql->setFetchMode(PDO::FETCH_OBJ);
                        $result = $sql->fetchAll();
                        //SE HOUVER ALGUM DADO, DISPÕE ELES NA TABELA
                        if($result){
                            foreach($result as $row){
                                ?>
                                <tr>
                                    <td><?= $row->id; ?></td>
                                    <td><?= $row->nome_projeto; ?></td>
                                    <td><?= $row->data_inicio; ?></td>
                                    <td><?= $row->data_termino; ?></td>
                                    <td><?= $row->valor_projeto; ?></td>
                                    <td><?= $row->risco; ?></td>
                                    <td><?= $row->participantes; ?></td>
                                    <td>
                                        <a href="editar.php?id=<?= $row->id; ?>" class="btn btn-primary">Editar</a> <!-- BOTÃO REDIRECIONAR PARA editar.php -->
                                    </td>
                                    <td>
                                        <form action="excluir.php" method="POST"> <!-- BOTÃO QUE REDIRECIONA PARA excluir.php COM O ID DA LINHA A SER EXCLUIDA  -->
                                            <button type = "submit" name = "excluir-projeto" value="<?=$row->id;?>" class="btn btn-danger">Excluir</button> 
                                        </form>
                                    </td>
                                    <td>    <!-- BOTÃO QUE ABRE O MODAL  -->
                                        <button type = "button" name = "simular-projeto" value="<?=$row->id;?>" data-toggle="modal" data-target="#exampleModal" data-risco = "<?= $row->risco; ?>" data-whatever="<?= $row->valor_projeto; ?>" class="btn btn-warning">Simular</button>                    
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        //SE NÃO, DISPÕE UMA TABELA LIMPA CONTENDO 'Nenhum dado foi encontrado'
                        else
                        {
                            ?>
                            <tr>
                                <td colspan="10">Nenhum dado foi encontrado.</td>
                            </tr>
                            <?php
                        }
                    ?>
                </tbody>
                </thead>
            </table>
            <hr>
        </div>
    </main>
<!-- CONTAINER CONTENDO O MODAL PARA SE REALIZAR O CÁLCULO DO RETORNO DO PROJETO E INSERIR O VALOR DE INVESTIMENTO -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Simular Projeto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Insira o investimento:</label>
                            <input type="number" class="form-control" id="recipient-name" placeholder="Insira o valor do investimento">
                        </div>
                    </form>
                    <br>
                    <p>Valor do Projeto: <span id = "span-valor-projeto"></span></p>
                    <p>Retorno do Investimento: <span id = "retorna-valor"></span></p>
                </div>
                <div class="modal-footer">
                    <button id = "botao-sair" type="button" class="btn btn-secondary" data-dismiss="modal">Sair</button>
                    <button id="botao-calcular" type="button" class="btn btn-primary">Calcular</button>
                </div>
            </div>
        </div>
    </div>
<!-- SCRIPT DO MODAL. FUNÇÕES:
 - EXTRAIR OS DADOS RISCO E VALOR_PROJETO DA LINHA SELECIONADA PARA SIMULAR
 - CALCULAR O VALOR DO RETORNO DO INVESTIMENTO
 - LIMPAR O INPUT QUANDO É ACIONADO O BOTÃO 'SAIR'
 - GERENCIAR O SPAN DE ERRO OU RETORNO DO CÁLCULO
-->
<script>
    $('#exampleModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // BOTÃO QUE ACIONOU O MODAL
        var recipient = button.data('whatever')
        var risco = button.data('risco') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)

        document.querySelector('#span-valor-projeto').innerHTML = recipient;
        //CALCULA O RESULTADO DO INVESTIMENTO E RETORNA UM SPAN COM O VALOR DO RETORNO OU UM SPAN COM O ERRO DE ENTRADA NO MODAL
        retornoInvestimento = document.querySelector("#retorna-valor")
        retornoInvestimento.innerHTML = "";
        document.getElementById("botao-calcular").onclick = function() {
            valorInput = document.querySelector("#recipient-name").value
            if(valorInput == ""){
                retornoInvestimento.style.color = 'red';
                retornoInvestimento.innerHTML = "por favor, insira uma entrada válida.";
            }else{
                if(isNaN(valorInput)){
                    retornoInvestimento.innerHTML = "Erro, seu valor não é um número.";
                    retornoInvestimento.style.color = 'red';
                }else{
                    if(valorInput < parseFloat(recipient)){
                        retornoInvestimento.style.color = 'red';
                        retornoInvestimento.innerHTML = "Erro, o valor do investimento é menor que o do projeto.";
                    }else{
                        switch(risco){
                            case 0:
                                retornoInvestimento.style.color = 'green';
                                retornoInvestimento.innerHTML = valorInput*0.05;
                            break
                            case 1:
                                retornoInvestimento.style.color = 'green';
                                retornoInvestimento.innerHTML = valorInput*0.1;
                                break
                            case 2:
                                retornoInvestimento.style.color = 'green';
                                retornoInvestimento.innerHTML = valorInput*0.2;
                                break
                            default:
                                retornoInvestimento.innerHTML = "Erro, aconteceu algum problema no risco!";
                                retornoInvestimento.style.color = 'red';
                        }
                    }
                }
            }

        };
        //QUANDO CLICA NO BOTÃO 'Sair' LIMPA O VALOR DO INPUT DO MODAL
        document.getElementById("botao-sair").onclick = function(){
        document.querySelector("#recipient-name").value = "";
        }
    })
</script>

</body>
</html>