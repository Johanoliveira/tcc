<?php
session_start();
require_once __DIR__ . "/conexao.php";

if (!isset($_SESSION["equipe_id"])) {
    header("Location: ../html/loginequipe.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["acao_acomp"])) {
    $id_acomp = (int)$_POST["id_acomp"];
    $id_relato = (int)$_POST["id_relato"];
    $situacao = trim($_POST["situacao_atual"] ?? "");
    $proximo = !empty($_POST["proximo_atendimento"]) ? $_POST["proximo_atendimento"] : null;
    $hoje = date("Y-m-d");

    if ($_POST["acao_acomp"] === "concluir") {
        $resultado = $situacao ?: "Acompanhamento finalizado pela equipe sociopedagógica.";
        $upd = $conexao->prepare("UPDATE acompanhamentos SET status = 'Concluído', situacao_atual = ?, resultado_final = ?, ultimo_atendimento = ?, data_conclusao = ? WHERE id = ?");
        $upd->bind_param("ssssi", $situacao, $resultado, $hoje, $hoje, $id_acomp);
        $upd->execute();
        $upd->close();

        $updRel = $conexao->prepare("UPDATE relatos SET status = 'Concluído', atualizacao_equipe = ? WHERE id = ?");
        $updRel->bind_param("si", $resultado, $id_relato);
        $updRel->execute();
        $updRel->close();

        header("Location: relatorios.php");
        exit;
    } else {
        $upd = $conexao->prepare("UPDATE acompanhamentos SET situacao_atual = ?, ultimo_atendimento = ?, proximo_atendimento = ? WHERE id = ?");
        $upd->bind_param("sssi", $situacao, $hoje, $proximo, $id_acomp);
        $upd->execute();
        $upd->close();

        header("Location: acompanhamento.php");
        exit;
    }
}

$sql = "SELECT ac.*, a.nome_completo, a.curso, a.serie 
        FROM acompanhamentos ac 
        JOIN alunos a ON ac.id_aluno = a.id 
        WHERE ac.status = 'Em andamento' 
        ORDER BY ac.data_inicio DESC";
$lista = $conexao->query($sql)->fetch_all(MYSQLI_ASSOC);
$qtd = count($lista);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acompanhamentos - Form Empático</title>
    <link rel="stylesheet" href="../css/acompanhamento.css">
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
            <a href="acompanhamento.php" class="ativo"><span>♙</span> Acompanhamentos</a>
            <a href="relatorios.php"><span>▤</span> Relatórios</a>
            <a href="logout.php"><span>⇥</span> Sair</a>
        </nav>
    </aside>

    <main>
        <section class="cabecalho-acompanhamentos">
            <div class="icone-acompanhamentos">♙</div>
            <div>
                <span class="etiqueta">ACOMPANHAMENTO</span>
                <h1>Acompanhamentos</h1>
                <p>Acompanhe os estudantes que estão recebendo atendimento da equipe sociopedagógica e registre o andamento de cada situação.</p>
            </div>
        </section>

        <section class="indicadores">
            <div class="indicador">
                <div class="icone-indicador verde">♙</div>
                <div><strong><?= $qtd ?></strong><p>Em acompanhamento</p></div>
            </div>
            <div class="indicador">
                <div class="icone-indicador amarelo">◷</div>
                <div><strong><?= $qtd ?></strong><p>Atendimentos ativos</p></div>
            </div>
            <div class="indicador">
                <div class="icone-indicador azul">✓</div>
                <div><strong><?= $qtd ?></strong><p>Acompanhamentos atualizados</p></div>
            </div>
        </section>

        <section class="lista-acompanhamentos">
            <div class="titulo-secao">
                <h2>Estudantes em acompanhamento</h2>
                <p>Consulte as situações que estão sendo acompanhadas pela equipe sociopedagógica.</p>
            </div>

            <?php if (empty($lista)): ?>
                <article class="acompanhamento"><p>Nenhum estudante em acompanhamento ativo no momento.</p></article>
            <?php else: ?>
                <?php foreach ($lista as $ac):
                    $inicial = strtoupper(substr($ac["nome_completo"], 0, 1));
                ?>
                <article class="acompanhamento">
                    <div class="topo-acompanhamento">
                        <div class="perfil-estudante">
                            <div class="avatar"><?= $inicial ?></div>
                            <div>
                                <h3><?= htmlspecialchars($ac["nome_completo"]) ?></h3>
                                <p><?= htmlspecialchars($ac["curso"]) ?> • <?= htmlspecialchars($ac["serie"]) ?>º ano</p>
                            </div>
                        </div>
                        <span class="status andamento"><?= htmlspecialchars($ac["status"]) ?></span>
                    </div>

                    <div class="dados-acompanhamento">
                        <div><span>Início do acompanhamento</span><strong><?= date("d/m/Y", strtotime($ac["data_inicio"])) ?></strong></div>
                        <div><span>Último atendimento</span><strong><?= $ac["ultimo_atendimento"] ? date("d/m/Y", strtotime($ac["ultimo_atendimento"])) : "-" ?></strong></div>
                        <div><span>Próximo atendimento</span><strong><?= $ac["proximo_atendimento"] ? date("d/m/Y", strtotime($ac["proximo_atendimento"])) : "A definir" ?></strong></div>
                    </div>

                    <form method="post">
                        <input type="hidden" name="id_acomp" value="<?= $ac["id"] ?>">
                        <input type="hidden" name="id_relato" value="<?= $ac["id_relato"] ?>">

                        <div class="situacao">
                            <span>Situação atual / Registro do atendimento</span>
                            <textarea name="situacao_atual" rows="3" style="width:100%; margin-top:8px; padding:12px; border-radius:10px; border:1px solid #d5e3d1;"><?= htmlspecialchars($ac["situacao_atual"]) ?></textarea>
                        </div>

                        <div style="margin-top:12px;">
                            <label style="font-size:12px; font-weight:bold; color:#315b45;">Próximo atendimento: </label>
                            <input type="date" name="proximo_atendimento" value="<?= htmlspecialchars($ac["proximo_atendimento"] ?? "") ?>" style="padding:6px 10px; border-radius:8px; border:1px solid #d5e3d1;">
                        </div>

                        <div class="acoes">
                            <button type="submit" name="acao_acomp" value="atualizar">Registrar atendimento</button>
                            <button type="submit" name="acao_acomp" value="concluir" class="registrar">Concluir e gerar relatório</button>
                        </div>
                    </form>
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