<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/ServicoDAO.php";

$servicoDAO = new ServicoDAO();

$id = filter_input(INPUT_POST, "idServ");
$nome = filter_input(INPUT_POST, "nomeServ");

if ($servicoDAO->estaRelacionado($id)) {
    echo "CADASTRADO";
} else if ($servicoDAO->excluir($nome)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  