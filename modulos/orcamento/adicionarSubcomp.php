<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/OrcComposicaoSubcomposicaoDAO.php";
include_once "../../classes/modelo/OrcComposicao.php";
include_once "../../classes/modelo/OrcComposicaoSubcomposicao.php";

$orcCompSubcompDAO = new OrcComposicaoSubcomposicaoDAO();
$orcComp = new OrcComposicao();
$orcSubComp = new OrcComposicao();
$orcCompSubcomp = new OrcComposicaoSubcomposicao();

$id_orc_comp = filter_input(INPUT_POST, "idOrcComp");
$id_orc_subcomp = filter_input(INPUT_POST, "idOrcSubcomp");
$quantidade = filter_input(INPUT_POST, "quantidade");

$orcComp->setId($id_orc_comp);
$orcSubComp->setId($id_orc_subcomp);
$orcSubComp->setQuantidade($quantidade);

$orcCompSubcomp->setOrcComposicao($orcComp);
$orcCompSubcomp->setOrcSubcomp($orcSubComp);

if ($orcCompSubcompDAO->adicionar($orcCompSubcomp)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  