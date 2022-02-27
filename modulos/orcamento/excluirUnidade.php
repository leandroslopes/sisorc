<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/UnidadeDAO.php";

$unidadeDAO = new UnidadeDAO();

$unidade = filter_input(INPUT_POST, "nomeUnidade");

if ($unidadeDAO->excluir($unidade)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  