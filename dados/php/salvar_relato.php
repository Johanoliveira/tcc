<?php
session_start();
require_once __DIR__ . "/conexao.php";

if (!isset($_SESSION["aluno_id"])) {
    echo "<script>alert('Faça login antes de enviar um relato.'); window.location.href='../html/aluno.html';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_aluno = $_SESSION["aluno_id"];
    $texto = trim($_POST["relato"] ?? "");

    if ($texto === "") {
        echo "<script>alert('Escreva seu relato antes de enviar.'); history.back();</script>";
        exit;
    }

    $sql = $conexao->prepare("INSERT INTO relatos (id_aluno, texto_relato, status) VALUES (?, ?, 'Novo relato')");
    $sql->bind_param("is", $id_aluno, $texto);

    if ($sql->execute()) {
        $sql->close();
        header("Location: ../html/enviorelato.html");
        exit;
    } else {
        $sql->close();
        echo "<script>alert('Erro ao enviar relato.'); history.back();</script>";
        exit;
    }
}
?>