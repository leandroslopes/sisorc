<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/dao/OrcComposicaoDAO.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/util/NumeroUtil.php";

class TituloComposicaoDAO {

    private $conexao;
    private $funcionario;

    /**
     * Metodo construtor.
     */
    public function TituloComposicaoDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();

        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    /**
     * Adiciona uma composicao a um titulo do orcamento.
     * @param OrcTitComposicao $tit_comp
     * @return boolean
     */
    public function adicionar($tit_comp) {
        $orcCompDAO = new OrcComposicaoDAO();
        $query = "INSERT INTO titulo_composicao (id_orc_tit, id_orc_comp, num_item) "
                . "VALUES (" . $tit_comp->getTitulo()->getId() . ", " . $tit_comp->getComposicao()->getId() . ", "
                . "'" . $tit_comp->getNumeroItem() . "')";
        if ($this->conexao->executaQuery($query)) {
            if ($orcCompDAO->alterarQuantidade($tit_comp->getComposicao())) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * Edita uma composicao de um titulo.
     * @param OrcTitComposicao $tit_comp
     * @return boolean
     */
    public function editar($tit_comp) {
        $orcCompDAO = new OrcComposicaoDAO();
        $query = "UPDATE titulo_composicao SET num_item = '" . $tit_comp->getNumeroItem() . "' WHERE id = " . $tit_comp->getId();
        if ($this->conexao->executaQuery($query)) {
            if ($orcCompDAO->alterarQuantidade($tit_comp->getComposicao())) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
    
    /**
     * Exclui uma composicao de um titulo.
     * @param inteiro $id - ID da composicao do titulo.
     * @return boolean
     */
    public function excluir($id) {
        $query = "DELETE FROM titulo_composicao WHERE id = $id";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Verifica se determinada composicao de um titulo estah cadastrado no orcamento.
     * @param OrcTitComposicao $tit_comp - Um objeto do tipo OrcTitComposicao.
     * @return boolean
     */
    public function tituloCompJaCadastrado($tit_comp) {
        $query = "SELECT * FROM titulo_composicao AS a, orc_titulo AS b, orcamento AS c "
                . "WHERE a.id_orc_tit = b.id AND b.id_orc = c.id AND a.id_orc_comp = " . $tit_comp->getComposicao()->getId() . " "
                . "AND c.id = " . $tit_comp->getTitulo()->getOrcamento()->getId();
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        if ($qtdRegistros > 0) {
            return true;
        }
        return false;
    }

}

?>
