<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/ServicoDAO.php";

$servicoDAO = new ServicoDAO();

$nome = filter_input(INPUT_POST, "servico");

if ($servicoDAO->isCadastrado($nome)) {
    echo "CADASTRADO";
} else {
    if (isset($_REQUEST["id"])) {
        $id = filter_input(INPUT_POST, "id");
        if ($servicoDAO->editar($id, $nome)) {
            echo "TRUE";
        } else {
            echo "FALSE";
        }
    } else {
        if ($servicoDAO->cadastrar($nome)) {
            echo "TRUE";
        } else {
            echo "FALSE";
        }
    }
}
?>  