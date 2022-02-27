<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/dao/OrcComposicaoDAO.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";

class SubtituloComposicaoDAO {

    private $conexao;
    private $funcionario;

    /**
     * Metodo construtor.
     */
    public function SubtituloComposicaoDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();

        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    /**
     * Adiciona uma composicao a um subtitulo.
     * @param OrcSubtitComposicao $subtit_comp
     * @return boolean
     */
    public function adicionar($subtit_comp) {
        $orcCompDAO = new OrcComposicaoDAO();
        $query = "INSERT INTO subtitulo_composicao (id_tit_subtit, id_orc_comp, num_item) "
                . "VALUES (" . $subtit_comp->getSubtitulo()->getId() . ", " . $subtit_comp->getComposicao()->getId() . ", "
                . "'" . $subtit_comp->getNumeroItem() . "')";
        if ($this->conexao->executaQuery($query)) {
            if ($orcCompDAO->alterarQuantidade($subtit_comp->getComposicao())) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * Edita uma composicao de um subtitulo.
     * @param OrcSubtitComposicao $subtit_comp
     * @return boolean
     */
    public function editar($subtit_comp) {
        $orcCompDAO = new OrcComposicaoDAO();
        $query = "UPDATE subtitulo_composicao SET num_item = '" . $subtit_comp->getNumeroItem() . "' WHERE id = " . $subtit_comp->getId();
        if ($this->conexao->executaQuery($query)) {
            if ($orcCompDAO->alterarQuantidade($subtit_comp->getComposicao())) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
    
    /**
     * Exclui uma composicao de um subtitulo.
     * @param inteiro $id - ID da composicao do subtitulo.
     * @return boolean
     */
    public function excluir($id) {
        $query = "DELETE FROM subtitulo_composicao WHERE id = $id";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Verifica se determinada composicao estah cadastrado em algum subtitulo do orcamento.
     * @param OrcSubtitComposicao $subtit_comp - Um objeto do tipo OrcSubtitComposicao.
     * @return boolean
     */
    public function estaCadastrado($subtit_comp) {
        $query = "SELECT * FROM subtitulo_composicao AS a, titulo_subtitulo AS b, orc_titulo AS c, orcamento AS d "
                . "WHERE a.id_tit_subtit = b.id AND b.id_orc_tit = c.id AND c.id_orc = d.id AND a.id_orc_comp = " . $subtit_comp->getComposicao()->getId()
                . " AND d.id = " . $subtit_comp->getSubtitulo()->getTitulo()->getOrcamento()->getId();
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        if ($qtdRegistros > 0) {
            return true;
        }
        return false;
    }

}

?>
