<?php
session_start();
require_once __DIR__ . "/conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $senha = $_POST["senha"] ?? "";

    $sql = $conexao->prepare("SELECT id, nome_completo, curso, serie, senha FROM alunos WHERE email_institucional = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $resultado = $sql->get_result();

    if ($aluno = $resultado->fetch_assoc()) {
        if (password_verify($senha, $aluno["senha"])) {
            $_SESSION["aluno_id"] = $aluno["id"];
            $_SESSION["aluno_nome"] = $aluno["nome_completo"];
            $_SESSION["aluno_curso"] = $aluno["curso"];
            $_SESSION["aluno_serie"] = $aluno["serie"];

            header("Location: areaaluno.php");
            exit;
        }
    }

    echo "<script>alert('E-mail institucional ou senha inválidos.'); window.location.href='../html/aluno.html';</script>";
    exit;
}
?>