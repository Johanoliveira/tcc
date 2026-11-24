<?php
session_start();
require_once __DIR__ . "/conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST["usuario"] ?? "");
    $senha = $_POST["senha"] ?? "";

    $sql = $conexao->prepare("SELECT id, nome_completo, cargo, senha FROM equipe WHERE usuario = ?");
    $sql->bind_param("s", $usuario);
    $sql->execute();
    $resultado = $sql->get_result();

    if ($membro = $resultado->fetch_assoc()) {
        if (password_verify($senha, $membro["senha"])) {
            $_SESSION["equipe_id"] = $membro["id"];
            $_SESSION["equipe_nome"] = $membro["nome_completo"];
            $_SESSION["equipe_cargo"] = $membro["cargo"];

            header("Location: areaequipe.php");
            exit;
        }
    }

    echo "<script>alert('Usuário ou senha inválidos.'); window.location.href='../html/loginequipe.html';</script>";
    exit;
}
?>