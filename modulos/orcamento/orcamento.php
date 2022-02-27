<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/util/SistemaUtil.php";
include_once "../../classes/util/DataUtil.php";
include_once "../../classes/util/NumeroUtil.php";
include_once "../../classes/dao/ModuloDAO.php";
include_once "../../classes/dao/OrcamentoDAO.php";
include_once "../../classes/dao/OrcComposicaoDAO.php";
include_once "../../classes/dao/TituloDAO.php";
include_once "../../classes/dao/SubtituloDAO.php";
include_once "../../classes/dao/CronogramaDAO.php";
include_once "../../classes/dao/CronogramaCabecalhoDAO.php";

$moduloDAO = new ModuloDAO();
$orcamentoDAO = new OrcamentoDAO();
$orcComposicaoDAO = new OrcComposicaoDAO();
$orcTituloDAO = new TituloDAO();
$orcSubtituloDAO = new SubtituloDAO();
$cronogramaDAO = new CronogramaDAO();
$cronogramaCabecalhoDAO = new CronogramaCabecalhoDAO();

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
        <script type="text/javascript" src="../../scripts/js-funcoes.js"></script>

        <link type="text/css" rel="stylesheet" href="../../plugins/jquery-ui/css/custom-theme/jquery-ui-1.10.1.custom.css"/>  
        <script type="text/javascript" src="../../plugins/jquery-ui/js/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="../../plugins/jquery-ui/js/jquery-ui-1.10.1.custom.js"></script>

        <script type="text/javascript" src="../../plugins/jquery-maskmoney/jquery.maskMoney.js"></script>
        <script type="text/javascript" src="../../scripts/jquery-maskdecimal.js"></script>

        <link type="text/css" rel="stylesheet" href="../../plugins/jquery-tablesorter/themes/blue/style.css"/>  
        <script type="text/javascript" src="../../plugins/jquery-tablesorter/jquery-latest.js"></script>
        <script type="text/javascript" src="../../plugins/jquery-tablesorter/jquery.tablesorter.js"></script>
        <script type="text/javascript" src="../../plugins/jquery-tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>

        <script type="text/javascript" src="../../scripts/jquery-funcoes.js"></script>
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

            <div class="modulos">
                <?= $moduloDAO->getModulos($_SESSION["funcionario"], TRUE); ?>
            </div>

            <div id="conteudo">
                <div class="menuEstrutural">
                    <a href="../../inicio.php">IN&Iacute;CIO</a>
                    ::
                    <a href="orcamentos.php?id=<?= $id_modulo ?>" title="">OR&Ccedil;AMENTOS</a>
                    ::
                </div>

                <div class="modulo">

                    <? include "../../componentesPagina/msgDialog.php"; ?>

                    <!-- TITULO -->

                    <div class="oculto" id="dialogFormCadTitulo">
                        <br />
                        <form method="post" name="frmCadastrarTitulo" id="frmCadastrarTitulo" action="">
                            <input type="hidden" name="id_orc_cad" id="id_orc_cad" value="<?= $id_orc ?>"/>
                            <label class="negrito">T&iacute;tulo:</label> &nbsp;
                            <input type="text" name="nome_cad" id="nome_cad" class="caixaAlta retiraAcento focus" size="45" maxlength="150" value=""/> 
                            <br /> <br />
                            <label class="negrito">Item:</label> &nbsp;
                            <input type="text" name="item_cad" id="item_cad" class="soNumeros" size="10" maxlength="2" value=""/>
                        </form>
                    </div>

                    <div class="oculto" id="dialogFormAddTitulo">
                        <br />
                        <form method="post" name="frmAdicionarTit" id="frmAdicionarTit" action="">
                            <input type="hidden" name="id_orc_add" id="id_orc_add" value="<?= $id_orc ?>"/>
                            <label class="negrito">Descri&ccedil;&atilde;o:</label> &nbsp; 
                            <input type="text" name="nome_pesq" id="nome_pesq" class="caixaAlta retiraAcento focus" size="45" maxlength="150" value=""/> 
                            <br /> <br />
                            <label class="negrito">Resultado:</label> <br /> <br />
                            <select name="titulos" id="titulos" size="5" class="selectTitulos"></select> <br /> <br />
                            <label class="negrito">T&iacute;tulo:</label> &nbsp; <label id="titulo" class="italico"></label> <br /> <br />
                            <label class="negrito">Item:</label> &nbsp;
                            <input type="text" name="item_add" id="item_add" class="soNumeros" size="10" maxlength="2" value=""/>
                        </form>
                    </div>

                    <div class="oculto" id="dialogFormEddTitulo">
                        <br />
                        <form method="post" name="frmEditarTitulo" id="frmEditarTitulo" action="">
                            <input type="hidden" name="id_orc_edd" id="id_orc_edd" value="<?= $id_orc ?>"/>
                            <input type="hidden" name="id_orc_tit_edd" id="id_orc_tit_edd" value=""/>
                            <label class="negrito">Descri&ccedil;&atilde;o:</label> &nbsp;
                            <input type="text" name="nome_edd" id="nome_edd" class="caixaAlta retiraAcento focus" size="45" maxlength="150" value=""/> 
                            <br /> <br />
                            <label class="negrito">Item:</label> &nbsp;
                            <input type="text" name="item_edd" id="item_edd" class="soNumeros" size="10" maxlength="2" value=""/>
                        </form>
                    </div>

                    <div class="oculto" id="dialogConfirmTitulo">
                        <p>Tem certeza que quer excluir este t&iacute;tulo?</p>
                    </div>

                    <div class="oculto" id="dialogFormAddTitComp">
                        <br />
                        <form method="post" name="frmAdicionarTitComp" id="frmAdicionarTitComp" action="">
                            <input type="hidden" name="id_orc_add_comp" id="id_orc_add_comp" value="<?= $id_orc ?>"/>
                            <label class="negrito">T&iacute;tulos:</label> &nbsp; <br /> <br />
                            <?= $orcTituloDAO->getSelectOrcTitulos($id_orc) ?> <br /> <br />
                            <label class="negrito">Composi&ccedil;&otilde;es:</label> <br /> <br />
                            <?= $orcComposicaoDAO->getSelectOrcComp($id_orc) ?> <br /> <br />
                            <label class="negrito">Item:</label> &nbsp;
                            <input type="text" name="tit_comp_item_add" id="tit_comp_item_add" class="soNumeros" size="10" maxlength="4" value=""/> <br /> <br />
                            <label class="negrito">Quantidade:</label> &nbsp;
                            <input type="text" name="qtd_add" id="qtd_add" class="decimal" value="" size="10"/>
                        </form>
                    </div>

                    <!-- SUBTITULO -->

                    <div class="oculto" id="dialogFormCadSubTit">
                        <br />
                        <form method="post" name="frmCadastrarSubtit" id="frmCadastrarSubtit" action="">
                            <label class="negrito">T&iacute;tulo:</label> <br /> <br />
                            <?= $orcTituloDAO->getSelectOrcTitulos($id_orc) ?> <br /> <br />
                            <label class="negrito">Subt&iacute;tulo:</label> &nbsp;
                            <input type="text" name="subtit_cad" id="subtit_cad" class="caixaAlta retiraAcento" size="45" maxlength="150" value=""/>
                            <br /> <br />
                            <label class="negrito">Item:</label> &nbsp;
                            <input type="text" name="subitem_cad" id="subitem_cad" class="soNumeros" size="10" maxlength="4" value=""/>
                        </form>
                    </div>

                    <div class="oculto" id="dialogFormAddSubTit">
                        <br />
                        <form method="post" name="frmAdicionarSubTit" id="frmAdicionarSubTit" action="">
                            <input type="hidden" name="tipo" id="tipo" value="subtitulo"/>
                            <label class="negrito">T&iacute;tulos:</label> <br /> <br />
                            <?= $orcTituloDAO->getSelectOrcTitulos($id_orc) ?> <br /> <br />
                            <label class="negrito">Descri&ccedil;&atilde;o:</label> &nbsp; 
                            <input type="text" name="subtit_pesq" id="subtit_pesq" class="caixaAlta retiraAcento focus" size="45" maxlength="150" value=""/> 
                            <br /> <br />
                            <label class="negrito">Resultado:</label> <br /> <br />
                            <select name="subtitulos" id="subtitulos" size="5" class="selectTitulos"></select> <br /> <br />
                            <label class="negrito">Subt&iacute;tulo:</label> &nbsp; <label id="subtitulo" class="italico"></label>
                            <br /> <br />
                            <label class="negrito">Item:</label> &nbsp;
                            <input type="text" name="subitem_add" id="subitem_add" class="soNumeros" size="10" maxlength="4" value=""/>
                        </form>
                    </div>

                    <div class="oculto" id="dialogFormEddSubTit">
                        <br />
                        <form method="post" name="frmEditarSubtit" id="frmEditarSubtit" action="">
                            <input type="hidden" name="id_subtit_edd" id="id_subtit_edd" value=""/>
                            <label class="negrito">Descri&ccedil;&atilde;o:</label> &nbsp;
                            <input type="text" name="subnome_edd" id="subnome_edd" class="caixaAlta retiraAcento focus" size="45" maxlength="150" value=""/> 
                            <br /> <br />
                            <label class="negrito">Item:</label> &nbsp;
                            <input type="text" name="subitem_edd" id="subitem_edd" class="soNumeros" size="10" maxlength="4" value=""/>
                        </form>
                    </div>

                    <div class="oculto" id="dialogConfirmSubtit">
                        <p>Tem certeza que quer excluir este subt&iacute;tulo?</p>
                    </div>

                    <div class="oculto" id="dialogFormAddSubtitComp">
                        <br />
                        <form method="post" name="frmAdicionarSubtitComp" id="frmAdicionarSubtitComp" action="">
                            <input type="hidden" name="id_orc_add_comp2" id="id_orc_add_comp2" value="<?= $id_orc ?>"/>
                            <label class="negrito">Subt&iacute;tulos:</label> &nbsp; <br /> <br />
                            <?= $orcSubtituloDAO->getSelectSubtitulos($id_orc) ?> <br /> <br />
                            <label class="negrito">Composi&ccedil;&otilde;es:</label> <br /> <br />
                            <?= $orcComposicaoDAO->getSelectOrcComp($id_orc) ?> <br /> <br />
                            <label class="negrito">Item:</label> &nbsp;
                            <input type="text" name="item_add2" id="item_add2" class="soNumeros" size="10" maxlength="6" value=""/> <br /> <br />
                            <label class="negrito">Quantidade:</label> &nbsp;
                            <input type="text" name="qtd_add2" id="qtd_add2" class="decimal" value="" size="10"/>
                        </form>
                    </div>

                    <!-- COMPOSICAO -->

                    <div class="oculto" id="dialogFormEddComp">
                        <br />
                        <form method="post" name="frmEditarComp" id="frmEditarComp" action="">
                            <input type="hidden" name="id_orc_comp_edd" id="id_orc_comp_edd" value=""/>
                            <input type="hidden" name="id_comp_edd" id="id_comp_edd" value=""/>
                            <label class="negrito">Item:</label> &nbsp;
                            <input type="text" name="comp_item_edd" id="comp_item_edd" class="soNumeros" size="10" maxlength="6" value=""/> <br /> <br />
                            <label class="negrito">Quantidade:</label> &nbsp;
                            <input type="text" name="qtd_edd" id="qtd_edd" class="decimal" value="" size="10"/>
                        </form>
                    </div>

                    <div class="oculto" id="dialogConfirmExcComp">
                        <p>Tem certeza que quer excluir esta composi&ccedil;&atilde;o?</p>
                    </div>

                    <!-- CRONOGRAMA -->

                    <div class="oculto" id="dialogFormGerarCronograma">
                        <br />
                        <form method="post" name="frmGerarCronograma" id="frmGerarCronograma" action="">
                            <input type="hidden" name="id_modulo" id="id_modulo" value="<?= $id_modulo ?>"/>
                            <input type="hidden" name="id_orc" id="id_orc" value="<?= $id_orc ?>"/>
                            <label class="negrito">Cabe&ccedil;alho:</label> &nbsp;
                            <?= $cronogramaCabecalhoDAO->getSelect(); ?> <br /> <br />
                            <label class="negrito">Quantidade:</label> &nbsp;
                            <input type="text" name="quantidade" id="quantidade" class="soNumeros" size="5" maxlength="2"/>
                        </form>
                    </div>

                    <!-- OPCOES -->

                    <div class="extras">
                        <ul>
                            <li id="compCusto">
                                <a href="composicao_custo.php?id=<?= $id_modulo ?>&id_orc=<?= $id_orc ?>">
                                    <img src="../../imagens/icones/editar.png" alt=""/>
                                    <label>Composi&ccedil;&atilde;o de Custo</label>
                                </a>
                            </li>
                            <li>&nbsp;&nbsp;</li>
                            <?
                            $id_cronograma = "";
                            $link_cronograma = "";
                            if (!$cronogramaDAO->temCronograma($id_orc)) {
                                $id_cronograma = "gerarCronograma";
                            } else {
                                $link_cronograma = "href='cronograma.php?id=$id_modulo&id_orc=$id_orc'";
                            }
                            ?>
                            <li id="<?= $id_cronograma ?>">
                                <a <?= $link_cronograma ?> >
                                    <img src="../../imagens/icones/editar.png" alt=""/>
                                    <label>Cronograma F&iacute;sico</label>
                                </a>
                            </li>
                            <li>&nbsp;&nbsp;</li>
                            <li id="editarOrc">
                                <a href="frmCadOrc.php?id=<?= $id_modulo ?>&id_orc=<?= $id_orc ?>">
                                    <img src="../../imagens/icones/editar.png" alt=""/>
                                    <label>Editar Or&ccedil;amento</label>
                                </a> 
                            </li>
                            <li>&nbsp;&nbsp;</li>
                            <li id="impressao">
                                <a href="impressao.php?id=<?= $id_modulo ?>&id_orc=<?= $id_orc ?>">
                                    <img src="../../imagens/icones/imprimir.png" alt=""/>
                                    <label>Impress&atilde;o</label>
                                </a>
                            </li>
                        </ul>
                    </div> <br />

                    <div class="extras">
                        <ul>
                            <li id="cadastrarTitulo">
                                <a>
                                    <img src="../../imagens/icones/adicionar.png" alt=""/>
                                    <label>Cadastrar T&iacute;tulo</label>
                                </a>
                            </li>
                            <li>&nbsp;&nbsp;</li>
                            <li id="adicionarTitulo">
                                <a>
                                    <img src="../../imagens/icones/adicionar.png" alt=""/>
                                    <label>Adicionar T&iacute;tulo</label>
                                </a>
                            </li>
                            <li>&nbsp;&nbsp;</li>
                            <li id="adicionarTitComp">
                                <a>
                                    <img src="../../imagens/icones/adicionar.png" alt=""/>
                                    <label>Adicionar Composi&ccedil;&atilde;o no T&iacute;tulo</label>
                                </a>
                            </li>
                        </ul> <br /> <br />
                        <ul>
                            <li id="cadastrarSubTitulo">
                                <a>
                                    <img src="../../imagens/icones/adicionar.png" alt=""/>
                                    <label>Cadastrar Subt&iacute;tulo</label>
                                </a>
                            </li>
                            <li>&nbsp;&nbsp;</li>
                            <li id="adicionarSubTitulo">
                                <a>
                                    <img src="../../imagens/icones/adicionar.png" alt=""/>
                                    <label>Adicionar Subt&iacute;tulo</label>
                                </a>
                            </li>
                            <li>&nbsp;&nbsp;</li>
                            <li id="adicionarSubtitComp">
                                <a>
                                    <img src="../../imagens/icones/adicionar.png" alt=""/>
                                    <label>Adicionar Composi&ccedil;&atilde;o no Subt&iacute;tulo</label>
                                </a>
                            </li>
                        </ul>
                    </div> 

                    <?
                    $total_obra = $orcTituloDAO->getTotalOrcamento($id_orc, $orcamento["encargo_social"], $orcamento["bdi"], FALSE);
                    ?>
                    <div class="tabela">
            
                        <table>
                            <thead>
                                <tr class="high"><th colspan="6">PLANILHA OR&Ccedil;AMENT&Aacute;RIA</th></tr>
                                <tr class="low textoEsq">
                                    <th>OBRA: &nbsp; <?= $orcamento["nome_obra"] ?></th>
                                    <th>CLIENTE: &nbsp; <?= $orcamento["nome_cliente"] ?> </th>
                                    <th colspan="2">LOCAL: &nbsp; <?= $orcamento["local"] ?></th>
                                </tr>
                                <tr class="low textoEsq">
                                    <th>&Aacute;REA: &nbsp; <?= NumeroUtil::formatar($orcamento["area"], NumeroUtil::NUMERO_BRA) ?> M2</th>
                                    <th>VALOR/M2: &nbsp; <?= NumeroUtil::formatar(($total_obra / $orcamento["area"]), NumeroUtil::NUMERO_BRA) ?></th>
                                    <th>B.D.I: &nbsp; <?= NumeroUtil::formatar($orcamento["bdi"], NumeroUtil::NUMERO_BRA) ?>%</th>
                                    <th>ENCARGO SOCIAL: &nbsp; <?= NumeroUtil::formatar($orcamento["encargo_social"], NumeroUtil::NUMERO_BRA) ?>%</th>
                                </tr>
                            </thead>
                        </table>

                        <table>
                            <thead>
                                <tr class="high textoEsq">
                                    <th>ITEM</th>
                                    <th>DESCRI&Ccedil;&Atilde;O</th>
                                    <th>UNIDADE</th>
                                    <th>QUANTIDADE</th>
                                    <th>PRE&Ccedil;O UNIT&Aacute;RIO (R$)</th>
                                    <th>TOTAL (R$)</th>
                                    <th><img src="../../imagens/icones/excluir.png" alt="Excluir" class="tam16"/></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="tituloItem negrito">
                                    <td>**01**</td>
                                    <td><?= $orcamento["nome_obra"] ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><?= NumeroUtil::formatar($total_obra, NumeroUtil::NUMERO_BRA); ?></td>
                                    <td></td>
                                </tr>
                                <?= $orcTituloDAO->listarTitulos($id_orc, $orcamento["encargo_social"], $orcamento["bdi"]); ?>
                            </tbody>
                        </table>
                        
                    </div>                    
                </div>                
            </div>
        </div>
    </body>
</html>