<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/SubmoduloDAO.php";

$submoduloDAO = new SubmoduloDAO();

$id = filter_input(INPUT_POST, "id");

if ($submoduloDAO->excluirAcessoCargo($id)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  