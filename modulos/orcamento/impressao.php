<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/ModuloDAO.php";
include_once "../../classes/dao/OrcamentoDAO.php";
include_once "../../classes/util/SistemaUtil.php";
include_once "../../classes/util/DataUtil.php";

$moduloDAO = new ModuloDAO();
$orcamentoDAO = new OrcamentoDAO();

$id_modulo = filter_input(INPUT_GET, "id");
$id_orc = filter_input(INPUT_GET, "id_orc");

$orcamento = $orcamentoDAO->getOrcamento($id_orc);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SistemaUtil::SISTEMA_TITULO ?> | Or&ccedil;amentos | Or&ccedil;amento</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="image/x-icon" rel="icon" href="../../imagens/icones/favicon.jpg" />
        <link type="text/css" rel="stylesheet" href="../../css/estilo.css"/>

        <script type="text/javascript" src="../../plugins/jquery-ui/js/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="../../plugins/jquery-ui/js/jquery-ui-1.10.1.custom.js"></script>
        <link type="text/css" rel="stylesheet" href="../../plugins/jquery-ui/css/custom-theme/jquery-ui-1.10.1.custom.css"/>

        <script type="text/javascript" src="../../plugins/jquery-validate/jquery.validate.js"></script> 
        <script type="text/javascript" src="../../scripts/jquery-validar-forms.js"></script>

        <script type="text/javascript" src="../../scripts/jquery-ajax.js"></script>
        <script type="text/javascript" src="../../scripts/jquery-funcoes.js"></script>
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

            <div class="modulos">
                <?= $moduloDAO->getModulos($_SESSION["funcionario"], TRUE); ?>
            </div>

            <div id="conteudo">
                <div class="menuEstrutural">
                    <a href="../../inicio.php">IN&Iacute;CIO</a>
                    ::
                    <a href="orcamento.php?id=<?= $id_modulo ?>&id_orc=<?= $id_orc ?>">OR&Ccedil;AMENTO</a>
                    ::
                </div>

                <form method="post" name="frmImprimir" id="frmImprimir" action="imprimir.php">
                    <input type="hidden" name="id_orc" value="<?= $id_orc ?>"/>
                    <input type="hidden" name="nome_obra" value="<?= $orcamento["nome_obra"] ?>"/>
                    <input type="hidden" name="encargo_social" value="<?= $orcamento["encargo_social"] ?>"/>
                    <input type="hidden" name="bdi" value="<?= $orcamento["bdi"] ?>"/>
                    <div class="formulario">
                        <br />
                        <div class="campo">
                            <label class="negrito">Relat&oacute;rio:</label> <br />
                            <select name="relatorio" id="relatorio">
                                <option value="">SELECIONE UM RELAT&Oacute;RIO</option>
                                <option value="1_PLANILHA ORCAMENTARIA">PLANILHA ORCAMENT&Aacute;RIA</option>
                                <option value="2_COMPOSICAO DE CUSTO">COMPOSI&Ccedil;&Atilde;O DE CUSTO</option>
                                <option value="3_CURVA ABC DE INSUMOS">CURVA ABC DE INSUMOS</option>
                                <option value="4_CRONOGRAMA FISICO">CRONOGRAMA F&Iacute;SICO</option>
                            </select> <br /> <br />
                            
                            <label class="negrito">Filtros:</label> <br />
                            <div id="selectExtra"></div>
                            <div id="selectExtra2"></div>
                        </div> <br />                        
                        <div class="botao">
                            <input type="submit" name="btImprimir" value="Imprimir" onclick="$(this).novaAbaForm(this.form);"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>