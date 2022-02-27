<?

session_cache_limiter('nocache');
session_cache_expire(999);
session_start();

include_once "../../classes/dao/SubtituloDAO.php";
include_once "../../classes/modelo/Titulo.php";
include_once "../../classes/modelo/Subtitulo.php";
include_once "../../classes/util/StringUtil.php";

$orcSubtituloDAO = new SubtituloDAO();
$orcTitulo = new Titulo();
$orcSubtitulo = new Subtitulo();

$id_titulo = filter_input(INPUT_POST, "idOrcTit");
$subtitulo = filter_input(INPUT_POST, "nome");
$num_item = filter_input(INPUT_POST, "item");

$orcTitulo->setId($id_titulo);
$orcSubtitulo->setTitulo($orcTitulo);
$orcSubtitulo->setSubtitulo($subtitulo);
$orcSubtitulo->setNumeroItem($num_item);

if (empty(StringUtil::removeEspacos($subtitulo)) && empty(StringUtil::removeEspacos($num_item))) {
    echo "FALSE";
} else if ($orcSubtituloDAO->subtituloJaExistente($orcSubtitulo)) {
    echo "CADASTRADO";
} else if ($orcSubtituloDAO->adicionar($orcSubtitulo)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>