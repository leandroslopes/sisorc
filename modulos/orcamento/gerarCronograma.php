<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/util/SistemaUtil.php";
include_once "../../classes/util/DataUtil.php";
include_once "../../classes/dao/ModuloDAO.php";
include_once "../../classes/dao/OrcamentoDAO.php";
include_once "../../classes/dao/CronogramaDAO.php";

$moduloDAO = new ModuloDAO();
$orcamentoDAO = new OrcamentoDAO();
$cronogramaDAO = new CronogramaDAO();

$id_modulo = filter_input(INPUT_GET, "id");
$id_orc = filter_input(INPUT_GET, "id_orc");
$id_cabecalho = filter_input(INPUT_GET, "id_cabecalho");
$quantidade = filter_input(INPUT_GET, "quantidade");

$orcamento = $orcamentoDAO->getOrcamento($id_orc);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SistemaUtil::SISTEMA_TITULO ?> | Or&ccedil;amentos</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="image/x-icon" rel="icon" href="../../imagens/icones/favicon.jpg" />
        <link type="text/css" rel="stylesheet" href="../../css/estilo.css"/>

        <script type="text/javascript" src="../../plugins/jquery-ui/js/jquery-1.9.1.js"></script>

        <script type="text/javascript" src="../../plugins/jquery-maskmoney/jquery.maskMoney.js"></script>
        <script type="text/javascript" src="../../scripts/jquery-maskdecimal.js"></script>

        <script type="text/javascript" src="../../scripts/jquery-funcoes.js"></script>
        <script type="text/javascript" src="../../scripts/jquery-ajax.js"></script>
    </head>
    <body>
        <div class="corpo">
            <? include SistemaUtil::SISTEMA_URL . "componentesPagina/cabecalho.php?id_modulo=" . $id_modulo; ?>

            <div class="extras">
                <ul>
                    <li><?= DataUtil::imprimirDataAtual() ?></li>
                    <li>|</li>
                    <li>
                        <a href="../../sair.php" class="cursor">
                            <img src="../../imagens/icones/sair.png"/>
                            <label>Sair do sistema</label>
                        </a>
                    </li>
                </ul>
            </div> <br />

            <div class="subModulos">
                <?= $moduloDAO->getModulos($_SESSION["funcionario"], TRUE); ?>
            </div>

            <div id="conteudo">
                
                <div class="menuEstrutural">
                    <a href="../../inicio.php">IN&Iacute;CIO</a>
                    ::
                    <a href="orcamento.php?id=<?= $id_modulo ?>&id_orc=<?= $id_orc ?>">OR&Ccedil;AMENTO</a>
                    ::
                </div>

                <div class="tabela">

                    <table>
                        <thead>
                            <tr class="high"><th colspan="2">CRONOGRAMA</th></tr>
                            <tr class="low textoEsq">
                                <th colspan="2">OBRA: &nbsp; <?= $orcamento["nome_obra"] ?></th>
                            </tr>
                            <tr class="low">
                                <th class="textoEsq">CLIENTE: &nbsp; <?= $orcamento["nome_cliente"] ?> </th>
                                <th class="textoDir">DATA: <?= DataUtil::getHoje(); ?></th>
                            </tr>
                            <tr class="low textoEsq">
                                <th colspan="2">LOCAL: &nbsp; <?= $orcamento["local"] ?></th>
                            </tr>
                        </thead>
                    </table> 
                    
                    <?= $cronogramaDAO->gerar($_REQUEST); ?>

                </div>                
            </div>
        </div>
    </body>
</html>