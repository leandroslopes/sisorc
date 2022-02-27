<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/util/SistemaUtil.php";
include_once "../../classes/util/DataUtil.php";
include_once "../../classes/modelo/Funcionario.php";
include_once "../../classes/dao/ModuloDAO.php";
include_once "../../classes/dao/CadastroDAO.php";

$moduloDAO = new ModuloDAO();
$cadastroDAO = new CadastroDAO();

$id_modulo = filter_input(INPUT_GET, "id");
$codigo = filter_input(INPUT_POST, "codigo");
$nome = filter_input(INPUT_POST, "nome");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SistemaUtil::SISTEMA_TITULO ?> | Cadastro | Pesquisar</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="image/x-icon" rel="icon" href="../../imagens/favicon.png" />
        <link type="text/css" rel="stylesheet" href="../../css/estilo.css"/>
        <script type="text/javascript" src="../../scripts/valida-forms.js"></script>

        <link type="text/css" rel="stylesheet" href="../../plugins/jquery-ui/css/custom-theme/jquery-ui-1.10.1.custom.css"/>  
        <script type="text/javascript" src="../../plugins/jquery-ui/js/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="../../plugins/jquery-ui/js/jquery-ui-1.10.1.custom.js"></script>

        <link type="text/css" rel="stylesheet" href="../../plugins/jquery-tablesorter/themes/blue/style.css"/>
        <script type="text/javascript" src="../../plugins/jquery-tablesorter/jquery-latest.js"></script>
        <script type="text/javascript" src="../../plugins/jquery-tablesorter/jquery.tablesorter.js"></script>
        <script type="text/javascript" src="../../plugins/jquery-tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
        <script type="text/javascript" src="../../scripts/jquery-tabelas.js"></script>

        <script type="text/javascript" src="../../scripts/jquery-funcoes.js"></script>
    </head>
    <body>
        <div class="corpo">
            <? include SistemaUtil::SISTEMA_URL . "componentesPagina/cabecalho.php?id_modulo=" . $id_modulo ?>

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
                    <a href="index.php?id=<?= $id_modulo ?>">CADASTRO</a>
                    ::
                    <a href="frmPesquisar.php?id=<?= $id_modulo ?>">PESQUISAR</a>
                    ::
                </div>


                <? if (empty($codigo) && empty($nome)) { ?>
                    <form method="post" name="frmPesquisar" id="frmPesquisar" action="" onsubmit="return validaFrmPesquisar()">
                        <div class="formulario">
                            <br />
                            <div class="campo">
                                <b>C&oacute;digo:</b> <br />
                                <input type="text" name="codigo" maxlength="6" size="15" class="soNumeros"/>
                            </div>
                            <div class="campo">
                                <b>Nome:</b> <br />
                                <input type="text" name="nome" maxlength="255" size="75" class="retiraAcento focus"/>
                            </div>        
                            <br />
                            <div class="botao">
                                <input type="submit" name="btPesquisar" value="Pesquisar"/>
                            </div>
                            <div id="msgErroForms" class="msgErroForm"></div>
                        </div>
                    </form>
                <? } else { ?>
                    <div class="tabela">
                        <?= $cadastroDAO->listarCadastros($_REQUEST, $id_modulo); ?>
                    </div>
                <? } ?>
            </div>
        </div>
        </div>
    </body>
</html>
