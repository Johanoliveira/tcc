<?php
session_start();
require_once __DIR__ . "/conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $bi = trim($_POST["bi"] ?? "");
    $serie = $_POST["serie"] ?? "";
    $curso = $_POST["curso"] ?? "";
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";
    $confirmar = $_POST["confirmar_senha"] ?? "";

    if (
        strlen($senha) !== 6 ||
        !preg_match('/[A-Z]/', $senha) ||
        !preg_match('/[0-9]/', $senha) ||
        !preg_match('/[@#$!]/', $senha)
    ) {
        echo "<script>alert('A senha deve ter exatamente 6 caracteres, com 1 maiúscula, 1 número e 1 símbolo (@#$!).'); history.back();</script>";
        exit;
    }

    if ($senha !== $confirmar) {
        echo "<script>alert('As senhas não coincidem.'); history.back();</script>";
        exit;
    }

    $consulta = $conexao->prepare("SELECT id FROM alunos WHERE bi = ? OR email_institucional = ?");
    $consulta->bind_param("ss", $bi, $email);
    $consulta->execute();
    $consulta->store_result();

    if ($consulta->num_rows > 0) {
        $consulta->close();
        echo "<script>alert('BI ou e-mail institucional já cadastrado.'); history.back();</script>";
        exit;
    }
    $consulta->close();

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = $conexao->prepare(
        "INSERT INTO alunos (nome_completo, bi, serie, curso, email_institucional, senha) VALUES (?, ?, ?, ?, ?, ?)"
    );
    $sql->bind_param("ssssss", $nome, $bi, $serie, $curso, $email, $senha_hash);

    if ($sql->execute()) {
        $sql->close();
        echo "<script>alert('Cadastro realizado com sucesso! Faça seu login.'); window.location.href='../html/aluno.html';</script>";
        exit;
    } else {
        $sql->close();
        echo "<script>alert('Erro ao realizar cadastro.'); history.back();</script>";
        exit;
    }
}
?>