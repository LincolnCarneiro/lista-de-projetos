<?php 
//CONFIG GERAL
$servidor="localhost";
$usuario="root";
$senha="";
$banco="banco_formulario";

//CONEXÃO COM O SQL
$pdo = new PDO("mysql:host=$servidor;dbname=$banco",$usuario,$senha);
?>