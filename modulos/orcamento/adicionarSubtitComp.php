<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/SubtituloComposicaoDAO.php";
include_once "../../classes/modelo/Orcamento.php";
include_once "../../classes/modelo/OrcComposicao.php";
include_once "../../classes/modelo/Titulo.php";
include_once "../../classes/modelo/Subtitulo.php";
include_once "../../classes/modelo/SubtituloComposicao.php";

$orcSubtitCompDAO = new SubtituloComposicaoDAO();
$orcamento = new Orcamento();
$orcComposicao = new OrcComposicao();
$orcTitulo = new Titulo();
$orcSubtitulo = new Subtitulo();
$orcSubtitComp = new SubtituloComposicao();

$id_orc = filter_input(INPUT_POST, "id_orc_add_comp2");
$id_orc_comp = filter_input(INPUT_POST, "orc_composicao");
$quantidade = filter_input(INPUT_POST, "qtd_add2");
$id_subtitulo = filter_input(INPUT_POST, "tit_subtitulos");
$num_item = filter_input(INPUT_POST, "item_add2");

$orcamento->setId($id_orc);
$orcComposicao->setId($id_orc_comp);
$orcComposicao->setQuantidade($quantidade);

$orcTitulo->setOrcamento($orcamento);

$orcSubtitulo->setId($id_subtitulo);
$orcSubtitulo->setTitulo($orcTitulo);

$orcSubtitComp->setSubtitulo($orcSubtitulo);
$orcSubtitComp->setComposicao($orcComposicao);
$orcSubtitComp->setNumeroItem($num_item);

if ($orcSubtitCompDAO->estaCadastrado($orcSubtitComp)) {
    echo "CADASTRADO";
} else if ($orcSubtitCompDAO->adicionar($orcSubtitComp)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>