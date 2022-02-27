<?

session_start();

include_once "classes/dao/FuncionarioDAO.php";
include_once "classes/modelo/Funcionario.php";

$funcionarioDAO = new FuncionarioDAO();
$funcionario = new Funcionario();

$codigo = filter_input(INPUT_POST, "codigo");
$senha = filter_input(INPUT_POST, "senha");

$funcionario->setCodigo($codigo);
$funcionario->setSenha($senha);

if ($funcionarioDAO->login($funcionario)) {
    $_SESSION["funcionario"] = $funcionarioDAO->getFuncionario($codigo);
    header("Location: inicio.php");
} else {
    header("Location: index.php?error=1");
}
?>
