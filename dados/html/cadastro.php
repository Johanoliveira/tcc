<?php

include "../php/conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = $_POST["nome"];
    $curso = $_POST["curso"];
    $turma = $_POST["turma"];
    $bi = $_POST["bi"];

    $sql = "INSERT INTO alunos (nome, curso, turma, bi)
            VALUES ('$nome', '$curso', '$turma', '$bi')";

    if ($conexao->query($sql) === TRUE) {
        $mensagem = "Cadastro realizado com sucesso!";
    } else {
        $mensagem = "Erro ao cadastrar.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro do Aluno - Form Empático</title>

    <link rel="stylesheet" href="../css/cadastro.css">

</head>

<body>

<header>

    <div class="logo">
        <img src="../img/if.png" alt="Logo Instituto Federal">
    </div>

    <div class="titulo">
        <h1>Form Empático</h1>
        <p>Acompanhamento e Apoio Sociopedagógico</p>
    </div>

</header>


<main>

    <section class="cadastro">

        <div class="texto">

            <h1>Cadastro do Aluno</h1>

            <p>
                Preencha seus dados para acessar o Form Empático.
            </p>

        </div>


        <div class="formulario">

            <form method="POST">

                <label for="nome">Nome completo</label>

                <input
                    type="text"
                    id="nome"
                    name="nome"
                    placeholder="Digite seu nome completo"
                    required
                >


                <label for="curso">Curso</label>

                <input
                    type="text"
                    id="curso"
                    name="curso"
                    placeholder="Ex.: Técnico em Informática"
                    required
                >


                <label for="turma">Turma</label>

                <input
                    type="text"
                    id="turma"
                    name="turma"
                    placeholder="Ex.: 3º Ano A"
                    required
                >


                <label for="bi">BI</label>

                <input
                    type="text"
                    id="bi"
                    name="bi"
                    placeholder="Digite seu BI"
                    required
                >


                <button type="submit">
                    Cadastrar
                </button>

            </form>

        </div>


        <?php if (isset($mensagem)) { ?>

            <p class="mensagem">
                <?php echo $mensagem; ?>
            </p>

        <?php } ?>

    </section>

</main>


<footer>

    <p>
        Instituto Federal de Educação, Ciência e Tecnologia
    </p>

</footer>

</body>

</html>