<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/SubtituloDAO.php";
include_once "../../classes/modelo/Subtitulo.php";
include_once "../../classes/util/StringUtil.php";

$orcSubtituloDAO = new SubtituloDAO();
$orcSubtitulo = new Subtitulo();

$id = filter_input(INPUT_POST, "id");
$subtitulo = filter_input(INPUT_POST, "nome");
$num_item = filter_input(INPUT_POST, "item");

$orcSubtitulo->setId($id);
$orcSubtitulo->setSubtitulo($subtitulo);
$orcSubtitulo->setNumeroItem($num_item);

if (empty(StringUtil::removeEspacos($subtitulo)) && empty(StringUtil::removeEspacos($num_item))) {
    echo "FALSE";
} else if ($orcSubtituloDAO->editar($orcSubtitulo)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  