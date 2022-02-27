<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/UnidadeDAO.php";

$unidadeDAO = new UnidadeDAO();

$unidade = filter_input(INPUT_POST, "unidade");
$id = filter_input(INPUT_POST, "id");

if ($unidadeDAO->isCadastrado($unidade)) {
    echo "CADASTRADO";
} else {
    if (isset($id)) {
        if ($unidadeDAO->editar($id, $unidade)) {
            echo "TRUE";
        } else {
            echo "FALSE";
        }
    } else {
        if ($unidadeDAO->cadastrar($unidade)) {
            echo "TRUE";
        } else {
            echo "FALSE";
        }
    }
}
?>  