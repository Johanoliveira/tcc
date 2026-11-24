<?php
session_start();
require_once __DIR__ . "/conexao.php";

if (!isset($_SESSION["aluno_id"])) {
    header("Location: ../html/aluno.html");
    exit;
}

$id_aluno = $_SESSION["aluno_id"];
$sql = $conexao->prepare("SELECT * FROM relatos WHERE id_aluno = ? ORDER BY data_envio DESC");
$sql->bind_param("i", $id_aluno);
$sql->execute();
$relatos = $sql->get_result()->fetch_all(MYSQLI_ASSOC);
$sql->close();

$tem_notificacao = false;
foreach ($relatos as $r) {
    if ($r["atendimento_presencial"] == 1) {
        $tem_notificacao = true;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Relatos - Form Empático</title>
    <link rel="stylesheet" href="../css/meusrelatos.css">
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
            <a href="areaaluno.php">Início</a>
        </nav>
    </header>

    <main>
        <section class="topo">
            <div>
                <span class="etiqueta">📋 SEU HISTÓRICO</span>
                <h1>Meus relatos</h1>
                <p>Aqui você pode acompanhar os relatos que já enviou e as atualizações realizadas pela Equipe Sociopedagógica.</p>
            </div>
            <div class="icone-topo">📋</div>
        </section>

        <?php if ($tem_notificacao): ?>
        <section class="notificacao">
            <div class="icone-notificacao">🔔</div>
            <div class="texto-notificacao">
                <span>NOVA ATUALIZAÇÃO</span>
                <h2>Seu relato foi analisado 💚</h2>
                <p>A Equipe Sociopedagógica analisou o relato que você enviou e gostaria de conversar com você pessoalmente.</p>
                <div class="consulta">
                    <strong>🏫 Atendimento presencial solicitado</strong>
                    <p>Procure a Equipe Sociopedagógica para combinar o melhor momento para a conversa.</p>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <section class="titulo-secao">
            <span>ACOMPANHE SEUS ENVIOS</span>
            <h2>Histórico dos meus relatos</h2>
        </section>

        <section class="lista-relatos">
            <?php if (empty($relatos)): ?>
                <article class="relato-card">
                    <div class="resumo">
                        <p>Você ainda não enviou nenhum relato.</p>
                    </div>
                </article>
            <?php else: ?>
                <?php foreach ($relatos as $relato):
                    $classe_status = "processo";
                    $icone_status = "⏳";
                    if ($relato["status"] === "Analisado") {
                        $classe_status = "analisado";
                        $icone_status = "✓";
                    } elseif ($relato["status"] === "Concluído") {
                        $classe_status = "concluido";
                        $icone_status = "✓";
                    }
                ?>
                <article class="relato-card">
                    <div class="cabecalho-relato">
                        <div class="icone-relato">💬</div>
                        <div>
                            <span class="data"><?= date("d/m/Y \à\s H:i", strtotime($relato["data_envio"])) ?></span>
                            <h3>Relato enviado</h3>
                        </div>
                        <span class="status <?= $classe_status ?>">
                            <?= $icone_status ?> <?= htmlspecialchars($relato["status"]) ?>
                        </span>
                    </div>

                    <div class="resumo">
                        <p><?= nl2br(htmlspecialchars($relato["texto_relato"])) ?></p>
                    </div>

                    <?php if (!empty($relato["atualizacao_equipe"])): ?>
                    <div class="resultado">
                        <span>ATUALIZAÇÃO DA EQUIPE</span>
                        <p><?= nl2br(htmlspecialchars($relato["atualizacao_equipe"])) ?></p>
                        <?php if ($relato["atendimento_presencial"] == 1): ?>
                            <strong>🏫 Atendimento presencial solicitado</strong>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <section class="novo-relato">
            <div class="icone-novo">📝</div>
            <div>
                <h2>Quer compartilhar algo novo?</h2>
                <p>Você pode fazer um novo relato sempre que sentir necessidade de conversar ou compartilhar alguma situação.</p>
            </div>
            <a href="../html/relatoaluno.html">Fazer novo relato →</a>
        </section>
    </main>

    <footer>
        <strong>Form Empático — Instituto Federal</strong>
        <span>Um espaço de escuta, acolhimento e apoio.</span>
    </footer>
</body>
</html>