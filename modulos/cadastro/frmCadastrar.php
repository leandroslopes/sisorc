<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/util/SistemaUtil.php";
include_once "../../classes/util/DataUtil.php";
include_once "../../classes/util/SexoUtil.php";
include_once "../../classes/util/ArrayUtil.php";
include_once "../../classes/util/EstadoCivilUtil.php";
include_once "../../classes/dao/ModuloDAO.php";
include_once "../../classes/dao/CadastroDAO.php";

$moduloDAO = new ModuloDAO();

$cadastro = "";

$id_modulo = filter_input(INPUT_GET, "id");
$id_cad = filter_input(INPUT_GET, "id_cad");
$bt_cadastrar = filter_input(INPUT_POST, "btCadastrar");
$id_cadastro = filter_input(INPUT_POST, "idCadastro");

if (isset($id_cad)) {
    $id_cad = filter_input(INPUT_GET, "id_cad");
    $cadastroDAO = new CadastroDAO();
    $cadastro = $cadastroDAO->getCadastro($id_cad);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SistemaUtil::SISTEMA_TITULO ?> | Cadastro | Cadastrar</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="image/x-icon" rel="icon" href="../../imagens/favicon.png" />
        <link type="text/css" rel="stylesheet" href="../../css/estilo.css"/>
        <script type="text/javascript" src="../../scripts/valida-forms.js"></script>

        <link type="text/css" rel="stylesheet" href="../../plugins/jquery-ui/css/custom-theme/jquery-ui-1.10.1.custom.css"/>  
        <script type="text/javascript" src="../../plugins/jquery-ui/js/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="../../plugins/jquery-ui/js/jquery-ui-1.10.1.custom.js"></script>

        <script type="text/javascript" src="../../plugins/masked-input/jquery.maskedinput.js"></script>
        <script type="text/javascript" src="../../scripts/jquery-masks.js"></script>

        <script type="text/javascript" src="../../plugins/jquery-validate/jquery.validate.js"></script>
        <script type="text/javascript" src="../../scripts/jquery-validar-forms.js"></script>

        <script type="text/javascript" src="../../scripts/jquery-mensagens.js"></script>
        <script type="text/javascript" src="../../scripts/jquery-funcoes.js"></script>
    </head>
    <body>

        <? include "../../componentesPagina/msgInfo.php"; ?>

        <?
        if (!empty($bt_cadastrar)) {
            $cadastroDAO = new CadastroDAO(); 

            if (empty($id_cadastro)) {                
                if ($cadastroDAO->cadastrar($_REQUEST)) {
                    unset($_REQUEST);
                    ?>
                    <script>
                        $(this).showMsgSucesso('index.php?id=<?= $id_modulo ?>');
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
                if ($cadastroDAO->editar($_REQUEST)) {
                    unset($_REQUEST);
                    ?>
                    <script>
                        $(this).showMsgSucesso('index.php?id=<?= $id_modulo ?>');
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
            <? include SistemaUtil::SISTEMA_URL . 'componentesPagina/cabecalho.php?id_modulo=' . $id_modulo ?>

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
                    <a href="frmCadastrar.php?id=<?= $id_modulo ?>">CADASTRAR</a>
                    ::
                </div>

                <form method="post" name="frmCadastrar" id="frmCadastrar" action="">
                    <input type="hidden" name="idCadastro" id="idCadastro" value="<?= $id_cad ?>"/>
                    <div class="formulario">
                        <br />
                        <div class="campo">
                            <label class="negrito">Nome:</label> <br />
                            <input type="text" name="nome" id="nome" maxlength="255" size="90" class="caixaAlta retiraAcento focus"
                                   value="<?= ArrayUtil::array_get('nome', $cadastro) ?>"/>
                        </div>        
                        <br />
                        <div class="campo">
                            <label class="negrito">Data de nascimento:</label> <br />
                            <input type="text" name="dt_nasc" id="data" maxlength="10" size="10" value="<?= ArrayUtil::array_get('dt_nasc', $cadastro) ?>"/>
                        </div>
                        <br />
                        <div class="campo">
                            <label class="negrito">Sexo:</label> <br />
                            <?= SexoUtil::getSelect(ArrayUtil::array_get('sexo', $cadastro)) ?>
                        </div>
                        <br />
                        <div class="campo">
                            <label class="negrito">Estado Civil:</label> <br />
                            <?= EstadoCivilUtil::getSelect(ArrayUtil::array_get('est_civil', $cadastro)) ?>
                        </div>
                        <br />
                        <div class="campo">
                            <label class="negrito">CPF:</label> <br />
                            <input type="text" name="cpf" id="cpf" maxlength="13" size="20" value="<?= ArrayUtil::array_get('cpf', $cadastro) ?>"/>
                        </div>
                        <br />
                        <div class="campo">
                            <label class="negrito">RG:</label> <br />
                            <input type="text" name="rg" id="rg" maxlength="12" size="20" value="<?= ArrayUtil::array_get('rg', $cadastro) ?>"/>
                        </div>
                        <br />
                        <div class="campo">
                            <label class="negrito">PAI:</label> <br />
                            <input type="text" name="pai" id="pai" maxlength="150" size="90" class="caixaAlta retiraAcento" 
                                   value="<?= ArrayUtil::array_get('pai', $cadastro) ?>"/>
                        </div>
                        <br />
                        <div class="campo">
                            <label class="negrito">M&Atilde;E:</label> <br />
                            <input type="text" name="mae" id="mae" maxlength="150" size="90" class="caixaAlta retiraAcento"
                                   value="<?= ArrayUtil::array_get('mae', $cadastro) ?>"/>
                        </div>
                        <br />
                        <div class="campo">
                            <label class="negrito">Endere&ccedil;o:</label> <br />
                            <input type="text" name="endereco" id="endereco" maxlength="100" size="90" class="caixaAlta retiraAcento" 
                                   value="<?= ArrayUtil::array_get('endereco', $cadastro) ?>"/>
                        </div>
                        <br />
                        <div class="campo">
                            <label class="negrito">Bairro:</label> <br />
                            <input type="text" name="bairro" id="bairro" maxlength="50" size="50" class="caixaAlta retiraAcento" 
                                   value="<?= ArrayUtil::array_get('bairro', $cadastro) ?>"/>
                        </div>
                        <br />
                        <div class="campo">
                            <label class="negrito">Cidade:</label> <br />
                            <input type="text" name="cidade" id="cidade" maxlength="50" size="50" class="caixaAlta retiraAcento" 
                                   value="<?= ArrayUtil::array_get('cidade', $cadastro) ?>"/> Ex.: S&atilde;o Lu&iacute;s/MA
                        </div>
                        <br />
                        <div class="campo">
                            <label class="negrito">Telefone:</label> <br />
                            <input type="text" name="telefone" id="telefone" maxlength="13" size="13" value="<?= ArrayUtil::array_get('telefone', $cadastro) ?>"/>
                        </div>
                        <br />
                        <div class="campo">
                            <label class="negrito">Celular:</label> <br />
                            <input type="text" name="celular" id="celular" maxlength="13" size="13" value="<?= ArrayUtil::array_get('celular', $cadastro) ?>"/>
                        </div>
                        <br />
                        <div class="campo">
                            <label class="negrito">Email:</label><br />
                            <input type="text" name="email" id="email" maxlength="35" size="50" class="retiraAcento" 
                                   value="<?= ArrayUtil::array_get('email', $cadastro) ?>"/>
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
