<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/TituloComposicaoDAO.php";
include_once "../../classes/modelo/Orcamento.php";
include_once "../../classes/modelo/OrcComposicao.php";
include_once "../../classes/modelo/Titulo.php";
include_once "../../classes/modelo/TituloComposicao.php";

$orcTitCompDAO = new TituloComposicaoDAO();
$orcamento = new Orcamento();
$orcComposicao = new OrcComposicao();
$orcTitulo = new Titulo();
$orcTitComposicao = new TituloComposicao();

$id_orc = filter_input(INPUT_POST, "id_orc_add_comp");
$id_orc_comp = filter_input(INPUT_POST, "orc_composicao");
$quantidade = filter_input(INPUT_POST, "qtd_add");
$id_titulo = filter_input(INPUT_POST, "orc_titulos");
$num_item = filter_input(INPUT_POST, "tit_comp_item_add");

$orcamento->setId($id_orc);
$orcComposicao->setId($id_orc_comp);
$orcComposicao->setQuantidade($quantidade);

$orcTitulo->setId($id_titulo);
$orcTitulo->setOrcamento($orcamento);

$orcTitComposicao->setTitulo($orcTitulo);
$orcTitComposicao->setNumeroItem($num_item);
$orcTitComposicao->setComposicao($orcComposicao);

if ($orcTitCompDAO->tituloCompJaCadastrado($orcTitComposicao)) {
    echo "CADASTRADO";
} else if ($orcTitCompDAO->adicionar($orcTitComposicao)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  