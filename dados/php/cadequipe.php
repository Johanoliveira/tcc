<?php
session_start();
require_once __DIR__ . "/conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"] ?? "");
    $cargo = trim($_POST["cargo"] ?? "");
    $usuario = trim($_POST["usuario"] ?? "");
    $senha = $_POST["senha"] ?? "";
    $confirmar = $_POST["confirmar_senha"] ?? "";

    if (strlen($senha) !== 6) {
        echo "<script>alert('A senha deve possuir exatamente 6 caracteres.'); history.back();</script>";
        exit;
    }

    if ($senha !== $confirmar) {
        echo "<script>alert('As senhas não coincidem.'); history.back();</script>";
        exit;
    }

    $consulta = $conexao->prepare("SELECT id FROM equipe WHERE usuario = ?");
    $consulta->bind_param("s", $usuario);
    $consulta->execute();
    $consulta->store_result();

    if ($consulta->num_rows > 0) {
        $consulta->close();
        echo "<script>alert('Este nome de usuário já está em uso.'); history.back();</script>";
        exit;
    }
    $consulta->close();

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = $conexao->prepare("INSERT INTO equipe (nome_completo, cargo, usuario, senha) VALUES (?, ?, ?, ?)");
    $sql->bind_param("ssss", $nome, $cargo, $usuario, $senha_hash);

    if ($sql->execute()) {
        $sql->close();
        echo "<script>alert('Integrante cadastrado com sucesso!'); window.location.href='../html/loginequipe.html';</script>";
        exit;
    } else {
        $sql->close();
        echo "<script>alert('Erro ao cadastrar integrante.'); history.back();</script>";
        exit;
    }
}
?>