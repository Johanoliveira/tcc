<?php
session_start();
require_once __DIR__ . "/conexao.php";

if (!isset($_SESSION["equipe_id"])) {
    header("Location: ../html/loginequipe.html");
    exit;
}

$sql = "SELECT ac.*, r.data_envio AS data_relato, a.nome_completo, a.curso, a.serie 
        FROM acompanhamentos ac 
        JOIN relatos r ON ac.id_relato = r.id 
        JOIN alunos a ON ac.id_aluno = a.id 
        WHERE ac.status = 'Concluído' 
        ORDER BY ac.data_conclusao DESC";
$lista = $conexao->query($sql)->fetch_all(MYSQLI_ASSOC);
$qtd = count($lista);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - Form Empático</title>
    <link rel="stylesheet" href="../css/relatorios.css">
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
            <a href="relatos.php"><span>▣</span> Relatos dos Estudantes</a>
            <a href="acompanhamento.php"><span>♙</span> Acompanhamentos</a>
            <a href="relatorios.php" class="ativo"><span>▤</span> Relatórios</a>
            <a href="logout.php"><span>⇥</span> Sair</a>
        </nav>
    </aside>

    <main>
        <section class="cabecalho-relatorios">
            <div class="icone-relatorios">▤</div>
            <div class="texto-relatorios">
                <span>HISTÓRICO DE ATENDIMENTOS</span>
                <h1>Relatórios</h1>
                <p>Consulte os registros dos acompanhamentos que já foram analisados e concluídos pela equipe sociopedagógica.</p>
            </div>
        </section>

        <section class="indicadores">
            <div class="indicador">
                <div class="icone indicador-verde">✓</div>
                <div><strong><?= $qtd ?></strong><p>Relatórios concluídos</p></div>
            </div>
            <div class="indicador">
                <div class="icone indicador-amarelo">◷</div>
                <div><strong><?= $qtd ?></strong><p>Atendimentos finalizados</p></div>
            </div>
            <div class="indicador">
                <div class="icone indicador-verde-claro">▤</div>
                <div><strong><?= $qtd ?></strong><p>Registros arquivados</p></div>
            </div>
        </section>

        <section class="area-relatorios">
            <div class="titulo-secao">
                <div class="icone-titulo">▤</div>
                <div>
                    <h2>Relatórios dos atendimentos</h2>
                    <p>Histórico dos acompanhamentos realizados pela equipe</p>
                </div>
            </div>

            <div class="lista-relatorios">
                <?php if (empty($lista)): ?>
                    <article class="card-relatorio"><p>Nenhum relatório concluído ainda.</p></article>
                <?php else: ?>
                    <?php foreach ($lista as $rel):
                        $inicial = strtoupper(substr($rel["nome_completo"], 0, 1));
                    ?>
                    <article class="card-relatorio">
                        <div class="topo-relatorio">
                            <div class="perfil-aluno">
                                <div class="avatar"><?= $inicial ?></div>
                                <div>
                                    <h3><?= htmlspecialchars($rel["nome_completo"]) ?></h3>
                                    <p><?= htmlspecialchars($rel["curso"]) ?> • <?= htmlspecialchars($rel["serie"]) ?>º ano</p>
                                </div>
                            </div>
                            <span class="status concluido">✓ Concluído</span>
                        </div>

                        <div class="informacoes-relatorio">
                            <div><span>Data do relato</span><strong><?= date("d/m/Y", strtotime($rel["data_relato"])) ?></strong></div>
                            <div><span>Início do acompanhamento</span><strong><?= date("d/m/Y", strtotime($rel["data_inicio"])) ?></strong></div>
                            <div><span>Conclusão</span><strong><?= date("d/m/Y", strtotime($rel["data_conclusao"])) ?></strong></div>
                        </div>

                        <div class="resumo-relatorio">
                            <span>Resumo do acompanhamento</span>
                            <p><?= nl2br(htmlspecialchars($rel["situacao_atual"])) ?></p>
                        </div>

                        <div class="resultado">
                            <strong>Resultado do acompanhamento</strong>
                            <p><?= nl2br(htmlspecialchars($rel["resultado_final"])) ?></p>
                        </div>
                    </article>
                    <?php endforeach; ?>
                <?php endif; ?>
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