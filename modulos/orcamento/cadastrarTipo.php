<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/TipoDAO.php";

$tipoDAO = new TipoDAO();

$nome = filter_input(INPUT_POST, "tipo");
$id = filter_input(INPUT_POST, "id");

if ($tipoDAO->isCadastrado($nome)) {
    echo "CADASTRADO";
} else {
    if (isset($id)) {
        $id = filter_input(INPUT_POST, "id");
        if ($tipoDAO->editar($id, $nome)) {
            echo "TRUE";
        } else {
            echo "FALSE";
        }
    } else {
        if ($tipoDAO->cadastrar($nome)) {
            echo "TRUE";
        } else {
            echo "FALSE";
        }
    }
}
?>  