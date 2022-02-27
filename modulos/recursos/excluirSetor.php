<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/SetorDAO.php";

$setorDAO = new SetorDAO();

$id = filter_input(INPUT_POST, "idSetor");
$setor = filter_input(INPUT_POST, "nomeSetor");

if ($setorDAO->estaModulo($id)) {
    echo "CADASTRADO";
} else if ($setorDAO->excluirSetor($setor)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  