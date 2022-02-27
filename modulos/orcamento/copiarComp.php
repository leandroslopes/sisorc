<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/OrcComposicaoDAO.php";
include_once "../../classes/modelo/Orcamento.php";
include_once "../../classes/modelo/OrcComposicao.php";

$orcComposicaoDAO = new OrcComposicaoDAO();
$orcamento = new Orcamento();
$orcComposicao = new OrcComposicao();

$id_orc = filter_input(INPUT_POST, "idOrc");
$id_orc_comp = filter_input(INPUT_POST, "idOrcComp");

$orcamento->setId($id_orc);

$orcComposicao->setId($id_orc_comp);
$orcComposicao->setOrcamento($orcamento);

if ($orcComposicaoDAO->copiar($orcComposicao)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  