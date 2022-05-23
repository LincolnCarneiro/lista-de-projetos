<?php
//conexão com o banco de dados SQL PDO
    require('db/conexao.php');
//SE O BOTÃO É SOLICITADO PELO MÉTODO POST, TENTA EXCLUIR A LINHA DA TABELA COM SEU RESPECTIVO ID. SENÃO, GERA UM ERRO
    if(isset($_POST['excluir-projeto'])){
        $usuario_id = $_POST['excluir-projeto'];
        try{
            $query = "DELETE FROM projetos WHERE id=:user_id";
            $sql = $pdo->prepare($query);
            $data = [
                ':user_id' => $usuario_id
            ];
            $sql->execute($data); 
        }catch(PDOException $e){
            echo $e->getMessage();
        }
//VOLTA PARA A PÁGINA INICIAL
        header('Location: index.php');
    }

?>