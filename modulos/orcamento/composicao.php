<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/util/SistemaUtil.php";
include_once "../../classes/util/DataUtil.php";
include_once "../../classes/dao/ModuloDAO.php";
include_once "../../classes/dao/ComposicaoDAO.php";
include_once "../../classes/dao/InsumoDAO.php";
include_once "../../classes/dao/ServicoDAO.php";
include_once "../../classes/dao/UnidadeDAO.php";
include_once "../../classes/dao/TipoDAO.php";

$moduloDAO = new ModuloDAO();
$composicaoDAO = new ComposicaoDAO();
$insumoDAO = new InsumoDAO();
$servicoDAO = new ServicoDAO();
$unidadeDAO = new UnidadeDAO();
$tipoDAO = new TipoDAO();

$id_modulo = filter_input(INPUT_GET, "id");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SistemaUtil::SISTEMA_TITULO ?> | Or&ccedil;ameto | Composi&ccedil;&otilde;es e Insumos</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="image/x-icon" rel="icon" href="../../imagens/icones/favicon.jpg" />
        <link type="text/css" rel="stylesheet" href="../../css/estilo.css"/>
        <script type="text/javascript" src="../../scripts/js-funcoes.js"></script>

        <link type="text/css" rel="stylesheet" href="../../plugins/jquery-ui/css/custom-theme/jquery-ui-1.10.1.custom.css"/>  
        <script type="text/javascript" src="../../plugins/jquery-ui/js/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="../../plugins/jquery-ui/js/jquery-ui-1.10.1.custom.js"></script>

        <link type="text/css" rel="stylesheet" href="../../plugins/jquery-tablesorter/themes/blue/style.css"/>  
        <script type="text/javascript" src="../../plugins/jquery-tablesorter/jquery-latest.js"></script>
        <script type="text/javascript" src="../../plugins/jquery-tablesorter/jquery.tablesorter.js"></script>
        <script type="text/javascript" src="../../plugins/jquery-tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
        <script type="text/javascript" src="../../scripts/jquery-tabelas.js"></script>

        <script type="text/javascript" src="../../scripts/jquery-dialog-form-orc.js"></script>
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

            <div class="modulos">
                <?= $moduloDAO->getModulos($_SESSION["funcionario"], TRUE); ?>
            </div>

            <div id="conteudo">
                <div class="menuEstrutural">
                    <a href="../../inicio.php" title="">IN&Iacute;CIO</a>
                    ::
                    <a href="index.php?id=<?= $id_modulo ?>" title="">OR&Ccedil;AMENTO</a>
                    ::
                    <a href="composicao.php?id=<?= $id_modulo ?>" title="">COMPOSI&Ccedil;&Otilde;ES E INSUMOS</a>
                    ::
                </div>

                <div class="modulo">

                    <? include "../../componentesPagina/msgDialog.php"; ?>

                    <!-- COMPOSICAO -->

                    <div class="oculto" id="dialogFormPesquisarComp">
                        <br />
                        <form method="post" name="frmPesquisarComp" id="frmPesquisarComp" action="">
                            <input type="hidden" name="idMod" id="idMod" value="<?= $id_modulo ?>"/>
                            <label class="negrito">Descri&ccedil;&atilde;o:</label> &nbsp; 
                            <input type="text" name="descPesquisa" id="descPesquisa" class="retiraAcento focus" size="43" maxlength="200" /> <br /> <br />
                            <label class="negrito">Resultado:</label> &nbsp; <br /> <br />
                            <select name="compPesquisa" id="compPesquisa" size="5" class="selectComp"></select>
                        </form>
                    </div>

                    <div class="oculto" id="dialogFormComp">
                        <br />
                        <form method="post" name="frmCadastrarComp" id="frmCadastrarComp" action="">
                            <label class="aviso vermelho italico oculto">AVISO: Ao editar esta composi&ccedil;&atilde;o, ela será alterada nas 
                                composi&ccedil;&otilde;es e respectivamente nos or&ccedil;amentos. <br /> <br /> </label>
                            <label class="negrito">Nome:</label> <br /> <br />
                            <input type="text" name="descricao" id="descricao" class="caixaAlta retiraAcento focus" size="46" maxlength="200" /> <br /> <br />
                            <label class="negrito">Servi&ccedil;o:</label> &nbsp; CO <br /> <br />
                            <label class="negrito">Unidade:</label> &nbsp; 
                            <?= $unidadeDAO->getSelectUnidade("", "composicao") ?> <br /> <br />
                            <label class="negrito">Tipo:</label> &nbsp;
                            <?= $tipoDAO->getSelectTipo("", "composicao"); ?>
                        </form>
                    </div>

                    <div class="extras">
                        <ul>
                            <li id="pesquisarComp">
                                <a class="cursor">
                                    <img src="../../imagens/icones/pesquisar.png" alt=""/>
                                    <label>Pesquisar Composi&ccedil;&atilde;o</label>
                                </a>
                            </li>
                            <li>&nbsp;</li>
                            <li id="cadastrarComp">
                                <a class="cursor">
                                    <img src="../../imagens/icones/adicionar.png" alt=""/>
                                    <label>Cadastrar Composi&ccedil;&atilde;o</label>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tabela">
                        <?= $composicaoDAO->listarComposicoes($id_modulo) ?>
                    </div>

                    <!-- INSUMO -->

                    <div class="oculto" id="dialogFormPesquisarIns">
                        <br />
                        <form method="post" name="frmPesquisarIns" id="frmPesquisarIns" action="">
                            <label class="negrito">Descri&ccedil;&atilde;o:</label> &nbsp; 
                            <input type="text" name="insDescPesquisa" id="insDescPesquisa" class="retiraAcento focus" size="43" maxlength="200" /> <br /> <br />
                            <label class="negrito">Resultado:</label> &nbsp; <br /> <br />
                            <select name="insPesquisa" id="insPesquisa" size="5" class="selectIns"></select>
                        </form>
                    </div>

                    <div class="oculto" id="dialogFormIns">
                        <br />
                        <form method="post" name="frmCadastrarIns" id="frmCadastrarIns" action="">
                            <div class="negrito">
                                <label>Nome:</label> <br /> <br />
                                <input type="text" name="desc_ins" id="desc_ins" class="caixaAlta retiraAcento focus" size="43" maxlength="150" /> <br /> <br />
                                <label>Unidade:</label> &nbsp; 
                                <?= $unidadeDAO->getSelectUnidade("", "insumo") ?> <br /> <br />
                                <label>Servi&ccedil;o:</label> &nbsp; 
                                <?= $servicoDAO->getSelectServico("", "insumo") ?> <br /> <br />
                                <label>Tipo:</label> &nbsp;
                                <?= $tipoDAO->getSelectTipo("", "insumo"); ?>
                            </div>
                        </form>
                    </div>

                    <div class="extras">
                        <ul>
                            <li id="pesquisarIns">
                                <a class="cursor">
                                    <img src="../../imagens/icones/pesquisar.png" alt=""/>
                                    <label>Pesquisar Insumo</label>
                                </a>
                            </li>
                            <li>&nbsp;</li>
                            <li id="cadastrarIns">
                                <a class="cursor">
                                    <img src="../../imagens/icones/adicionar.png" alt=""/>
                                    <label>Cadastrar Insumo</label>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tabela">
                        <?= $insumoDAO->listarInsumos(); ?>
                    </div>

                </div>
            </div>
        </div>
    </body>
</html>