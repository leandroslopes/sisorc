<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/ComposicaoDAO.php";

$composicaoDAO = new ComposicaoDAO();

$id = filter_input(INPUT_POST, "id");

if ($composicaoDAO->excluirInsumo($id)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  