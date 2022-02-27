<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/util/SistemaUtil.php";
include_once "../../classes/util/DataUtil.php";
include_once "../../classes/modelo/Funcionario.php";
include_once "../../classes/dao/ModuloDAO.php";

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
                    <a href="">MEU PERFIL</a>
                    ::
                </div>

                <div class="meuPerfil">
                    <div class="fotoPerfil">
                        <img src="../../imagens/outros/sem_foto.jpg" alt="" title=""/>
                    </div>
                    <div class="nomePerfil">
                        <p class="textoPequeno">
                            <?= $_SESSION["funcionario"]["nomeFuncionario"] ?> <br />
                            <?= $_SESSION["funcionario"]["nomeCargo"] ?>
                        </p>
                    </div>
                </div>

                <div class="opcoes">
                    <ul>
                        <li>
                            <a href="frmAlterarSenha.php?id=<?= $id_modulo ?>">
                                Alterar Senha
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>