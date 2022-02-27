<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/OrcamentoDAO.php";

$orcamentoDAO = new OrcamentoDAO();

$id_orc = filter_input(INPUT_POST, "id_orc");
$encargo_social = filter_input(INPUT_POST, "encargo_social");

if ($orcamentoDAO->editarES($id_orc, $encargo_social)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  