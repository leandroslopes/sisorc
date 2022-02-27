<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/util/NumeroUtil.php";

class OrcComposicaoInsumoDAO {

    private $conexao;
    private $funcionario;

    /**
     * Metodo construtor.
     */
    public function OrcComposicaoInsumoDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();

        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    /**
     * Adiciona um insumo a uma composicao de um orcamento.
     * @param OrcCompInsumo $orc_comp_ins
     * @return boolean
     */
    public function adicionar($orc_comp_ins) {
        $queryCompAdd = "INSERT INTO orc_comp_insumo (id_orc_comp, id_ins, data_cadastro) "
                . "VALUES (" . $orc_comp_ins->getOrcComposicao()->getId() . ", " . $orc_comp_ins->getInsumo()->getCodigo() . ", NOW())";
        return $this->conexao->executaQuery($queryCompAdd);
    }

    /**
     * Altera a quantidade e o preco do insumo.
     * @param OrcCompInsumo $orcCompIns
     * @return boolean
     */
    public function editarQtdEPreco($orcCompIns) {

        if (!empty($orcCompIns->getQuantidade()) && !empty($orcCompIns->getPreco())) {
            $set = "quantidade = " . NumeroUtil::formatar($orcCompIns->getQuantidade(), NumeroUtil::NUMERO_USA)
                    . ", preco_unitario = " . NumeroUtil::formatar($orcCompIns->getPreco(), NumeroUtil::NUMERO_USA);
        } else if (!empty($orcCompIns->getQuantidade())) {
            $set = "quantidade = " . NumeroUtil::formatar($orcCompIns->getQuantidade(), NumeroUtil::NUMERO_USA);
        } else if (!empty($orcCompIns->getPreco())) {
            $set = "preco_unitario = " . NumeroUtil::formatar($orcCompIns->getPreco(), NumeroUtil::NUMERO_USA);
        }

        $query = "UPDATE orc_comp_insumo SET $set, data_cadastro = NOW() WHERE id = " . $orcCompIns->getId();
        return $this->conexao->executaQuery($query);
    }

    /**
     * Retorna os insumos de uma composicao para um 'select'.
     * @param inteiro $codigo - Codigo da composicao.
     * @return array
     */
    public function getCompInsumos($codigo) {
        $insumos = "";

        $query = "SELECT b.codigo, b.descricao FROM comp_insumo AS a, insumo AS b WHERE a.id_ins = b.codigo AND a.id_comp = $codigo ORDER BY b.descricao ASC";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);

        $x = 0;
        while ($insumo = $this->conexao->getRegistros($resultado)) {
            $x = $x + 1;
            if ($x == 1) {
                $insumos = array(array($insumo["codigo"] => $insumo["descricao"]));
            } else {
                $insumos[] = array($insumo["codigo"] => $insumo["descricao"]);
            }
        }

