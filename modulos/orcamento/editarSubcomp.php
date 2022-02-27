<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/OrcComposicaoDAO.php";
include_once "../../classes/modelo/OrcComposicao.php";

$orcComposicaoDAO = new OrcComposicaoDAO();
$orcSubComp = new OrcComposicao();

$id = filter_input(INPUT_POST, "id_subcomp");
$quantidade = filter_input(INPUT_POST, "qtd_subcomp2");

$orcSubComp->setId($id);
$orcSubComp->setQuantidade($quantidade);

if ($orcComposicaoDAO->alterarQuantidade($orcSubComp)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  