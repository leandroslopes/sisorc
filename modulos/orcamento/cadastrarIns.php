<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/InsumoDAO.php";

$insumoDAO = new InsumoDAO();

$codigo = filter_input(INPUT_POST, "codigo");

if ($insumoDAO->isCadastrado($_REQUEST)) {
    echo "CADASTRADO";
} else {
    if (isset($codigo)) {
        if ($insumoDAO->editar($_REQUEST)) {
            echo "TRUE";
        } else {
            echo "FALSE";
        }
    } else {
        if ($insumoDAO->cadastrar($_REQUEST)) {
            echo "TRUE";
        } else {
            echo "FALSE";
        }
    }
}
?>  