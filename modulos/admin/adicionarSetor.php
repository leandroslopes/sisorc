<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/ModuloDAO.php";
include_once "../../classes/util/StringUtil.php";

$moduloDAO = new ModuloDAO();

$setor = filter_input(INPUT_POST, "setor");

$arrayString = StringUtil::getArrayStrings('_', $setor);
$id_modulo = $arrayString[0];
$id_setor = $arrayString[1];

if ($moduloDAO->adicionarAcessoSetor($id_modulo, $id_setor))
    echo "TRUE";
else
    echo "FALSE";
?>  