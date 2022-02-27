<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/SetorDAO.php";

$setorDAO = new SetorDAO();

$id = filter_input(INPUT_POST, "id");
$setor = filter_input(INPUT_POST, "setor");

if ($setorDAO->isCadastrado($setor)) {
    echo "CADASTRADO";
} else {
    if (isset($id)) {
        $id = filter_input(INPUT_POST, "id");
        if ($setorDAO->editar($id, $setor)) {
            echo "TRUE";
        } else {
            echo "FALSE";
        }
    } else {
        if ($setorDAO->cadastrar($setor)) {
            echo "TRUE";
        } else {
            echo "FALSE";
        }
    }
}
?>  