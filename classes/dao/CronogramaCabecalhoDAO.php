<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";

class CronogramaCabecalhoDAO {

    private $conexao;
    private $funcionario;

    /**
     * Metodo construtor.
     */
    public function CronogramaCabecalhoDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();

        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    /**
     * Retorna um 'select' com os cabecalhos de um cronograma.
     * @return string
     */
    public function getSelect() {
        $conteudo_html = "";

        $query = "SELECT * FROM cronograma_cabecalho AS a ORDER BY a.id ASC";
        $resultado = $this->conexao->executaQuery($query);

        $conteudo_html .= "<select name='cabecalho' id='cabecalho'>";
        while ($cabecalho = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<option value='" . $cabecalho["id"] . "'>" . $cabecalho["descricao"] . "</option>";
        }
        $conteudo_html .= "</select>";

        return $conteudo_html;
    }

}

?>
