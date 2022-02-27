<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/TituloComposicaoDAO.php";
include_once "../../classes/dao/SubtituloComposicaoDAO.php";

$orcTitCompDAO = new TituloComposicaoDAO();
$orcSubtitCompDAO = new SubtituloComposicaoDAO();

$id = filter_input(INPUT_POST, "id");
$tipo = filter_input(INPUT_POST, "tipo");

if ($tipo == "tit_comp") {
    if ($orcTitCompDAO->excluir($id)) {
        echo "TRUE";
    } else {
        echo "FALSE";
    }
} else {
    if ($orcSubtitCompDAO->excluir($id)) {
        echo "TRUE";
    } else {
        echo "FALSE";
    }
}
?>  