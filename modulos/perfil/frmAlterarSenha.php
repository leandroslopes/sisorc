<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/util/SistemaUtil.php";
include_once "../../classes/util/DataUtil.php";
include_once "../../classes/dao/ModuloDAO.php";
include_once "../../classes/dao/FuncionarioDAO.php";

$moduloDAO = new ModuloDAO();

$id_modulo = filter_input(INPUT_GET, "id");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SistemaUtil::SISTEMA_TITULO ?> | Meu Perfil</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="image/x-icon" rel="icon" href="../../imagens/icones/favicon.png" />
        <link type="text/css" rel="stylesheet" href="../../css/estilo.css"/>

        <script type="text/javascript" src="../../plugins/jquery-ui/js/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="../../plugins/jquery-ui/js/jquery-ui-1.10.1.custom.js"></script>
        <link type="text/css" rel="stylesheet" href="../../plugins/jquery-ui/css/custom-theme/jquery-ui-1.10.1.custom.css"/>

        <script type="text/javascript" src="../../plugins/jquery-validate/jquery.validate.js"></script> 
        <script type="text/javascript" src="../../scripts/jquery-validar-forms.js"></script>

        <script type="text/javascript" src="../../scripts/jquery-mensagens.js"></script>
        <script type="text/javascript" src="../../scripts/jquery-funcoes.js"></script>
    </head>
    <body>

        <div class="msgSucesso" title="ALTERAR SENHA">A senha foi alterada com sucesso. <br /> Entre no sistema com a nova senha.</div>
        <div class="msgErro" title="ALTERAR SENHA">A senha n&atilde; foi alterada. <br /> Tente novamente.</div>
        <?
        $bt_alterar = filter_input(INPUT_POST, "btAlterar");
        
        if (!empty($bt_alterar)) {
            $funcionarioDAO = new FuncionarioDAO();
            
            $senha =  filter_input(INPUT_POST, "senha");
            
            if ($funcionarioDAO->alterarSenha($senha)) {
                ?>
                <script>
                    $(this).showMsgSucesso('index.php?id=<?= $id_modulo ?>');
                </script>
                <?
            } else {
                ?>
                <script>
                    $(this).showMsgErro();
                </script>
                <?
            }
        }
        ?>        

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
                    <a href="index.php?id=<?= $id_modulo ?>">MEU PERFIL</a>
                    ::
                    <a href="frmAlterarSenha.php?id=<?= $id_modulo ?>">ALTERAR SENHA</a>
                    ::
                </div>

                <form method="post" name="frmAlterarSenha" id="frmAlterarSenha" action="" >
                    <div class="formulario">
                        <br />
                        <div class="campo negrito">
                            <label for="senha">Senha:</label> <br />
                            <input type="password" name="senha" id="senha" class="focus" maxlength="6" size="25"/>
                        </div> <br />
                        <div class="campo negrito">
                            <label for="confSenha">Confirma&ccedil;&atilde;o  de Senha:</label> <br />
                            <input type="password" name="confSenha" id="confSenha" maxlength="6" size="25"/>
                        </div> <br />
                        <div class="botao">
                            <input type="submit" name="btAlterar" value="Alterar"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>