        if ($qtdRegistros > 0) {
            return $insumos;
        } else {
            return "";
        }
    }

    /**
     * Calcula e retorna o valor total dos insumos de uma composicao.
     * @param inteiro $id_orc_comp - ID de uma composicao e/ou subcomposicao.
     * @param decimal $encargo_social
     * @param decimal $bdi
     * @param type $is_servico
     * @return decimal
     */
    public function getTotalInsumos($id_orc_comp, $encargo_social, $bdi = 0, $is_servico) {
        $total = 0;
        $condicaoServico = "";

        if ($is_servico) {
            $condicaoServico = "AND c.nome IN ('MO', 'VB', 'SB', 'MAQ')";
        }

        $query = "SELECT a.quantidade, a.preco_unitario, c.nome AS servico "
                . "FROM orc_comp_insumo AS a, insumo AS b, servico AS c "
                . "WHERE a.id_ins = b.codigo AND b.id_serv = c.id AND a.id_orc_comp = $id_orc_comp $condicaoServico"
                . "ORDER BY c.nome ASC";
        $resultado = $this->conexao->executaQuery($query);
        $qtd = $this->conexao->getQtdRegistros($resultado);
        if ($qtd > 0) {
            while ($insumo = $this->conexao->getRegistros($resultado)) {
                $valorInsumo = NumeroUtil::multiplicar($insumo["quantidade"], $insumo["preco_unitario"]);
                $total += $valorInsumo;
                if ($insumo["servico"] == "MO") {
                    if ($valorInsumo != 0) {
                        $valorLeisSocias = (($encargo_social / $valorInsumo) * 100);
                    } else {
                        $valorLeisSocias = 0;
                    }
                    $total += $valorLeisSocias;
                }
            }

            if ($bdi != 0) {
                $total = $total * (1 + ($bdi / 100));
            }

            return $total;
        }
    }

    /**
     * Calcula e retorna o valor total dos insumos e subcomposicoes de uma composicao.
     * @param inteiro $id_orc_comp - ID de uma composicao e/ou subcomposicao.
     * @param decimal $encargo_social
     * @param decimal $bdi
     * @return decimal
     */
    public function getTotalInsumosESubcomposicoes($id_orc_comp, $encargo_social, $bdi = 0, $is_servico) {
        $total = 0;
        $condicaoServico = "";

        if ($is_servico) {
            $condicaoServico = "AND c.nome IN ('MO', 'VB', 'SB', 'MAQ')";
        }

        $queryInsumos = "SELECT a.quantidade, a.preco_unitario, c.nome AS servico "
                . "FROM orc_comp_insumo AS a, insumo AS b, servico AS c "
                . "WHERE a.id_ins = b.codigo AND b.id_serv = c.id AND a.id_orc_comp = $id_orc_comp "
                . "$condicaoServico "
                . "ORDER BY c.nome ASC";
        $resultadoInsumos = $this->conexao->executaQuery($queryInsumos);
        $qtdInsumos = $this->conexao->getQtdRegistros($resultadoInsumos);
        if ($qtdInsumos > 0) {
            while ($insumo = $this->conexao->getRegistros($resultadoInsumos)) {
                $valorInsumo = NumeroUtil::multiplicar($insumo["quantidade"], $insumo["preco_unitario"]);
                $total += $valorInsumo;
                if ($insumo["servico"] == "MO") {
                    if ($valorInsumo != 0) {
                        $valorLeisSocias = (($encargo_social / $valorInsumo) * 100);
                    } else {
                        $valorLeisSocias = 0;
                    }
                    $total += $valorLeisSocias;
                }
            }
        }

        $querySubcomposicoes = "SELECT * FROM orc_comp_subcomp AS a WHERE a.id_orc_comp = $id_orc_comp";
        $resultadoSubcomposicoes = $this->conexao->executaQuery($querySubcomposicoes);
        $qtdSubcomposicoes = $this->conexao->getQtdRegistros($resultadoSubcomposicoes);
        if ($qtdSubcomposicoes > 0) {
            while ($subcomposicao = $this->conexao->getRegistros($resultadoSubcomposicoes)) {
                $querySubcomposicao = "SELECT a.quantidade FROM orc_composicao AS a WHERE a.id = " . $subcomposicao["id_orc_subcomp"];
                $resultadoSubcomposicao = $this->conexao->executaQuery($querySubcomposicao);
                $arraySubComp = $this->conexao->getRegistros($resultadoSubcomposicao);

                $totalSubcomposicao = $this->getTotalInsumos($subcomposicao["id_orc_subcomp"], $encargo_social, $bdi, $is_servico);
                $valorSubcomposicao = NumeroUtil::multiplicar($arraySubComp["quantidade"], $totalSubcomposicao);
                $total += $valorSubcomposicao;
            }
        }

        if ($bdi != 0) {
            return $total * (1 + ($bdi / 100));
        }

        return $total;
    }

}

?>