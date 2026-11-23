
<?php

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "banco";

$conexão = new mysqli($host, $usuario, $senha, $banco);

if ($conexão->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conexão->connect_error);
}

$conexão->set_charset("utf8mb4");

?>
