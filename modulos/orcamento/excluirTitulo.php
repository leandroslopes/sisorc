<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/TituloDAO.php";

$orcTituloDAO = new TituloDAO();

$id = filter_input(INPUT_POST, "id");

if ($orcTituloDAO->estaRelacionado($id)) {
    echo "RELACIONADO";
} else if ($orcTituloDAO->excluir($id)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  