<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/dao/OrcComposicaoInsumoDAO.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/util/NumeroUtil.php";

class OrcComposicaoSubcomposicaoDAO {

    private $conexao;
    private $funcionario;

    /**
     * Metodo construtor.
     */
    public function OrcComposicaoSubcomposicaoDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();

        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    /**
     * Retorna as composicoes de um orcamento num 'select' <br /> para serem adiconadas como subcomposicoes.
     * @param inteiro $id_orc - Id de um orcamento.
     * @param inteiro $id_orc_comp - Id de uma composicao de um orcamento.
     * @return array
     */
    public function getSubcomposicoesAdd($id_orc, $id_orc_comp) {
        $subcomposicoes = "";

        $query = "SELECT a.id, b.descricao FROM orc_composicao AS a, composicao AS b WHERE a.id_comp = b.codigo AND a.id_orc = $id_orc "
                . "AND a.id != $id_orc_comp AND a.id AND a.id NOT IN (SELECT id_orc_subcomp FROM orc_comp_subcomp) ORDER BY b.descricao ASC";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);

        $x = 0;
        while ($subcomposicao = $this->conexao->getRegistros($resultado)) {
            $x = $x + 1;
            if ($x == 1) {
                $subcomposicoes = array(array($subcomposicao["id"] => $subcomposicao["descricao"]));
            } else {
                $subcomposicoes[] = array($subcomposicao["id"] => $subcomposicao["descricao"]);
            }
        }

        if ($qtdRegistros > 0) {
            return $subcomposicoes;
        } else {
            return "";
        }
    }

    /**
     * Adiciona uma subcomposicao a uma composicao.
     * @param OrcCompSubcomp $orc_comp_subcomp
     * @return type boolean
     */
    public function adicionar($orc_comp_subcomp) {
        $query = "INSERT INTO orc_comp_subcomp (id_orc_comp, id_orc_subcomp) "
                . "VALUES (" . $orc_comp_subcomp->getOrcComposicao()->getId() . ", " . $orc_comp_subcomp->getOrcSubcomp()->getId() . ")";
        if ($this->conexao->executaQuery($query)) {
            if (!empty($orc_comp_subcomp->getOrcSubcomp()->getQuantidade())) {
                $queryUpdate = "UPDATE orc_composicao SET quantidade = " . NumeroUtil::formatar($orc_comp_subcomp->getOrcSubcomp()->getQuantidade(), NumeroUtil::NUMERO_USA)
                        . " WHERE id = " . $orc_comp_subcomp->getOrcSubcomp()->getId();
                return $this->conexao->executaQuery($queryUpdate);
            }
            return true;
        }
        return false;
    }

    /**
     * Exclui subcomposicao de uma composicao.
     * @param OrcCompSubcomp $subcomposicao
     * @return boolean
     */
    public function excluir($subcomposicao) {
        $query = "DELETE FROM orc_comp_subcomp WHERE id = " . $subcomposicao->getId();
        return $this->conexao->executaQuery($query);
    }

    /**
     * 
     * @param type $id_orc_comp
     * @param type $encargo_social
     * @param type $bdi
     * @param type $is_servico
     * @return type
     */
    public function getTotalSubcomposicoes($id_orc_comp, $encargo_social, $bdi, $is_servico) {
        $orcCompInsumoDAO = new OrcComposicaoInsumoDAO();
        $totalSubcomposicoes = 0;
        $totalSubComposicao = 0;

        $query = "SELECT a.id_orc_subcomp, b.quantidade "
                . "FROM orc_comp_subcomp AS a, orc_composicao AS b "
                . "WHERE a.id_orc_subcomp = b.id AND a.id_orc_comp = $id_orc_comp";
        $resultado = $this->conexao->executaQuery($query);
        $qtd = $this->conexao->getQtdRegistros($resultado);
        if ($qtd > 0) {
            while ($subcomposicao = $this->conexao->getRegistros($resultado)) {
                $totalSubComposicao = ($orcCompInsumoDAO->getTotalInsumos($subcomposicao["id_orc_subcomp"], $encargo_social, $bdi, $is_servico) * $subcomposicao["quantidade"]);
                $totalSubcomposicoes += $totalSubComposicao;
            }

            /*if ($bdi != 0) {
                return $totalSubcomposicoes * (1 + ($bdi / 100));
            }*/

            return $totalSubcomposicoes;
        }
    }

}

?>