<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/util/SistemaUtil.php";
include_once "../../classes/util/DataUtil.php";
include_once "../../classes/dao/ModuloDAO.php";
include_once "../../classes/dao/CargoDAO.php";

$moduloDAO = new ModuloDAO();
$cargoDAO = new CargoDAO();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SistemaUtil::SISTEMA_TITULO ?> | Recursos Humanos | Cargos</title>
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

        <script type="text/javascript" src="../../scripts/jquery-dialog-form-cargo.js"></script>
        <script type="text/javascript" src="../../scripts/jquery-funcoes.js"></script>
    </head>
    <body>
        <div class="corpo">
            <? include SistemaUtil::SISTEMA_URL . "componentesPagina/cabecalho.php?id_modulo=" . $_SESSION["id_modulo"]; ?>

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
                    <a href="index.php?id=<?= $_SESSION["id_modulo"] ?>" title="">RECURSOS HUMANOS</a>
                    ::
                    <a href="cargos.php" title="">CARGOS</a>
                    ::
                </div>

                <div class="modulo">

                    <? include "../../componentesPagina/msgDialog.php"; ?>

                    <div class="dialogForm oculto">
                        <p class="validateTips">Informe o nome do cargo.</p>
                        <br />
                        <form method="post" name="frmCadastrarCargo" id="frmCadastrarCargo" action="">
                            <div class="negrito">
                                <label>CARGO:</label> &nbsp;
                                <input type="text" name="cargo" id="cargo" class="caixaAlta retiraAcento focus" maxlength="100" />
                            </div>
                        </form>
                    </div>

                    <div class="dialogConfirm oculto">
                        <p>
                            <span class="ui-icon ui-icon-alert"></span>
                            Tem certeza que quer excluir este cargo?
                        </p>
                    </div>

                    <div class="extras">
                        <ul>
                            <li id="cadastrarCargo">
                                <a title="">
                                    <img src="../../imagens/icones/adicionar.png" alt=""/>
                                    <label>Cadastrar</label>
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="tabela">
                        <?= $cargoDAO->listarCargos() ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>