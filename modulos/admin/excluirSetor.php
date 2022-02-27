<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/ModuloDAO.php";

$moduloDAO = new ModuloDAO();

$id = filter_input(INPUT_POST, "id");

if ($moduloDAO->excluirAcessoSetor($id)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  