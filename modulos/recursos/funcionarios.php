<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/util/SistemaUtil.php";
include_once "../../classes/util/DataUtil.php";
include_once "../../classes/dao/ModuloDAO.php";
include_once "../../classes/dao/FuncionarioDAO.php";
include_once "../../classes/dao/CargoDAO.php";
include_once "../../classes/dao/SetorDAO.php";

$moduloDAO = new ModuloDAO();
$funcionarioDAO = new FuncionarioDAO();
$cargoDAO = new CargoDAO();
$setorDAO = new SetorDAO();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SistemaUtil::SISTEMA_TITULO ?> | Recursos Humanos | Funcion&aacute;rios</title>
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

        <script type="text/javascript" src="../../scripts/jquery-dialog-form-funcionario.js"></script>
        <script type="text/javascript" src="../../scripts/jquery-funcoes.js"></script>
        <script type="text/javascript" src="../../scripts/jquery-ajax.js"></script>
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
                    <a href="funcionarios.php" title="">FUNCION&Aacute;RIOS</a>
                    ::
                </div>

                <div class="modulo">

                    <? include "../../componentesPagina/msgDialog.php"; ?>
                    
                    <div class="dialogForm oculto">
                        <form method="post" name="frmAdicionarFuncionario" id="frmAdicionarFuncionario" action="">
                            <div class="negrito">
                                <label>C&oacute;digo:</label> &nbsp;
                                <input type="text" name="codigo" id="codigo" class="soNumeros focus" maxlength="6" size="10"/> <br /> <br />
                                <label>Resultado:</label> &nbsp;
                                <select name="funcionarios" id="funcionarios" class="selectFuncionarios" size="3"></select> <br /> <br />
                                <label>Funcion&aacute;rio:</label> &nbsp;
                                <label id="funcionario" class="italico"></label> <br /> <br />
                                <label>Cargo:</label> &nbsp;
                                <?= $cargoDAO->getSelect("add"); ?> <br /> <br />
                                <label>Setor:</label> &nbsp;
                                <?= $setorDAO->getSelect("add"); ?> <br /> <br />
                                <label>Situa&ccedil;&atilde;o:</label> &nbsp;
                                <select name="situacao">
                                    <option value="0">ATIVO</option>
                                    <option value="1">INATIVO</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    
                    <div class="dialogFormEditar oculto">
                        <form method="post" name="frmEditarFuncionario" id="frmEditarFuncionario" action="">
                            <input type="hidden" name="codigo_edd" id="codigo_edd" value=""/> 
                            <div class="negrito">
                                <label>Funcion&aacute;rio:</label> &nbsp;
                                <label id="nome_funcionario" class="italico"></label> <br /> <br />
                                <label>Cargo:</label> &nbsp;
                                <?= $cargoDAO->getSelect(); ?> <br /> <br />
                                <label>Setor:</label> &nbsp;
                                <?= $setorDAO->getSelect(); ?> <br /> <br />
                                <label>Situa&ccedil;&atilde;o:</label> &nbsp;
                                <select name="situacao" id="situacao_edd">
                                    <option value="0">ATIVO</option>
                                    <option value="1">INATIVO</option>
                                </select>
                            </div>
                        </form>
                    </div>

                    <div class="extras">
                        <ul>
                            <li id="adicionarFuncionario">
                                <a title="">
                                    <img src="../../imagens/icones/adicionar.png" alt=""/>
                                    <label>Adicionar</label>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tabela">
                        <?= $funcionarioDAO->listar(); ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>