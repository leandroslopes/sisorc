<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/ComposicaoDAO.php";

$composicaoDAO = new ComposicaoDAO();

$codigo = filter_input(INPUT_POST, "codigo");

if ($composicaoDAO->isCadastrado($_REQUEST)) {
    echo "CADASTRADO";
} else {
    if (isset($codigo)) {
        if ($composicaoDAO->editar($_REQUEST)) {
            echo "TRUE";
        } else {
            echo "FALSE";
        }
    } else {
        if ($composicaoDAO->cadastrar($_REQUEST)) {
            echo "TRUE";
        } else {
            echo "FALSE";
        }
    }
}
?>  