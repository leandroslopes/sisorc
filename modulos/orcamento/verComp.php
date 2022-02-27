<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/util/SistemaUtil.php";
include_once "../../classes/util/DataUtil.php";
include_once "../../classes/util/ArrayUtil.php";
include_once "../../classes/modelo/Funcionario.php";
include_once "../../classes/dao/ModuloDAO.php";
include_once "../../classes/dao/ComposicaoDAO.php";
include_once "../../classes/dao/ServicoDAO.php";
include_once "../../classes/dao/UnidadeDAO.php";
include_once "../../classes/dao/TipoDAO.php";

$moduloDAO = new ModuloDAO();
$composicaoDAO = new ComposicaoDAO();
$servicoDAO = new ServicoDAO();
$unidadeDAO = new UnidadeDAO();
$tipoDAO = new TipoDAO();

$id_modulo = filter_input(INPUT_GET, "id");
$codigo = filter_input(INPUT_GET, "codigo");

$composicao = $composicaoDAO->getComposicao($codigo);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SistemaUtil::SISTEMA_TITULO ?> | Composi&ccedil;&atilde;o</title>
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
                    <a href="index.php?id=<?= $id_modulo ?>">OR&Ccedil;AMENTO</a>
                    ::
                    <a href="composicao.php?id=<?= $id_modulo ?>">COMPOSI&Ccedil;&Otilde;ES E INSUMOS</a>
                    ::
                </div>

                <div class="modulo">

                    <? include "../../componentesPagina/msgDialog.php"; ?>

                    <div class="oculto" id="dialogFormComp">
                        <br />
                        <form method="post" name="frmCadastrarComp" id="frmCadastrarComp" action="">
                            <input type="hidden" name="codComp" id="codComp" value="<?= $composicao["codigo"] ?>" />
                            <label class="aviso vermelho italico oculto">AVISO: Ao editar esta composi&ccedil;&atilde;o, ela será alterada nas 
                                composi&ccedil;&otilde;es e respectivamente nos or&ccedil;amentos. <br /> <br /> </label>
                            <label class="negrito">Nome:</label> &nbsp;
                            <input type="text" name="descricao" id="descricao" class="caixaAlta retiraAcento focus" size="40" maxlength="200" 
                                   value="<?= $composicao["descricao"] ?>"/> <br /> <br />
                            <label class="negrito">Servi&ccedil;o:</label> &nbsp; CO <br /> <br />
                            <label class="negrito">Unidade:</label> &nbsp; 
                            <?= $unidadeDAO->getSelectUnidade($composicao["id_uni"], "composicao") ?> <br /> <br />
                            <label class="negrito">Tipo:</label> &nbsp;
                            <?= $tipoDAO->getSelectTipo($composicao["id_tipo"], "composicao"); ?>
                        </form>
                    </div>

                    <div class="dialogFormAddIns oculto">
                        <br />
                        <form method="post" name="frmAdicionarIns" id="frmAdicionarIns" action="">
                            <input type="hidden" name="idComp" id="idComp" value="<?= $codigo ?>"/>
                            <label class="negrito">Descri&ccedil;&atilde;o:</label> &nbsp; 
                            <input type="text" name="insDescAdd" id="insDescAdd" class="caixaAlta retiraAcento focus" size="43" maxlength="150" /> 
                            <br /> <br />
                            <label class="negrito">Resultado:</label> &nbsp; <br /> <br />
                            <select name="insPesquisa" id="insPesquisa" size="5" class="selectIns"></select> <br /> <br />
                            <label class="negrito">Insumo:</label> &nbsp; <label id="insumo" class="italico"></label>
                        </form>
                    </div>
                    
                    <div class="dialogConfirmIns oculto">
                        <p id="msgExcluir">Tem certeza que quer excluir este insumo desta composi&ccedil;&atilde;o?</p>
                    </div>

                    <br />
                    <div class="titulo"><?= ArrayUtil::array_get('descricao', $composicao) ?></div> <br />

                    <div class="extrasConteudo">
                        <ul>
                            <li id="adicionarIns">
                                <a class="cursor">
                                    <img src="../../imagens/icones/adicionar.png" alt=""/>
                                    <label>Adicionar Insumo</label>
                                </a>
                            </li>
                            <li>&nbsp;</li>
                            <li id="editarComp">
                                <a class="cursor">
                                    <img src="../../imagens/icones/editar.png" alt=""/>
                                    <label>Editar Composi&ccedil;&atilde;o</label>
                                </a>
                            </li>
                        </ul>
                    </div> <br />

                    <table id="tabela" class="tablesorter">
                        <thead>
                            <tr>
                                <th>C&Oacute;DIGO</th>
                                <th>NOME</th>
                                <th>UNIDADE</th>
                                <th>SERVIÇO</th>
                                <th>TIPO</th>
                                <th>EXCLUIR</th>
                            </tr>
                        </thead>
                        <?= $composicaoDAO->listarInsumos($_REQUEST["codigo"]) ?>
                    </table>

                </div>

                <? include "../../componentesPagina/rodape.php"; ?>
            </div>
        </div>
    </body>
</html>