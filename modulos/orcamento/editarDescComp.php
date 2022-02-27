<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/OrcComposicaoDAO.php";

$orcComposicaoDAO = new OrcComposicaoDAO();

$id_orc = filter_input(INPUT_POST, "id_orc");
$id_orc_comp = filter_input(INPUT_POST, "id_orc_comp");
$cod_comp_antigo = filter_input(INPUT_POST, "cod_comp_antigo");
$descricao = filter_input(INPUT_POST, "descricao");

if ($orcComposicaoDAO->jaCadastrado($id_orc, $descricao)) {
    echo "CADASTRADO";
} else if ($orcComposicaoDAO->editarDescricao($id_orc_comp, $cod_comp_antigo, $descricao)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>