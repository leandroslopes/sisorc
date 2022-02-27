<?

session_cache_limiter("nocache");
session_cache_expire(999);
session_start();

include_once "../../classes/dao/FuncionarioDAO.php";
include_once "../../classes/modelo/Funcionario.php";
include_once "../../classes/modelo/Cargo.php";
include_once "../../classes/modelo/Setor.php";

$funcionarioDAO = new FuncionarioDAO();
$funcionario = new Funcionario();
$cargo = new Cargo();
$setor = new Setor();

$id_cargo = filter_input(INPUT_POST, "cargo");
$id_setor = filter_input(INPUT_POST, "setor");
$codigo = filter_input(INPUT_POST, "codigo_edd");
$situacao = filter_input(INPUT_POST, "situacao");

$cargo->setId($id_cargo);
$setor->setId($id_setor);

$funcionario->setCodigo($codigo);
$funcionario->setCargo($cargo);
$funcionario->setSetor($setor);
$funcionario->setSituacao($situacao);

if ($funcionarioDAO->editar($funcionario)) {
    echo "TRUE";
} else {
    echo "FALSE";
}
?>  