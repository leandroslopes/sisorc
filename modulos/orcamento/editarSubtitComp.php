<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/SubtituloComposicaoDAO.php";
include_once "../../classes/modelo/OrcComposicao.php";
include_once "../../classes/modelo/SubtituloComposicao.php";

$orcSubtitCompDAO = new SubtituloComposicaoDAO();
$orcComposicao = new OrcComposicao();
$orcSubtitComp =  new SubtituloComposicao();

$id_orc_comp = filter_input(INPUT_POST, "idOrcComp");
$quantidade = filter_input(INPUT_POST, "quantidade");
$id_orc_subtitcomp = filter_input(INPUT_POST, "id");
$num_item = filter_input(INPUT_POST, "item");

$orcComposicao->setId($id_orc_comp);
$orcComposicao->setQuantidade($quantidade);

$orcSubtitComp->setId($id_orc_subtitcomp);
$orcSubtitComp->setNumeroItem($num_item);
$orcSubtitComp->setComposicao($orcComposicao);

if ($orcSubtitCompDAO->editar($orcSubtitComp)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  