<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/SubmoduloDAO.php";
include_once "../../classes/util/StringUtil.php";

$submoduloDAO = new SubmoduloDAO();

$cargo = filter_input(INPUT_POST, "cargo");

$arrayString = StringUtil::getArrayStrings('_', $cargo);
$id_submodulo = $arrayString[0];
$id_cargo = $arrayString[1];

if ($submoduloDAO->adicionarAcessoCargo($id_submodulo, $id_cargo)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  