<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/ComposicaoDAO.php";

$composicaoDAO = new ComposicaoDAO();

if ($composicaoDAO->isCadastrado($_REQUEST)) {
    echo "CADASTRADO";
} else if ($composicaoDAO->cadastrarNova($_REQUEST)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  