<?php

$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "form_empatico";

$conexao = new mysqli($servidor, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die("Erro na conexão com o banco de dados.");
}

$conexao->set_charset("utf8");

?>