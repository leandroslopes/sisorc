<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/OrcComposicaoDAO.php";
include_once "../../classes/modelo/OrcComposicao.php";

$orcComposicaoDAO = new OrcComposicaoDAO();
$orcComposicao = new OrcComposicao();

$id_orc_comp = filter_input(INPUT_POST, "id");
$ativa = filter_input(INPUT_POST, "ativa");

$orcComposicao->setId($id_orc_comp);
$orcComposicao->setAtiva($ativa);

echo $orcComposicaoDAO->ativar($orcComposicao);
?>  