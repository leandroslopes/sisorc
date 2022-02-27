<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/OrcComposicaoInsumoDAO.php";
include_once "../../classes/modelo/OrcComposicao.php";
include_once "../../classes/modelo/OrcComposicaoInsumo.php";
include_once "../../classes/modelo/Insumo.php";

$orcCompInsDAO = new OrcComposicaoInsumoDAO();
$orcComposicao = new OrcComposicao();
$orcCompIns = new OrcComposicaoInsumo();
$insumo = new Insumo();

$id_orc_comp = filter_input(INPUT_POST, "id_orc_comp");
$cod_insumo = filter_input(INPUT_POST, "insAdd");

$orcComposicao->setId($id_orc_comp);
$insumo->setCodigo($cod_insumo);
$orcCompIns->setOrcComposicao($orcComposicao);
$orcCompIns->setInsumo($insumo);

if ($orcCompInsDAO->adicionar($orcCompIns)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  