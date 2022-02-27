<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "classes/util/SistemaUtil.php";
include_once "classes/util/DataUtil.php";
include_once "classes/dao/ModuloDAO.php";
include_once "classes/modelo/Funcionario.php";

//if (Funcionario::estaLogado()) {
$moduloDAO = new ModuloDAO();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= SistemaUtil::SISTEMA_TITULO ?> | In&iacute;cio</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <meta name="author" content="Leandro Lopes, programador.leandrolopes@gmail.com" />
        <link type="image/x-icon" rel="icon" href="imagens/icones/favicon.jpg" />
        <link type="text/css" rel="stylesheet" href="css/estilo.css"/>
    </head>
    <body>
        <div class="corpo">
            <? include SistemaUtil::SISTEMA_URL . 'componentesPagina/cabecalho.php?id_modulo=inicio' ?>

            <div class="extras">
                <ul>
                    <li><?= DataUtil::imprimirDataAtual() ?></li>
                    <li>|</li>
                    <li>
                        <a href="sair.php">
                            <img src="imagens/icones/sair.png"/>
                            <label>Sair do sistema</label>
                        </a>
                    </li>
                </ul>
            </div> <br />

            <div class="modulos">
                <ul>
                    <?= $moduloDAO->getModulos($_SESSION["funcionario"], FALSE); ?>
                </ul>
            </div> <br />
        </div>
    </body>
</html>