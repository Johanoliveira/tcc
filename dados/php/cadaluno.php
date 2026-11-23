
<?php

require_once "../dados/php/conexao.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST["nome"]);
    $bi = trim($_POST["bi"]);
    $serie = $_POST["serie"];
    $curso = $_POST["curso"];
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];
    $confirmar = $_POST["confirmar_senha"];

    if (
        strlen($senha) != 6 ||
        !preg_match('/[A-Z]/', $senha) ||
        !preg_match('/[0-9]/', $senha) ||
        !preg_match('/[@#$!]/', $senha)
    ) {
        $mensagem = "A senha não atende aos requisitos.";

    } elseif ($senha != $confirmar) {

        $mensagem = "As senhas não coincidem.";

    } else {

        $consulta = $conexao->prepare(
            "SELECT id FROM alunos
             WHERE bi = ? OR email_institucional = ?"
        );

        $consulta->bind_param("ss", $bi, $email);
        $consulta->execute();
        $consulta->store_result();

        if ($consulta->num_rows > 0) {

            $mensagem = "BI ou e-mail já cadastrado.";

        } else {

            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            $sql = $conexao->prepare(
                "INSERT INTO alunos
                (nome_completo, bi, serie, curso,
                 email_institucional, senha)
                VALUES (?, ?, ?, ?, ?, ?)"
            );

            $sql->bind_param(
                "ssssss",
                $nome,
                $bi,
                $serie,
                $curso,
                $email,
                $senha_hash
            );

            if ($sql->execute()) {
                $mensagem = "Cadastro realizado com sucesso!";
            } else {
                $mensagem = "Não foi possível realizar o cadastro.";
            }

            $sql->close();
        }

        $consulta->close();
    }
}

?>
