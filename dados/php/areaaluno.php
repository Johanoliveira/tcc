<?php
session_start();
if (!isset($_SESSION["aluno_id"])) {
    header("Location: ../html/aluno.html");
    exit;
}
$primeiro_nome = explode(" ", $_SESSION["aluno_nome"])[0];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Aluno - Form Empático</title>
    <link rel="stylesheet" href="../css/areaaluno.css">
</head>
<body>
    <header>
        <div class="logo-area">
            <img src="../img/if.png" alt="Instituto Federal">
            <div class="titulo">
                <strong>Instituto Federal</strong>
                <span>Form Empático</span>
            </div>
        </div>
        <nav>
            <a href="logout.php" class="sair">Sair</a>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-texto">
                <span class="mini-titulo">🌱 SEU ESPAÇO DE ESCUTA</span>
                <h1>Olá, <span><?= htmlspecialchars($primeiro_nome) ?></span>!</h1>
                <h2>Como você está se sentindo hoje?</h2>
                <p>
                    Este espaço foi criado para ouvir você.
                    Aqui, você pode compartilhar o que está acontecendo
                    na sua rotina e contar como realmente está.
                </p>
                <div class="botoes-hero">
                    <a href="../html/relatoaluno.html" class="botao-principal">
                        <span>📝</span>
                        Quero fazer um relato
                    </a>
                    <a href="meusrelatos.php" class="botao-secundario">
                        Ver meus relatos
                    </a>
                </div>
            </div>
            <div class="hero-ilustracao">
                <div class="circulo-fundo"></div>
                <div class="coracao">💚</div>
                <div class="fala fala-1">💬</div>
                <div class="fala fala-2">🌿</div>
            </div>
        </section>

        <section class="acolhimento">
            <div class="icone-acolhimento">💬</div>
            <div class="texto-acolhimento">
                <span>UM ESPAÇO PARA VOCÊ</span>
                <h2>Aqui você pode falar sem medo.</h2>
                <p>
                    Não existe resposta certa ou errada.
                    Você pode contar sobre os estudos, a escola,
                    sua rotina ou qualquer outra situação que esteja
                    fazendo parte do seu momento.
                </p>
                <strong>Conte apenas aquilo que você se sentir confortável em compartilhar. 💚</strong>
            </div>
        </section>

        <section class="frase-final">
            <div class="aspas">“</div>
            <h2>Sua voz importa.</h2>
            <p>Falar sobre o que estamos vivendo também é uma forma de cuidar de nós mesmos.</p>
            <span>💚 Estamos aqui para ouvir você.</span>
        </section>
    </main>

    <footer>
        <strong>Form Empático — Instituto Federal</strong>
        <span>Um espaço de escuta, acolhimento e apoio.</span>
    </footer>
</body>
</html>