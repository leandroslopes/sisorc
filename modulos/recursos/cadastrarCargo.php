<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/CargoDAO.php";

$cargoDAO = new CargoDAO();

$id = filter_input(INPUT_POST, "id");
$cargo = filter_input(INPUT_POST, "cargo");

if ($cargoDAO->isCadastrado($cargo)) {
    echo "CADASTRADO";
} else {
    if (isset($id)) {
        $id = filter_input(INPUT_POST, "id");
        if ($cargoDAO->editarCargo($id, $cargo)) {
            echo "TRUE";
        } else {
            echo "FALSE";
        }
    } else {
        if ($cargoDAO->cadastrarCargo($cargo)) {
            echo "TRUE";
        } else {
            echo "FALSE";
        }
    }
}
?>  