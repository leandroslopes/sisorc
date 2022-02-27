<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/util/SistemaUtil.php";
include_once "../../classes/util/DataUtil.php";
include_once "../../classes/util/ArrayUtil.php";
include_once "../../classes/util/NumeroUtil.php";
include_once "../../classes/dao/ModuloDAO.php";
include_once "../../classes/dao/OrcamentoDAO.php";

$moduloDAO = new ModuloDAO();

$orcamento = "";
$id_orc = filter_input(INPUT_GET, "id_orc");
$id_modulo = filter_input(INPUT_GET, "id");

if (isset($id_orc)) {
    $orcamentoDAO = new OrcamentoDAO();
    $orcamento = $orcamentoDAO->getOrcamento($id_orc);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SistemaUtil::SISTEMA_TITULO ?> | Or&ccedil;ameto | Cadastrar</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="image/x-icon" rel="icon" href="../../imagens/icones/favicon.png" />
        <link type="text/css" rel="stylesheet" href="../../css/estilo.css"/>

        <link type="text/css" rel="stylesheet" href="../../plugins/jquery-ui/css/custom-theme/jquery-ui-1.10.1.custom.css"/>  
        <script type="text/javascript" src="../../plugins/jquery-ui/js/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="../../plugins/jquery-ui/js/jquery-ui-1.10.1.custom.js"></script>

        <script type="text/javascript" src="../../plugins/jquery-maskmoney/jquery.maskMoney.js"></script>
        <script type="text/javascript" src="../../scripts/jquery-maskdecimal.js"></script>

        <script type="text/javascript" src="../../plugins/jquery-validate/jquery.validate.js"></script>
        <script type="text/javascript" src="../../scripts/jquery-validar-forms.js"></script>

        <script type="text/javascript" src="../../scripts/jquery-mensagens.js"></script>
        <script type="text/javascript" src="../../scripts/jquery-funcoes.js"></script>
    </head>
    <body>

        <? include "../../componentesPagina/msgInfo.php"; ?>

        <?
        $bt_cadastrar = filter_input(INPUT_POST, "btCadastrar");
        $id_orc_cad = filter_input(INPUT_POST, "id_orc_cad");
        
        if (!empty($bt_cadastrar)) {
            $orcamentoDAO = new OrcamentoDAO();

            if (empty($id_orc_cad)) {
                if ($orcamentoDAO->cadastrar($_REQUEST)) {
                    unset($_REQUEST);
                    ?>
                    <script>
                        $(this).showMsgSucesso('orcamentos.php?id=<?= $id_modulo ?>');
                    </script>
                    <?
                } else {
                    unset($_REQUEST);
                    ?>
                    <script>
                        $(this).showMsgErro();
                    </script>
                    <?
                }
            } else {
                if ($orcamentoDAO->editar($_REQUEST)) {
                    unset($_REQUEST);
                    ?>
                    <script>
                        $(this).showMsgSucesso('orcamentos.php?id=<?= $id_modulo ?>');
                    </script>
                    <?
                } else {
                    unset($_REQUEST);
                    ?>
                    <script>
                        $(this).showMsgErro();
                    </script>
                    <?
                }
            }
        }
        ?>

        <div class="corpo">
            <? include SistemaUtil::SISTEMA_URL . 'componentesPagina/cabecalho.php?id_modulo=' . $id_modulo; ?>

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
                    <a href="frmCadOrc.php?id=<?= $id_modulo ?>" title="">CADASTRAR</a>
                    ::
                </div>

                <form method="post" name="frmCadOrc" id="frmCadOrc" action="" >
                    <input type="hidden" name="id_orc_cad" id="id_orc_cad" value="<?= $id_orc ?>"/>
                    <div class="formulario">
                        <br />
                        <div class="campo">
                            <label class="negrito">Nome da obra:</label> <br />
                            <input type="text" name="nome_obra" id="nome_obra" maxlength="150" size="90" class="caixaAlta retiraAcento focus" 
                                   value="<?= ArrayUtil::array_get('nome_obra', $orcamento) ?>"/>
                        </div>        
                        <br />
                        <div class="campo">
                            <label class="negrito">Nome do cliente:</label> <br />
                            <input type="text" name="nome_cliente" id="nome_cliente" maxlength="150" size="90" class="caixaAlta retiraAcento" 
                                   value="<?= ArrayUtil::array_get('nome_cliente', $orcamento) ?>"/>
                        </div>        
                        <br />
                        <div class="campo">
                            <label class="negrito">Local:</label> <br />
                            <input type="text" name="local" id="local" maxlength="100" size=50" class="caixaAlta retiraAcento" 
                                   value="<?= ArrayUtil::array_get('local', $orcamento) ?>"/> Ex.: SAO LUIS/MA
                        </div>        
                        <br />
                        <div class="campo">
                            <label class="negrito">&Aacute;rea:</label> <br />
                            <input type="text" name="area" id="area" size=10" class="decimal" 
                                   value="<?= NumeroUtil::formatar(ArrayUtil::array_get('area', $orcamento), NumeroUtil::NUMERO_BRA) ?>"/>
                        </div>        
                        <br />
                        <div class="campo">
                            <label class="negrito">B.D.I.:</label> <br />
                            <input type="text" name="bdi" id="bdi" size=10" class="decimal" 
                                   value="<?= NumeroUtil::formatar(ArrayUtil::array_get('bdi', $orcamento), NumeroUtil::NUMERO_BRA) ?>"/>
                        </div>        
                        <br />
                        <div class="campo">
                            <label class="negrito">Encargo Social:</label> <br />
                            <input type="text" name="encargo_social" id="encargo_social" size=10" class="decimal" 
                                   value="<?= NumeroUtil::formatar(ArrayUtil::array_get('encargo_social', $orcamento), NumeroUtil::NUMERO_BRA) ?>"/>
                        </div>        
                        <br />
                        <div class="botao">
                            <input type="submit" name="btCadastrar" value="Cadastrar"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>