<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/TituloComposicaoDAO.php";
include_once "../../classes/modelo/OrcComposicao.php";
include_once "../../classes/modelo/TituloComposicao.php";

$orcTitCompDAO = new TituloComposicaoDAO();
$orcComposicao = new OrcComposicao();
$orcTitComposicao = new TituloComposicao();

$id_orc_comp = filter_input(INPUT_POST, "idOrcComp");
$quantidade = filter_input(INPUT_POST, "quantidade");
$id_orc_titcomp = filter_input(INPUT_POST, "id");
$num_item = filter_input(INPUT_POST, "item");

$orcComposicao->setId($id_orc_comp);
$orcComposicao->setQuantidade($quantidade);

$orcTitComposicao->setId($id_orc_titcomp);
$orcTitComposicao->setNumeroItem($num_item);
$orcTitComposicao->setComposicao($orcComposicao);

if ($orcTitCompDAO->editar($orcTitComposicao)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>