<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/TipoDAO.php";

$tipoDAO = new TipoDAO();

$id = filter_input(INPUT_POST, "idTipo");
$nome = filter_input(INPUT_POST, "nomeTipo");

if ($tipoDAO->estaRelacionado($id)) {
    echo "CADASTRADO";
} else {
    if ($tipoDAO->excluir($nome)) {
        echo "TRUE";
    } else {
        echo "FALSE";
    }
}
?>  