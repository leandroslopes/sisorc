<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/util/SistemaUtil.php";
include_once "../../classes/util/DataUtil.php";
include_once "../../classes/dao/ModuloDAO.php";

$moduloDAO = new ModuloDAO();

$modulo["nome"] = "";

$id_modulo = filter_input(INPUT_GET, "id");
$id_modulo_cadastrado = filter_input(INPUT_GET, "id_modulo_cadastrado");

$modulo = $moduloDAO->getModulo($id_modulo_cadastrado);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SistemaUtil::SISTEMA_TITULO ?> | Administra&ccedil;&atilde;o | Gerenciar M&oacute;dulo</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="image/x-icon" rel="icon" href="../../imagens/icones/favicon.png" />
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

        <script type="text/javascript" src="../../scripts/jquery-dialog-form-modulo.js"></script>
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
                    <a href="index.php?id=<?= $id_modulo ?>" title="">ADMINISTRA&Ccedil;&Atilde;O</a>
                    ::
                    <a href="modulos.php?id=<?= $id_modulo ?>" title="">M&Oacute;DULOS</a>
                    ::
                    <a href="modulo.php?id=<?= $id_modulo . "&id_modulo_cadastrado=" . $id_modulo_cadastrado ?>" title="">GERENCIAR M&Oacute;DULO</a>
                    ::
                </div>

                <div class="modulo">
                    <div class="titulo">GERENCIAMENTO DO M&Oacute;DULO "<?= utf8_encode($modulo["nome"]) ?>"</div> <br />

                    <? include_once "../../componentesPagina/msgDialog.php"; ?> <br />

                    <?= $moduloDAO->listarSubmodulos($id_modulo_cadastrado) ?> <br />

                    <div class="dialogForm oculto">
                        <p class="validateTips">Selecione o setor.</p>
                        <br />
                        <form method="post" name="frmAdicionarSetor" id="frmAdicionarSetor" action="">
                            <div class="negrito">
                                <label>Setor:</label> &nbsp;
                                <?= $moduloDAO->getSelectSetorAdicionar($id_modulo_cadastrado) ?>
                            </div>
                        </form>
                    </div>

                    <div class="dialogConfirm oculto">
                        <p>
                            <span class="ui-icon ui-icon-alert"></span>
                            Tem certeza que quer excluir o acesso deste setor?
                        </p>
                    </div>

                    <div class="extrasConteudo">
                        <ul>
                            <li id="adicionarSetorAcesso">
                                <a class="cursor">
                                    <img src="../../imagens/icones/adicionar.png" alt="" />
                                    <label title="Adicionar acesso ao setor">Adicionar setor</label>
                                </a>
                            </li>
                        </ul>
                    </div> <br />

                    <?= $moduloDAO->listarSetores($id_modulo_cadastrado) ?> <br />
                </div>
            </div>
        </div>
    </body>
</html>