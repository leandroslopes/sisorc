<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/util/SistemaUtil.php";
include_once "../../classes/util/DataUtil.php";
include_once "../../classes/dao/ModuloDAO.php";
include_once "../../classes/dao/OrcamentoDAO.php";
include_once "../../classes/dao/OrcComposicaoDAO.php";
include_once "../../classes/dao/UnidadeDAO.php";
include_once "../../classes/dao/TipoDAO.php";
include_once "../../classes/dao/ServicoDAO.php";

$moduloDAO = new ModuloDAO();
$orcamentoDAO = new OrcamentoDAO();
$orcComposicaoDAO = new OrcComposicaoDAO();
$unidadeDAO = new UnidadeDAO();
$tipoDAO = new TipoDAO();
$servicoDAO = new ServicoDAO();

$id_modulo = filter_input(INPUT_GET, "id");
$id_orc = filter_input(INPUT_GET, "id_orc");

$orcamento = $orcamentoDAO->getOrcamento($id_orc);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SistemaUtil::SISTEMA_TITULO ?> | Or&ccedil;amento | Composi&ccedil;&atilde;o de Custo</title>
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

        <script type="text/javascript" src="../../scripts/jquery-mensagens.js"></script>
        <script type="text/javascript" src="../../scripts/jquery-funcoes.js"></script>
        <script type="text/javascript" src="../../scripts/jquery-dialog-form-orc.js"></script>
        <script type="text/javascript" src="../../scripts/jquery-ajax.js"></script>
    </head>
    <body>
        
        <? include "../../componentesPagina/msgInfo.php"; ?>
        
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
                    <a href="orcamento.php?id=<?= $id_modulo ?>&id_orc=<?= $id_orc ?>">OR&Ccedil;AMENTO</a>
                    ::
                </div>

                <div class="modulo">

                    <? include "../../componentesPagina/msgDialog.php"; ?>

                    <!-- COMPOSICAO -->

                    <div class="oculto" id="dialogFormAddComp">
                        <br />
                        <form method="post" name="frmAdicionarComp" id="frmAdicionarComp" action="">
                            <input type="hidden" name="idOrc" id="idOrc" value="<?= $id_orc ?>"/>
                            <input type="hidden" name="compCod" id="compCod" value=""/>
                            <label class="negrito">Descri&ccedil;&atilde;o:</label> &nbsp; 
                            <input type="text" name="compDesc" id="compDesc" size="43" maxlength="200" class="retiraAcento focus"/> <br /> <br />
                            <label class="negrito">Resultado:</label> <br /> <br />
                            <select name="composicoes" id="composicoes" size="5" class="selectComp"></select> <br /> <br />
                            <label class="negrito">Composi&ccedil;&atilde;o:</label> &nbsp; <label id="composicao" class="italico"></label> <br /> <br />
                            <label class="negrito">Insumos:</label> <br /> <br />
                            <select name="insumos" id="insumos" size="5" class="selectIns"></select>
                        </form>
                    </div>

                    <div class="oculto" id="dialogFormCopComp">
                        <br />
                        <form method="post" name="frmCopiarComp" id="frmCopiarComp" action="">
                            <input type="hidden" name="id_orc_cop" id="id_orc_cop" value="<?= $id_orc ?>"/>
                            <label class="negrito">Nome da obra:</label> &nbsp; 
                            <input type="text" name="nome_obra" id="nome_obra" size="43" maxlength="200" class="retiraAcento focus"/> <br /> <br />
                            <label class="negrito">Resultado:</label> <br /> <br />
                            <select name="obras" id="obras" size="5" class="selectObras"></select> <br /> <br />
                            <label class="negrito">Obra:</label> &nbsp; <label id="obra" class="italico"></label> <br /> <br />
                            <label class="negrito">Composi&ccedil;&otilde;es:</label> <br /> <br />
                            <select name="composicoes_cop" id="composicoes_cop" size="5" class="selectComp"></select> <br /> <br />
                            <label class="negrito">Composi&ccedil;&otilde;es:</label> &nbsp; <label id="composicao_cop" class="italico"></label> 
                        </form>
                    </div>

                    <div class="oculto" id="dialogFormComp">
                        <br />
                        <form method="post" name="frmCadastrarComp" id="frmCadastrarComp" action="">
                            <input type="hidden" name="id_orc" id="id_orc" value="<?= $id_orc ?>"/>
                            <label class="negrito">Descri&ccedil;&atilde;o:</label> <br /> <br />
                            <input type="text" name="descricao" id="descricao" class="caixaAlta retiraAcento focus" size="43" maxlength="200" /> 
                            <br /> <br />
                            <label class="negrito">Servi&ccedil;o:</label> &nbsp; CO <br /> <br />
                            <label class="negrito">Unidade:</label> &nbsp; 
                            <?= $unidadeDAO->getSelectUnidade("", "composicao") ?> <br /> <br />
                            <label class="negrito">Tipo:</label> &nbsp;
                            <?= $tipoDAO->getSelectTipo("", "composicao"); ?>
                        </form>
                    </div>

                    <div class="oculto" id="dialogConfirmComp">
                        <p>Tem certeza que quer excluir esta composi&ccedil;&atilde;o?</p>
                    </div>

                    <div class="oculto" id="dialogFromEddDescComp">
                        <br />
                        <form method="post" name="frmEditarDescComp" id="frmEditarDescComp" action="">
                            <input type="hidden" name="id_modulo_edd_desc" id="id_modulo_edd_desc" value="<?= $id_modulo ?>"/>
                            <label class="negrito">Descri&ccedil;&atilde;o Atual:</label> <br /> <br />
                            <input type="text" name="descricao_edd" id="descricao_edd" class="caixaAlta retiraAcento focus" size="52" maxlength="200"/>
                        </form>
                    </div>

                    <!-- B. D. I. -->

                    <div class="oculto" id="dialogFormBDI">
                        <br />
                        <form method="post" name="frmEditarBDI" id="frmEditarBDI" action="">
                            <input type="hidden" name="id_orc" id="id_orc" value="<?= $id_orc ?>"/>
                            <label class="negrito">B. D. I.:</label> &nbsp;
                            <input type="text" name="bdi" id="bdi" class="focus decimal" size=10" />
                        </form>
                    </div>

                    <!-- ENCARGOS SOCIAIS -->

                    <div class="oculto" id="dialogFormES">
                        <br />
                        <form method="post" name="frmEditarES" id="frmEditarES" action="">
                            <input type="hidden" name="id_orc" id="id_orc" value="<?= $id_orc ?>"/>
                            <label class="negrito">Enc. Sociais:</label> &nbsp;
                            <input type="text" name="encargo_social" id="encargo_social" class="focus decimal" size=10" />
                        </form>
                    </div>

                    <!-- SUBCOMPOSICAO -->

                    <div class="oculto" id="dialogFormSubComp">
                        <br />
                        <form method="post" name="frmAdicionarSubComp" id="frmAdicionarSubComp" action="">
                            <input type="hidden" name="id_orc" id="id_orc" value="<?= $id_orc ?>"/>
                            <label class="negrito">Composi&ccedil;&atilde;o:</label> &nbsp; <br /> <br />
                            <select name="subcomposicao" id="subcomposicao"></select> <br /> <br />
                            <label class="negrito">Quantidade:</label> &nbsp;
                            <input type="text" name="qtd_subcomp" id="qtd_subcomp" class="decimal" size=10" />
                        </form>
                    </div>

                    <div class="oculto" id="dialogFormSubcomp2">
                        <br />
                        <form method="post" name="frmEddOrcCompSub" id="frmEddOrcCompSub" action="">
                            <input type="hidden" name="id_subcomp" id="id_subcomp" value=""/>
                            <label class="negrito">Quantidade.:</label> &nbsp;
                            <input type="text" name="qtd_subcomp2" id="qtd_subcomp2" class="decimal" size=10" />
                        </form>
                    </div>

                    <div class="oculto" id="dialogConfirmSubComp">
                        <p>Tem certeza que quer excluir esta subcomposi&ccedil;&atilde;o?</p>
                    </div>

                    <div class="oculto" id="dialogConfirmDesatComp">
                        <p id="msgAtivar">Tem certeza que quer desativar esta composi&ccedil;&atilde;o?</p>
                    </div>

                    <!-- INSUMO -->

                    <div class="oculto" id="dialogFormEditarIns">
                        <br />
                        <form method="post" name="frmEditarOrcCompIns" id="frmEditarOrcCompIns" action="">
                            <input type="hidden" name="insId" id="insId" value=""/>
                            <label class="negrito">Quantidade:</label> &nbsp;
                            <input type="text" name="insQtd" id="insQtd" class="decimal" size="10" value=""/> <br /> <br />
                            <label class="negrito">Pre&ccedil;o:</label> &nbsp;
                            <input type="text" name="insPreco" id="insPreco" class="decimal" size="10" value=""/>
                        </form>
                    </div>

                    <div class="oculto" id="dialogFormAddOrcCompIns">
                        <br />
                        <form method="post" name="frmAddOrcCompIns" id="frmAddOrcCompIns" action="">
                            <input type="hidden" name="id_orc_comp" id="id_orc_comp" value=""/>
                            <label class="negrito">Filtro de Pesquisa:</label> &nbsp; <br /> <br />
                            <label class="negrito">Servi&ccedil;o:</label> &nbsp;
                            <?= $servicoDAO->getSelectServico("", ""); ?> <br /> <br />
                            <label class="negrito">Descri&ccedil;&atilde;o:</label> &nbsp;
                            <input type="text" name="insDesc" id="insDesc" size="47" maxlength="150" class="retiraAcento"/> <br /> <br />                            
                            <hr /> <br />
                            <label class="negrito">Resultado:</label> <br /> <br />
                            <select name="insAdd" id="insAdd" size="5" class="selectIns"></select> <br /> <br />
                            <hr /> <br />
                            <label class="negrito">Insumo:</label> &nbsp; <label id="insumo" class="italico"></label> <br /> <br />
                        </form>
                    </div>

                    <div class="extras">
                        <ul>
                            <li id="adicionarComp">
                                <a class="cursor">
                                    <img src="../../imagens/icones/adicionar.png" alt=""/>
                                    <label>Adicionar Composi&ccedil;&atilde;o</label>
                                </a>
                            </li>
                            <li>&nbsp;&nbsp;</li>
                            <li id="copiarComp">
                                <a class="cursor">
                                    <img src="../../imagens/icones/adicionar.png" alt=""/>
                                    <label>Copiar Composi&ccedil;&atilde;o</label>
                                </a>
                            </li>
                            <li>&nbsp;&nbsp;</li>
                            <li id="editarBDI">
                                <a class="cursor">
                                    <img src="../../imagens/icones/editar.png" alt=""/>
                                    <label>Editar B.D.I.</label>
                                </a>
                            </li>
                            <li>&nbsp;&nbsp;</li>
                            <li id="editarES">
                                <a class="cursor">
                                    <img src="../../imagens/icones/editar.png" alt=""/>
                                    <label>Editar Enc. Sociais</label>
                                </a>
                            </li>
                            <li>&nbsp;&nbsp;</li>
                            <li id="cadastrarNovaComp">
                                <a class="cursor">
                                    <img src="../../imagens/icones/adicionar.png" alt=""/>
                                    <label>Cadastrar Composi&ccedil;&atilde;o</label>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tabela">
                        <table>
                            <thead>
                                <tr class="high textoCentro">
                                    <th colspan="4">RELA&Ccedil;&Atilde;O DE COMPOSI&Ccedil;&Atilde;O DE CUSTO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="low textoEsq">
                                    <td>OBRA: &nbsp; <?= $orcamento["nome_obra"] ?></td>
                                    <td>CLIENTE: &nbsp; <?= $orcamento["nome_cliente"] ?> </td>
                                    <td colspan="2">LOCAL: &nbsp; <?= $orcamento["local"] ?></td>
                                </tr>
                                <tr class="low textoEsq">
                                    <td>&Aacute;REA: &nbsp; <?= NumeroUtil::formatar($orcamento["area"], NumeroUtil::NUMERO_BRA) ?> M2</td>
                                    <td>VALOR/M2: &nbsp; <? ?></td>
                                    <td>B.D.I: &nbsp; <?= NumeroUtil::formatar($orcamento["bdi"], NumeroUtil::NUMERO_BRA) ?>%</td>
                                    <td>ENCARGO SOCIAL: &nbsp; <?= NumeroUtil::formatar($orcamento["encargo_social"], NumeroUtil::NUMERO_BRA) ?>%</td>
                                </tr>
                            </tbody>
                        </table>

                        <table>
                            <thead>
                                <tr class="high">
                                    <th>C&Oacute;DIGO</th>
                                    <th>DESCRI&Ccedil;&Atilde;O</th>
                                    <th>UNIDADE</th>
                                    <th>QUANTIDADE</th>
                                    <th>PRE&Ccedil;O (R$)</th>
                                    <th>VALOR (R$)</th>
                                    <th>PERCENTUAL (%)</th>
                                    <th>DATA</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <?= $orcComposicaoDAO->listar($id_orc, $orcamento["encargo_social"], $orcamento["bdi"]); ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>