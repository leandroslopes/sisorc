<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/TituloDAO.php";
include_once "../../classes/modelo/Orcamento.php";
include_once "../../classes/modelo/Titulo.php";
include_once "../../classes/util/StringUtil.php";

$orcTituloDAO = new TituloDAO();
$orcamento = new Orcamento();
$orcTitulo = new Titulo();

$id_orc = filter_input(INPUT_POST, "idOrc");
$titulo = filter_input(INPUT_POST, "nome");
$num_item = filter_input(INPUT_POST, "item");

$orcamento->setId($id_orc);
$orcTitulo->setOrcamento($orcamento);
$orcTitulo->setTitulo($titulo);
$orcTitulo->setNumeroItem($num_item);

if (empty(StringUtil::removeEspacos($titulo))) {
    echo "FALSE";
} else if ($orcTituloDAO->tituloJaExistente($orcTitulo)) {
    echo "CADASTRADO";
} else if ($orcTituloDAO->adicionar($orcTitulo)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  