<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/OrcamentoDAO.php";

$orcamentoDAO = new OrcamentoDAO();

$id_orc = filter_input(INPUT_POST, "idOrc");
$codigo = filter_input(INPUT_POST, "compCod");

if (!empty($codigo)) {
    if ($orcamentoDAO->adicionarComposicao($id_orc, $codigo)) {
        echo "TRUE";
    } else {
        echo "FALSE";
    }
} else {
    echo "FALSE";
}
?>  