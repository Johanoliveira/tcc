<?php
session_start();
require_once __DIR__ . "/conexao.php";

if (!isset($_SESSION["equipe_id"])) {
    header("Location: ../html/loginequipe.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["iniciar_relato_id"])) {
    $id_relato = (int)$_POST["iniciar_relato_id"];
    $id_aluno = (int)$_POST["id_aluno"];
    $id_equipe = $_SESSION["equipe_id"];
    $hoje = date("Y-m-d");

    $upd = $conexao->prepare("UPDATE relatos SET status = 'Analisado', atualizacao_equipe = 'A equipe iniciou o acompanhamento do seu relato e gostaria de conversar com você.', atendimento_presencial = 1 WHERE id = ?");
    $upd->bind_param("i", $id_relato);
    $upd->execute();
    $upd->close();

    $check = $conexao->prepare("SELECT id FROM acompanhamentos WHERE id_relato = ?");
    $check->bind_param("i", $id_relato);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        $situacao = "Acompanhamento iniciado a partir do relato enviado pelo estudante.";
        $ins = $conexao->prepare("INSERT INTO acompanhamentos (id_relato, id_aluno, id_equipe, status, situacao_atual, data_inicio, ultimo_atendimento) VALUES (?, ?, ?, 'Em andamento', ?, ?, ?)");
        $ins->bind_param("iiisss", $id_relato, $id_aluno, $id_equipe, $situacao, $hoje, $hoje);
        $ins->execute();
        $ins->close();
    }
    $check->close();

    header("Location: acompanhamento.php");
    exit;
}

$sql = "SELECT r.*, a.nome_completo, a.curso, a.serie 
        FROM relatos r 
        JOIN alunos a ON r.id_aluno = a.id 
        ORDER BY r.data_envio DESC";
$lista = $conexao->query($sql)->fetch_all(MYSQLI_ASSOC);

$total = count($lista);
$aguardando = 0;
$analisados = 0;
foreach ($lista as $item) {
    if ($item["status"] === "Novo relato") $aguardando++;
    else $analisados++;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatos dos Estudantes - Form Empático</title>
    <link rel="stylesheet" href="../css/relatos.css">
</head>
<body>
<header>
    <div class="logo"><img src="../img/if.png" alt="Logo Instituto Federal"></div>
    <div class="titulo">
        <h1>Form Empático</h1>
        <p>Acompanhamento e Apoio Sociopedagógico</p>
    </div>
</header>

<div class="estrutura">
    <aside class="menu-lateral">
        <div class="perfil-menu">
            <div class="icone-perfil">👩‍🏫</div>
            <div>
                <h2>Equipe<br>Sociopedagógica</h2>
                <p><?= htmlspecialchars($_SESSION["equipe_nome"]) ?></p>
            </div>
        </div>
        <div class="linha"></div>
        <nav class="menu">
            <a href="areaequipe.php"><span>⌂</span> Painel Principal</a>
            <a href="relatos.php" class="ativo"><span>▣</span> Relatos dos Estudantes</a>
            <a href="acompanhamento.php"><span>♙</span> Acompanhamentos</a>
            <a href="relatorios.php"><span>▤</span> Relatórios</a>
            <a href="logout.php"><span>⇥</span> Sair</a>
        </nav>
    </aside>

    <main>
        <section class="cabecalho-relatos">
            <div class="icone-relatos">▣</div>
            <div>
                <span class="etiqueta">ACOMPANHAMENTO</span>
                <h1>Relatos dos Estudantes</h1>
                <p>Visualize os relatos enviados pelos estudantes, analise as situações apresentadas e inicie o acompanhamento quando necessário.</p>
            </div>
        </section>

        <section class="resumo-relatos">
            <div class="resumo-card">
                <div class="icone-resumo">✉</div>
                <div><strong><?= $total ?></strong><p>Relatos recebidos</p></div>
            </div>
            <div class="resumo-card">
                <div class="icone-resumo amarelo">◷</div>
                <div><strong><?= $aguardando ?></strong><p>Aguardando análise</p></div>
            </div>
            <div class="resumo-card">
                <div class="icone-resumo verde">✓</div>
                <div><strong><?= $analisados ?></strong><p>Já analisados</p></div>
            </div>
        </section>

        <section class="lista-relatos">
            <div class="titulo-secao">
                <div>
                    <h2>Relatos recebidos</h2>
                    <p>Confira os relatos enviados pelos estudantes.</p>
                </div>
            </div>

            <?php if (empty($lista)): ?>
                <article class="relato"><p>Nenhum relato recebido até o momento.</p></article>
            <?php else: ?>
                <?php foreach ($lista as $r):
                    $inicial = strtoupper(substr($r["nome_completo"], 0, 1));
                    $is_novo = ($r["status"] === "Novo relato");
                ?>
                <article class="relato">
                    <div class="topo-relato">
                        <div class="perfil-estudante">
                            <div class="avatar"><?= $inicial ?></div>
                            <div>
                                <h3><?= htmlspecialchars($r["nome_completo"]) ?></h3>
                                <p><?= htmlspecialchars($r["curso"]) ?> • <?= htmlspecialchars($r["serie"]) ?>º ano</p>
                            </div>
                        </div>
                        <span class="status <?= $is_novo ? 'novo' : 'analisado' ?>">
                            <?= htmlspecialchars($r["status"]) ?>
                        </span>
                    </div>

                    <div class="informacoes">
                        <div>
                            <span>Data do relato</span>
                            <strong><?= date("d/m/Y", strtotime($r["data_envio"])) ?></strong>
                        </div>
                        <div>
                            <span>Situação</span>
                            <strong><?= $is_novo ? "Aguardando análise" : "Em acompanhamento" ?></strong>
                        </div>
                    </div>

                    <div class="texto-relato">
                        <span>Relato do estudante</span>
                        <p><?= nl2br(htmlspecialchars($r["texto_relato"])) ?></p>
                    </div>

                    <div class="acoes">
                        <?php if ($is_novo): ?>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="iniciar_relato_id" value="<?= $r["id"] ?>">
                                <input type="hidden" name="id_aluno" value="<?= $r["id_aluno"] ?>">
                                <button type="submit" class="acompanhar">Iniciar acompanhamento</button>
                            </form>
                        <?php else: ?>
                            <a href="acompanhamento.php"><button type="button" class="acompanhar">Ver acompanhamento</button></a>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>
</div>

<footer>
    <p>Instituto Federal de Educação, Ciência e Tecnologia</p>
    <span>Juntos pelo bem-estar<br>dos nossos estudantes</span>
</footer>
</body>
</html>