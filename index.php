<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "classes/dao/ModuloDAO.php";
include_once "classes/util/SistemaUtil.php";
include_once "classes/modelo/Funcionario.php";  

//if (Funcionario::estaLogado()) {
$moduloDAO = new ModuloDAO();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SistemaUtil::SISTEMA_TITULO ?> | Login</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <link type="image/x-icon" rel="shortcut icon" href="imagens/icones/favicon.jpg" />
        <link type="text/css" rel="stylesheet" href="css/estilo.css"/>
        <script type="text/javascript" src="plugins/jquery-ui/js/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="plugins/jquery-validate/jquery.validate.js"></script>
        <script type="text/javascript" src="scripts/jquery-validar-forms.js"></script>
        <script type="text/javascript" src="scripts/jquery-funcoes.js"></script>
    </head>
    <body>
        <div class="corpo">
            <? include SistemaUtil::SISTEMA_URL . 'componentesPagina/cabecalho.php?id_modulo=login' ?>

            <div id="login">
                <?
                if (isset($_REQUEST["error"])) {
                    if ($_REQUEST["error"] == 1) {
                        ?>
                        <div id="erroLogin">
                            <p>C&oacute;digo e/ou senha incorretos!</p>
                        </div>
                        <?
                    }
                }
                ?>

                <div id="camposLogin">
                    <form method="post" name="login" id="formLogin" action="login.php">
                        <div id="campoCodigo" class="negrito">
                            <label>C&Oacute;DIGO:</label> <br />
                            <input type="text" name="codigo" id="codigo" class="focus" maxlength="6" size="38"/>
                        </div>
                        <br />
                        <div id="campoSenha" class="negrito">
                            <label>SENHA:</label> <br />
                            <input type="password" name="senha" id="senha" maxlength="6" size="38"/>
                        </div>
                        <br />
                        <div id="botaoLogin">
                            <input type="submit" name="btLogin" id="btLogin" value="ENTRAR" />
                        </div>
                        <div id="esqueceuSenha">
                            <a href="#">Esqueceu Sua Senha?</a>
                        </div> 
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>