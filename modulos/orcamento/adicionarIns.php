<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/ComposicaoDAO.php";

$composicaoDAO = new ComposicaoDAO();

$id_comp = filter_input(INPUT_POST, "idComp");
$codigo = filter_input(INPUT_POST, "insPesquisa");

if (!empty($codigo)) {
    if ($composicaoDAO->adicionarInsumo($id_comp, $codigo)) {
        echo "TRUE";
    } else {
        echo "FALSE";
    }
} else {
    echo "FALSE";
}
?>  