<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/OrcComposicaoSubcomposicaoDAO.php";
include_once "../../classes/modelo/OrcComposicaoSubcomposicao.php";

$orcSubCompDAO = new OrcComposicaoSubcomposicaoDAO();
$orcSubComp = new OrcComposicaoSubcomposicao();

$id = filter_input(INPUT_POST, "id");

$orcSubComp->setId($id);

if ($orcSubCompDAO->excluir($orcSubComp)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  