<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/OrcamentoDAO.php";

$orcamentoDAO = new OrcamentoDAO();

$id_orc = filter_input(INPUT_POST, "id_orc");
$bdi = filter_input(INPUT_POST, "bdi");

if ($orcamentoDAO->editarBDI($id_orc, $bdi)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  