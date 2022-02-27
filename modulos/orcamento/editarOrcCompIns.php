<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/OrcComposicaoInsumoDAO.php";
include_once "../../classes/modelo/OrcComposicaoInsumo.php";

$orcCompInsDAO = new OrcComposicaoInsumoDAO();
$orcCompIns = new OrcComposicaoInsumo();

$id = filter_input(INPUT_POST, "insId");
$quantidade = filter_input(INPUT_POST, "insQtd");
$preco = filter_input(INPUT_POST, "insPreco");

$orcCompIns->setId($id);
$orcCompIns->setQuantidade($quantidade);
$orcCompIns->setPreco($preco);

if ($orcCompInsDAO->editarQtdEPreco($orcCompIns)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  