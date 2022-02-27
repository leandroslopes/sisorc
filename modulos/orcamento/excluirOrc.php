<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/OrcamentoDAO.php";

$orcamentoDAO = new OrcamentoDAO();

$id = filter_input(INPUT_POST, "id");

if ($orcamentoDAO->temComposicao($id)) {
    echo "CADASTRADO";
} else if ($orcamentoDAO->excluir($id)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  