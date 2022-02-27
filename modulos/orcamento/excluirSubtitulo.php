<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/SubtituloDAO.php";

$orcSubtituloDAO = new SubtituloDAO();

$id = filter_input(INPUT_POST, "id");

if ($orcSubtituloDAO->estaRelacionado($id)) {
    echo "RELACIONADO";
} else if ($orcSubtituloDAO->excluir($id)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  