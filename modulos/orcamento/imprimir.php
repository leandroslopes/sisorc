<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/ImpressaoDAO.php";
include_once "../../classes/dao/OrcamentoDAO.php";
include_once "../../classes/dao/TituloDAO.php";

$impressoDAO = new ImpressaoDAO();
$orcamentoDAO = new OrcamentoDAO();
$orcTituloDAO = new TituloDAO();

$id_orc = filter_input(INPUT_POST, "id_orc");
$relatorio = filter_input(INPUT_POST, "relatorio");
$selectRel1 = filter_input(INPUT_POST, "selectRel1");

$titulo_relatorio = explode('_', $relatorio);

if ($selectRel1 == 3) {
    $selectRel1 = true;
}

$orcamento = $orcamentoDAO->getOrcamento($id_orc);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Impress&atilde;o</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="image/x-icon" rel="icon" href="../../imagens/icones/favicon.jpg" />
        <link type="text/css" rel="stylesheet" href="../../css/estilo.css"/>
    </head>
    <body id="semBackground">

        <div id="imprimir">

            <? $total_obra = $orcTituloDAO->getTotalOrcamento($id_orc, $orcamento["encargo_social"], $orcamento["bdi"], $selectRel1); ?>
            <table id="tblImpCabecalho">
                <thead class="textoEsq">
                    <tr class="textoCentro"><th colspan="2"><?= $titulo_relatorio[1] ?></th></tr>
                    <tr>
                        <th colspan="2">OBRA: &nbsp; <?= $orcamento["nome_obra"] ?></th>
                    </tr>
                    <tr>
                        <th>CLIENTE: &nbsp; <?= $orcamento["nome_cliente"] ?> </th>
                        <th>&Aacute;REA: &nbsp; <?= NumeroUtil::formatar($orcamento["area"], NumeroUtil::NUMERO_BRA) ?> M2</th>
                    </tr>
                    <tr>
                        <th>LOCAL: &nbsp; <?= $orcamento["local"] ?></th>
                        <th>VALOR/M2: &nbsp; <?= NumeroUtil::formatar(($total_obra / $orcamento["area"]), NumeroUtil::NUMERO_BRA) ?></th>
                    </tr>
                    <tr>
                        <th>B.D.I: &nbsp; <?= NumeroUtil::formatar($orcamento["bdi"], NumeroUtil::NUMERO_BRA) ?>%</th>
                        <th>ENCARGO SOCIAL: &nbsp; <?= NumeroUtil::formatar($orcamento["encargo_social"], NumeroUtil::NUMERO_BRA) ?>%</th>
                    </tr>
                </thead>
            </table>

            <?= $impressoDAO->imprimir($_REQUEST) ?>
        </div>
    </body>
</html>