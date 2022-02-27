<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/CargoDAO.php";
include_once "../../classes/util/StringUtil.php";

$cargoDAO = new CargoDAO();

$id = filter_input(INPUT_POST, "idCargo");
$cargo = filter_input(INPUT_POST, "nomeCargo");

if ($cargoDAO->estaSubmodulo($id)) {
    echo "CADASTRADO";
} else if ($cargoDAO->excluirCargo($cargo)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  