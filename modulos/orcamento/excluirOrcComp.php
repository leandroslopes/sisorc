<?
session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/OrcComposicaoDAO.php";
include_once "../../classes/modelo/Orcamento.php";
include_once "../../classes/modelo/Composicao.php";
include_once "../../classes/modelo/OrcComposicao.php";

$orcComposicaoDAO = new OrcComposicaoDAO();
$orcamento = new Orcamento();
$composicao = new Composicao();
$orcComposicao = new OrcComposicao();

$id_orc = filter_input(INPUT_POST, "idOrc");
$codigo = filter_input(INPUT_POST, "idComp");

$orcamento->setId($id_orc);
$composicao->setCodigo($codigo);

$orcComposicao->setOrcamento($orcamento);
$orcComposicao->setComposicao($composicao);

if ($orcComposicaoDAO->excluir($orcComposicao)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  