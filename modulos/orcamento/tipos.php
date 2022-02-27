<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/util/SistemaUtil.php";
include_once "../../classes/util/DataUtil.php";
include_once "../../classes/dao/ModuloDAO.php";
include_once "../../classes/dao/TipoDAO.php";
include_once "../../classes/dao/UnidadeDAO.php";
include_once "../../classes/dao/ServicoDAO.php";

$moduloDAO = new ModuloDAO();
$tipoDAO = new TipoDAO();
$unidadeDAO = new UnidadeDAO();
$servicoDAO = new ServicoDAO();

$id_modulo = filter_input(INPUT_GET, "id");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SistemaUtil::SISTEMA_TITULO ?> | Or&ccedil;ameto | Tipos e Unidades e Servi&ccedil;os</title>
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

        <script type="text/javascript" src="../../scripts/jquery-dialog-form-tipo.js"></script>
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
                <?= $moduloDAO->getModulos($_SESSION["funcionario"], TRUE) ?>
            </div>

            <div id="conteudo">
                <div class="menuEstrutural">
                    <a href="../../inicio.php" title="">IN&Iacute;CIO</a>
                    ::
                    <a href="index.php?id=<?= $id_modulo ?>" title="">OR&Ccedil;AMENTO</a>
                    ::
                    <a href="tipos.php?id=<?= $id_modulo ?>" title="">TIPOS - UNIDADES - SERVI&Ccedil;OS</a>
                    ::
                </div>

                <div class="modulo">

                    <? include "../../componentesPagina/msgDialog.php"; ?>

                    <!-- SERVICO -->

                    <div class="dialogFormServ oculto">
                        <br />
                        <form method="post" name="frmCadastrarServ" id="frmCadastrarServ" action="">
                            <div class="negrito">
                                <label>Servi&ccedil;o:</label> &nbsp;
                                <input type="text" name="servico" id="servico" class="caixaAlta retiraAcento focus" size="10" maxlength="5" />
                            </div>
                        </form>
                    </div>

                    <div class="dialogConfirmServ oculto">
                        <p>Tem certeza que quer excluir este servi&ccedil;o?</p>
                    </div>

                    <div class="extras">
                        <ul>
                            <li id="cadastrarServ">
                                <a class="cursor">
                                    <img src="../../imagens/icones/adicionar.png" alt=""/>
                                    <label>Cadastrar Servi&ccedil;o</label>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tabela">
                        <?= $servicoDAO->listarServicos() ?>
                    </div>               

                    <!-- TIPO -->

                    <div class="dialogFormTipo oculto">
                        <br />
                        <form method="post" name="frmCadastrarTipo" id="frmCadastrarTipo" action="">
                            <div class="negrito">
                                <label>Tipo:</label> &nbsp;
                                <input type="text" name="tipo" id="tipo" class="caixaAlta retiraAcento focus" size="10" maxlength="5" />
                            </div>
                        </form>
                    </div>

                    <div class="dialogConfirmTipo oculto">
                        <p>Tem certeza que quer excluir este tipo?</p>
                    </div>

                    <div class="extras">
                        <ul>
                            <li id="cadastrarTipo">
                                <a class="cursor">
                                    <img src="../../imagens/icones/adicionar.png" alt=""/>
                                    <label>Cadastrar Tipo</label>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tabela">
                        <?= $tipoDAO->listarTipos() ?>
                    </div>

                    <!-- UNIDADE -->

                    <div class="dialogFormUni oculto">
                        <br />
                        <form method="post" name="frmCadastrarUnidade" id="frmCadastrarUnidade" action="">
                            <div class="negrito">
                                <label>Unidade:</label> &nbsp;
                                <input type="text" name="unidade" id="unidade" class="caixaAlta retiraAcento focus" size="10" maxlength="5" />
                            </div>
                        </form>
                    </div>

                    <div class="dialogConfirmUni oculto">
                        <p>Tem certeza que quer excluir este unidade?</p>
                    </div>

                    <div class="extras">
                        <ul>
                            <li id="cadastrarUnidade">
                                <a class="cursor">
                                    <img src="../../imagens/icones/adicionar.png" alt=""/>
                                    <label>Cadastrar Unidade</label>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tabela">
                        <?= $unidadeDAO->listarUnidades() ?>
                    </div>

                </div>
            </div>
        </div>
    </body>
</html>