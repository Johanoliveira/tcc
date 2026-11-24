<?php
session_start();
require_once __DIR__ . "/conexao.php";

if (!isset($_SESSION["equipe_id"])) {
    header("Location: ../html/loginequipe.html");
    exit;
}

$total_relatos = $conexao->query("SELECT COUNT(*) AS total FROM relatos")->fetch_assoc()["total"];
$total_andamento = $conexao->query("SELECT COUNT(*) AS total FROM acompanhamentos WHERE status = 'Em andamento'")->fetch_assoc()["total"];
$total_concluidos = $conexao->query("SELECT COUNT(*) AS total FROM acompanhamentos WHERE status = 'Concluído'")->fetch_assoc()["total"];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área da Equipe - Form Empático</title>
    <link rel="stylesheet" href="../css/areaequipe.css">
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
            <a href="areaequipe.php" class="ativo"><span>⌂</span> Painel Principal</a>
            <a href="relatos.php"><span>▣</span> Relatos dos Estudantes</a>
            <a href="acompanhamento.php"><span>♙</span> Acompanhamentos</a>
            <a href="relatorios.php"><span>▤</span> Relatórios</a>
            <a href="logout.php"><span>⇥</span> Sair</a>
        </nav>
    </aside>

    <main>
        <section class="cabecalho-equipe">
            <div class="icone-equipe">👩‍🏫</div>
            <div class="texto-equipe">
                <span>ESPAÇO DE ACOLHIMENTO</span>
                <h1>Bem-vindo(a), <?= htmlspecialchars($_SESSION["equipe_nome"]) ?>!</h1>
                <p>
                    Este é o espaço destinado ao acompanhamento e apoio dos estudantes.
                    Aqui você poderá visualizar os relatos enviados, acompanhar os atendimentos
                    em andamento e consultar os registros já analisados.
                </p>
            </div>
        </section>

        <section class="indicadores">
            <div class="indicador">
                <div class="icone indicador-verde">✉</div>
                <div>
                    <strong><?= $total_relatos ?></strong>
                    <p>Relatos recebidos</p>
                </div>
            </div>
            <div class="indicador">
                <div class="icone indicador-amarelo">◷</div>
                <div>
                    <strong><?= $total_andamento ?></strong>
                    <p>Acompanhamentos em andamento</p>
                </div>
            </div>
            <div class="indicador">
                <div class="icone indicador-verde-claro">✓</div>
                <div>
                    <strong><?= $total_concluidos ?></strong>
                    <p>Relatórios concluídos</p>
                </div>
            </div>
        </section>

        <section class="painel-informacao">
            <div class="titulo-painel">
                <div class="icone-titulo">💚</div>
                <div>
                    <h2>Sobre o espaço da equipe</h2>
                    <p>Organização e acompanhamento dos estudantes</p>
                </div>
            </div>
            <p class="texto-painel">
                O Form Empático foi desenvolvido para facilitar a comunicação
                entre os estudantes e a equipe sociopedagógica. Por meio dos
                relatos enviados, a equipe poderá analisar cada situação,
                realizar o acompanhamento necessário e manter os registros organizados.
            </p>
        </section>

        <section class="acessos">
            <div class="titulo-painel">
                <div class="icone-titulo">▤</div>
                <div>
                    <h2>Acesso rápido</h2>
                    <p>Acesse as principais áreas do sistema</p>
                </div>
            </div>
            <div class="cards-acesso">
                <a href="relatos.php" class="card-acesso">
                    <div class="icone-card">▣</div>
                    <div>
                        <h3>Relatos dos Estudantes</h3>
                        <p>Visualize os relatos enviados pelos estudantes e realize a análise inicial.</p>
                    </div>
                    <span class="seta">›</span>
                </a>
                <a href="acompanhamento.php" class="card-acesso">
                    <div class="icone-card">♙</div>
                    <div>
                        <h3>Acompanhamentos</h3>
                        <p>Consulte os estudantes que estão sendo acompanhados pela equipe.</p>
                    </div>
                    <span class="seta">›</span>
                </a>
                <a href="relatorios.php" class="card-acesso">
                    <div class="icone-card">▤</div>
                    <div>
                        <h3>Relatórios</h3>
                        <p>Consulte os atendimentos que já foram analisados e concluídos.</p>
                    </div>
                    <span class="seta">›</span>
                </a>
            </div>
        </section>

        <section class="mensagem-equipe">
            <div class="icone-mensagem">💚</div>
            <div>
                <h2>Um espaço para acolher e acompanhar</h2>
                <p>Cada relato representa uma oportunidade de ouvir, compreender e oferecer o apoio necessário aos estudantes.</p>
            </div>
        </section>
    </main>
</div>

<footer>
    <p>Instituto Federal de Educação, Ciência e Tecnologia</p>
    <span>Juntos pelo bem-estar<br>dos nossos estudantes</span>
</footer>
</body>
</html>