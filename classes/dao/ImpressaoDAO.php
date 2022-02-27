<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/dao/OrcComposicaoInsumoDAO.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/dao/OrcComposicaoSubcomposicaoDAO.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/dao/TituloDAO.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/dao/CronogramaDAO.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/dao/SubtituloDAO.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Orcamento.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/CronogramaTitulo.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/util/NumeroUtil.php";

class ImpressaoDAO {

    const PLANILHA_ORCAMENTARIA = 1;
    const COMPOSICAO_CUSTO = 2;
    const CURVA_ABC = 3;
    const CRONOGRAMA = 4;

    private $conexao;
    private $funcionario;

    /**
     * Metodo construtor.
     */
    public function ImpressaoDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();

        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    /**
     * Imprimi o relatorio escolhido.
     * @param array $array_request
     * @return string
     */
    public function imprimir($array_request) {
        $relatorio = explode('_', $array_request["relatorio"]);
        switch ($relatorio[0]) {
            case self::PLANILHA_ORCAMENTARIA:
                return $this->impPlanilhaOrcamentaria($array_request);
            case self::COMPOSICAO_CUSTO:
                return $this->impComposicaoCusto($array_request);
            case self::CURVA_ABC:
                return $this->impCurvaABC($array_request);
            case self::CRONOGRAMA:
                return $this->impCronograma($array_request);
            default:
                break;
        }
    }

    /**
     * Imprimi a planilha orcamentaria.
     * @param array $array_request
     * @return string
     */
    private function impPlanilhaOrcamentaria($array_request) {
        $orcCompInsumoDAO = new OrcComposicaoInsumoDAO();
        $orcTituloDAO = new TituloDAO();
        $orcSubtituloDAO = new SubtituloDAO();
        $conteudo_html = "";
        $is_servico = "";

        $conteudo_html .= "<table id='tblImpConteudo'>";
        $conteudo_html .= "<thead>";
        $conteudo_html .= "<tr>";
        $conteudo_html .= "<th>ITEM</th>";
        $conteudo_html .= "<th>DESCRI&Ccedil;&Atilde;O</th>";
        $conteudo_html .= "<th>UNIDADE</th>";
        $conteudo_html .= "<th>QUANTIDADE</th>";
        $conteudo_html .= "<th>PRE&Ccedil;O UNIT&Aacute;RIO (R$)</th>";
        $conteudo_html .= "<th>TOTAL (R$)</th>";
        $conteudo_html .= "</tr>";
        $conteudo_html .= "</thead>";
        $conteudo_html .= "<tbody>";
        $conteudo_html .= "<tr>";
        $conteudo_html .= "<td>**01**</td>";
        $conteudo_html .= "<td colspan='4'>" . $array_request["nome_obra"] . "</td>";

        /* FILTRO */
        $selectRel1 = filter_input(INPUT_POST, "selectRel1");

        if ($selectRel1 == 2) { //SEM BDI
            $array_request["bdi"] = 0;
        } else if ($selectRel1 == 3) {
            $is_servico = true;
        }

        $total = $orcTituloDAO->getTotalOrcamento($array_request["id_orc"], $array_request["encargo_social"], $array_request["bdi"], $is_servico);
        $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($total, NumeroUtil::NUMERO_BRA) . "</td>";
        $conteudo_html .= "</tr>";

        $queryTitulos = "SELECT a.id, a.num_item, a.titulo FROM orc_titulo AS a WHERE a.id_orc = " . $array_request["id_orc"] . " ORDER BY a.num_item ASC";
        $resultadoTitulos = $this->conexao->executaQuery($queryTitulos);
        $qtdTitulos = $this->conexao->getQtdRegistros($resultadoTitulos);
        if ($qtdTitulos > 0) {
            while ($titulo = $this->conexao->getRegistros($resultadoTitulos)) {
                $conteudo_html .= "<tr>";
                $conteudo_html .= "<td>" . $titulo["num_item"] . "</td>";
                $conteudo_html .= "<td>" . $titulo["titulo"] . "</td>";
                $conteudo_html .= "<td>&nbsp;</td>";
                $conteudo_html .= "<td>&nbsp;</td>";
                $conteudo_html .= "<td>&nbsp;</td>";

                $total = $orcTituloDAO->getTotal($titulo["id"], $array_request["encargo_social"], $array_request["bdi"], $is_servico);
                $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($total, NumeroUtil::NUMERO_BRA) . "</td>";

                $conteudo_html .= "</tr>";

                $query = "(SELECT a.id, a.id_orc_comp, a.num_item, c.descricao, d.nome AS unidade, b.quantidade "
                        . "FROM titulo_composicao AS a, orc_composicao AS b, composicao AS c, unidade AS d "
                        . "WHERE a.id_orc_comp = b.id AND b.id_comp = c.codigo AND c.id_uni = d.id AND a.id_orc_tit = " . $titulo["id"] . ") "
                        . "UNION (SELECT a.id, @id_orc_comp := 0, a.num_item, a.subtitulo AS descricao, @unidade := 0, @quantidade := 0 "
                        . "FROM titulo_subtitulo AS a WHERE a.id_orc_tit = " . $titulo["id"] . ") ORDER BY num_item ASC ";
                $resultado = $this->conexao->executaQuery($query);
                $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
                if ($qtdRegistros > 0) {
                    $estilo_linha = "low";
                    while ($array = $this->conexao->getRegistros($resultado)) {
                        if ($array["id_orc_comp"] != 0) {
                            $conteudo_html .= "<tr>";
                            $conteudo_html .= "<td>" . $array["num_item"] . "</td>";
                            $conteudo_html .= "<td>" . $array["descricao"] . "</td>";
                            $conteudo_html .= "<td class='textoCentro'>" . $array["unidade"] . "</td>";
                            $conteudo_html .= "<td class='textoCentro'>" . NumeroUtil::formatar($array["quantidade"], NumeroUtil::NUMERO_BRA) . "</td>";

                            $preco = $orcCompInsumoDAO->getTotalInsumosESubcomposicoes($array["id_orc_comp"], $array_request["encargo_social"], $array_request["bdi"], $is_servico);
                            $conteudo_html .= "<td class='textoCentro'>" . NumeroUtil::formatar($preco, NumeroUtil::NUMERO_BRA) . "</td>";

                            $total = NumeroUtil::multiplicar($array["quantidade"], $preco);
                            $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($total, NumeroUtil::NUMERO_BRA) . "</td>";

                            $conteudo_html .= "</tr>";
                        } else {
                            $conteudo_html .= "<tr>";
                            $conteudo_html .= "<td>" . $array["num_item"] . "</td>";
                            $conteudo_html .= "<td>" . $array["descricao"] . "</td>";
                            $conteudo_html .= "<td>&nbsp;</td>";
                            $conteudo_html .= "<td>&nbsp;</td>";
                            $conteudo_html .= "<td>&nbsp;</td>";

                            $total_subtitulo = $orcSubtituloDAO->getTotal($array["id"], $array_request["encargo_social"], $array_request["bdi"], $is_servico);
                            $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($total_subtitulo, NumeroUtil::NUMERO_BRA) . "</td>";

                            $conteudo_html .= "</tr>";

                            $querySubtitComp = "SELECT a.id, a.id_orc_comp, a.num_item, c.descricao, d.nome AS unidade, b.quantidade "
                                    . "FROM subtitulo_composicao AS a, orc_composicao AS b, composicao AS c, unidade AS d "
                                    . "WHERE a.id_orc_comp = b.id AND b.id_comp = c.codigo AND c.id_uni = d.id AND a.id_tit_subtit = " . $array["id"]
                                    . " ORDER BY a.num_item";
                            $resultSubtitComp = $this->conexao->executaQuery($querySubtitComp);
                            $qtdSubtitComp = $this->conexao->getQtdRegistros($resultSubtitComp);
                            if ($qtdSubtitComp > 0) {
                                while ($subtit_comp = $this->conexao->getRegistros($resultSubtitComp)) {
                                    $conteudo_html .= "<tr>";
                                    $conteudo_html .= "<td>" . $subtit_comp["num_item"] . "</td>";
                                    $conteudo_html .= "<td>" . $subtit_comp["descricao"] . "</td>";
                                    $conteudo_html .= "<td class='textoCentro'>" . $subtit_comp["unidade"] . "</td>";
                                    $conteudo_html .= "<td class='textoCentro'>" . NumeroUtil::formatar($subtit_comp["quantidade"], NumeroUtil::NUMERO_BRA) . "</td>";

                                    $preco = $orcCompInsumoDAO->getTotalInsumos($subtit_comp["id_orc_comp"], $array_request["encargo_social"], $array_request["bdi"], $is_servico);
                                    $conteudo_html .= "<td class='textoCentro'>" . NumeroUtil::formatar($preco, NumeroUtil::NUMERO_BRA) . "</td>";

                                    $total = NumeroUtil::multiplicar($subtit_comp["quantidade"], $preco);
                                    $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($total, NumeroUtil::NUMERO_BRA) . "</td>";

                                    $conteudo_html .= "</tr>";
                                }
                            } else {
                                $conteudo_html .= "<tr><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>";
                            }
                        }
                    }
                } else {
                    $conteudo_html .= "<tr><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>";
                }
            }
        } else {
            $conteudo_html = "<tr><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>";
        }

        $conteudo_html .= "</tbody>";
        $conteudo_html .= "</table>";

        return $conteudo_html;
    }

    private function impComposicaoCusto($array_request) {
        $orcCompSubcompDAO = new OrcComposicaoSubcomposicaoDAO();
        $orcCompInsumoDAO = new OrcComposicaoInsumoDAO();

        $conteudo_html = "";
        $totalComp = 0;
        $subtotalSubComp = 0;
        $valorSubComp = 0;
        $totalCompPai = 0;
        $precoSubcompIns = 0;
        $totalPercentual = 0;
        $percentualLeisSociais = 0;
        $valorLeisSocias = 0;

        $conteudo_html .= "<table id='tblImpConteudo2'>";
        $conteudo_html .= "<thead class='negrito'>";
        $conteudo_html .= "<tr>";
        $conteudo_html .= "<th>C&Oacute;DIGO</th>";
        $conteudo_html .= "<th>DESCRI&Ccedil;&Atilde;O</th>";
        $conteudo_html .= "<th>UNID.</th>";
        $conteudo_html .= "<th>QUANT.</th>";
        $conteudo_html .= "<th>PRE&Ccedil;O (R$)</th>";
        $conteudo_html .= "<th>VALOR(R$)</th>";
        $conteudo_html .= "<th>PERC.(%)</th>";
        $conteudo_html .= "<th>DATA</th>";
        $conteudo_html .= "<th>&nbsp;</th>";
        $conteudo_html .= "</tr>";
        $conteudo_html .= "</thead>";
        $conteudo_html .= "<tbody>";

        /* FILTRO */
        $selectRel2 = filter_input(INPUT_POST, "selectRel2");

        if ($selectRel2 == 2) { //SEM BDI
            $array_request["bdi"] = 0;
        }

        $queryComposicoes = "SELECT a.id AS id_orc_comp, b.codigo, b.descricao, c.nome AS unidade, a.ativa "
                . "FROM orc_composicao AS a, composicao AS b, unidade AS c, orcamento AS d "
                . "WHERE b.id_uni = c.id AND a.id_comp = b.codigo AND a.id_orc = d.id AND a.id_orc = " . $array_request["id_orc"];
        $resultadoComposicoes = $this->conexao->executaQuery($queryComposicoes);
        $qtdComposicoes = $this->conexao->getQtdRegistros($resultadoComposicoes);
        if ($qtdComposicoes > 0) {
            while ($composicao = $this->conexao->getRegistros($resultadoComposicoes)) {
                $conteudo_html .= "<tr class='negrito'>";
                $conteudo_html .= "<td>SERVIÇO =></td>";
                $conteudo_html .= "<td>" . $composicao["codigo"] . " " . $composicao["descricao"] . "</td>";
                $conteudo_html .= "<td class='textoCentro'>" . $composicao["unidade"] . "</td>";
                $conteudo_html .= "<td>&nbsp;</td>";
                $conteudo_html .= "<td>&nbsp;</td>";
                $conteudo_html .= "<td>&nbsp;</td>";
                $conteudo_html .= "</tr>";

                $subtotalSubComp = $orcCompSubcompDAO->getTotalSubcomposicoes($composicao["id_orc_comp"], $array_request["encargo_social"], $array_request["bdi"], FALSE);

                $querySubcomposicoes = "SELECT * FROM orc_comp_subcomp AS a WHERE a.id_orc_comp = " . $composicao["id_orc_comp"];
                $resultadoSubcomposicoes = $this->conexao->executaQuery($querySubcomposicoes);
                $qtdSubcomposicoes = $this->conexao->getQtdRegistros($resultadoSubcomposicoes);
                if ($qtdSubcomposicoes > 0) {
                    while ($subcomposicao = $this->conexao->getRegistros($resultadoSubcomposicoes)) {
                        $querySubcomposicao = "SELECT c.nome AS servico, b.codigo, b.descricao, d.nome AS unidade, a.quantidade "
                                . "FROM orc_composicao AS a, composicao AS b, servico AS c, unidade AS d "
                                . "WHERE a.id_comp = b.codigo AND b.id_serv = c.id AND b.id_uni = d.id AND a.id = " . $subcomposicao["id_orc_subcomp"];
                        $resultadoSubcomposicao = $this->conexao->executaQuery($querySubcomposicao);
                        $arraySubComp = $this->conexao->getRegistros($resultadoSubcomposicao);

                        $conteudo_html .= "<tr>";
                        $conteudo_html .= "<td class='textoCentro'>" . $arraySubComp["servico"] . " " . $arraySubComp["codigo"] . "</td>";
                        $conteudo_html .= "<td class='textoEsq'>" . $arraySubComp["descricao"] . "</td>";
                        $conteudo_html .= "<td class='textoCentro'>" . $arraySubComp["unidade"] . "</td>";
                        $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($arraySubComp["quantidade"], NumeroUtil::NUMERO_BRA) . "</td>";

                        $precoSubcompIns = $orcCompInsumoDAO->getTotalInsumos($subcomposicao["id_orc_subcomp"], $array_request["encargo_social"], $array_request["bdi"], FALSE);
                        $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($precoSubcompIns, NumeroUtil::NUMERO_BRA) . "</td>";

                        $valorSubComp = NumeroUtil::multiplicar($arraySubComp["quantidade"], $precoSubcompIns);
                        $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($valorSubComp, NumeroUtil::NUMERO_BRA) . "</td>";

                        $totalCompPai = $orcCompInsumoDAO->getTotalInsumos($composicao["id_orc_comp"], $array_request["encargo_social"], $array_request["bdi"], FALSE);
                        $percentual = (($valorSubComp / ($subtotalSubComp + $totalCompPai)) * 100);
                        $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($percentual, NumeroUtil::NUMERO_BRA) . "</td>";

                        $conteudo_html .= "</tr>";
                    }

                    $percentual = (($subtotalSubComp / ($subtotalSubComp + $totalCompPai)) * 100);
                    $totalPercentual += $percentual;
                    $conteudo_html .= "<tr>";
                    $conteudo_html .= "<td colspan='5' class='textoCentro'>SUBTOTAL =></td>";
                    $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($subtotalSubComp, NumeroUtil::NUMERO_BRA) . "</td>";
                    $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($percentual, NumeroUtil::NUMERO_BRA) . "</td>";
                    $conteudo_html .= "<td colspan='2'></td>";
                    $conteudo_html .= "</tr>";
                } else {
                    $subtotalSubComp = 0;
                    $precoSubcompIns = 0;
                    $totalComp = 0;
                }

                $valorInsumo = 0;
                $subtotal = 0;
                $nomeServico = "";

                $queryServicos = "SELECT DISTINCT(b.id_serv) FROM orc_comp_insumo AS a, insumo AS b, servico AS c "
                        . "WHERE a.id_ins = b.codigo AND b.id_serv = c.id AND a.id_orc_comp = " . $composicao["id_orc_comp"] . " ORDER BY c.nome ASC ";
                $resultadoServicos = $this->conexao->executaQuery($queryServicos);
                $qtdServicos = $this->conexao->getQtdRegistros($resultadoServicos);
                if ($qtdServicos > 0) {
                    while ($servico = $this->conexao->getRegistros($resultadoServicos)) {
                        $queryInsumos = "SELECT a.id AS idOrcCompIns, a.id_orc_comp, c.nome AS servico, b.codigo, b.descricao, d.nome AS unidade, a.quantidade, "
                                . "a.preco_unitario, DATE_FORMAT(a.data_cadastro, '%d/%m/%Y') AS dtCadastro "
                                . "FROM orc_comp_insumo AS a, insumo AS b, servico AS c, unidade AS d "
                                . "WHERE a.id_ins = b.codigo AND b.id_serv = c.id AND b.id_uni = d.id AND c.id = " . $servico["id_serv"]
                                . " AND a.id_orc_comp = " . $composicao["id_orc_comp"] . " ORDER BY c.nome ASC, b.descricao ASC";
                        $resultadoInsumos = $this->conexao->executaQuery($queryInsumos);
                        $qtdInsumos = $this->conexao->getQtdRegistros($resultadoInsumos);
                        if ($qtdInsumos > 0) {
                            $subtotal = 0;
                            while ($insumo = $this->conexao->getRegistros($resultadoInsumos)) {
                                $conteudo_html .= "<tr>";
                                $conteudo_html .= "<td class='textoCentro'>" . $insumo["servico"] . " " . $insumo["codigo"] . "</td>";
                                $conteudo_html .= "<td class='textoEsq'>" . $insumo["descricao"] . "</td>";
                                $conteudo_html .= "<td class='textoCentro'>" . $insumo["unidade"] . "</td>";
                                $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($insumo["quantidade"], NumeroUtil::NUMERO_BRA) . "</td>";
                                $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($insumo["preco_unitario"], NumeroUtil::NUMERO_BRA) . "</td>";

                                $valorInsumo = NumeroUtil::multiplicar($insumo["quantidade"], $insumo["preco_unitario"]);
                                $subtotal += $valorInsumo;
                                $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($valorInsumo, NumeroUtil::NUMERO_BRA) . "</td>";

                                $totalCompPai = $orcCompInsumoDAO->getTotalInsumos($insumo["id_orc_comp"], $array_request["encargo_social"], $array_request["bdi"], FALSE);
                                $percentual = (($valorInsumo / ($subtotalSubComp + $totalCompPai)) * 100);
                                $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($percentual, NumeroUtil::NUMERO_BRA) . "</td>";

                                $conteudo_html .= "<td class='textoDir'>" . $insumo["dtCadastro"] . "</td>";
                                $conteudo_html .= "</tr>";

                                $nomeServico = $insumo["servico"];

                                $valorLeisSocias += (($array_request["encargo_social"] / $valorInsumo) * 100);
                            }
                            $totalComp += $subtotal;

                            $percentual = (($subtotal / ($subtotalSubComp + $totalCompPai)) * 100);
                            $totalPercentual += $percentual;
                            if ($nomeServico == "MO") {
                                $percentualLeisSociais = (($valorLeisSocias / ($subtotalSubComp + $totalCompPai)) * 100);
                                $totalPercentual += $percentualLeisSociais;
                                $conteudo_html .= "<tr>";
                                $conteudo_html .= "<td colspan='5' class='textoCentro'>LEIS SOCIAIS =></td>";
                                $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($valorLeisSocias, NumeroUtil::NUMERO_BRA) . "</td>";
                                $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($percentualLeisSociais, NumeroUtil::NUMERO_BRA) . "</td>";
                                $conteudo_html .= "<td colspan='2'></td>";
                                $conteudo_html .= "</tr>";
                                $conteudo_html .= "<tr>";
                                $conteudo_html .= "<td colspan='5' class='textoCentro'>SUBTOTAL =></td>";
                                $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($subtotal, NumeroUtil::NUMERO_BRA) . "</td>";
                                $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($percentual, NumeroUtil::NUMERO_BRA) . "</td>";
                                $conteudo_html .= "<td colspan='2'></td>";
                                $conteudo_html .= "</tr>";
                            } else {
                                $valorLeisSocias = 0;
                                $percentualLeisSociais = 0;
                                $conteudo_html .= "<tr>";
                                $conteudo_html .= "<td colspan='5' class='textoCentro'>SUBTOTAL =></td>";
                                $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($subtotal, NumeroUtil::NUMERO_BRA) . "</td>";
                                $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($percentual, NumeroUtil::NUMERO_BRA) . "</td>";
                                $conteudo_html .= "<td colspan='2'></td>";
                                $conteudo_html .= "</tr>";
                            }
                        } else {
                            $conteudo_html .= "<tr class='textoCentro'><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>";
                        }
                    }
                    $totalComp = $totalComp + $subtotalSubComp + $valorLeisSocias;
                    $totalBDI = $totalComp * (1 + ($array_request["bdi"] / 100));
                    $conteudo_html .= "<tr>";
                    $conteudo_html .= "<td colspan='5' class='textoCentro'>TOTAL =></td>";
                    $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($totalComp, NumeroUtil::NUMERO_BRA) . "</td>";
                    $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($totalPercentual, NumeroUtil::NUMERO_BRA) . "</td>";
                    $conteudo_html .= "<td colspan='2'></td>";
                    $conteudo_html .= "</tr>";
                    $conteudo_html .= "<tr class='bordaInferior'>";
                    $conteudo_html .= "<td colspan='5' class='textoCentro'>TOTAL COM BDI =></td>";
                    $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($totalBDI, NumeroUtil::NUMERO_BRA) . "</td>";
                    $conteudo_html .= "<td></td>";
                    $conteudo_html .= "<td colspan='2'></td>";
                    $conteudo_html .= "</tr>";
                    $totalPercentual = 0;
                    $percentualLeisSociais = 0;
                }
            }
        } else {
            $conteudo_html .= "<tr><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>";
        }

        $conteudo_html .= "</tbody>";
        $conteudo_html .= "</table>";

        return $conteudo_html;
    }

    private function impCurvaABC($array_request) {
        $conteudo_html = "";
        $bdi = 0;
        $filtro_servico = "";

        $conteudo_html .= "<table id='tblImpConteudo'>";
        $conteudo_html .= "<thead class='negrito'>";
        $conteudo_html .= "<tr>";
        $conteudo_html .= "<th>TIPO</th>";
        $conteudo_html .= "<th>C&Oacute;DIGO</th>";
        $conteudo_html .= "<th>DESCRI&Ccedil;&Atilde;O</th>";
        $conteudo_html .= "<th>UNID.</th>";
        $conteudo_html .= "<th>QUANT.</th>";
        $conteudo_html .= "<th>PRE&Ccedil;O UNIT.(R$)</th>";
        $conteudo_html .= "<th>VALOR PARCIAL(R$)</th>";
        $conteudo_html .= "<th>DATA</th>";
        $conteudo_html .= "</tr>";
        $conteudo_html .= "</thead>";

        $conteudo_html .= "<tbody>";

        /* FILTRO */
        $select_rel3 = filter_input(INPUT_POST, "selectRel3");
        $select_servico = filter_input(INPUT_POST, "servico");

        if ($select_rel3 == 1) { //COM BDI
            $bdi = 1 + ($array_request["bdi"] / 100);
        } else if ($select_rel3 == 3) { //POR SERVICO
            $filtro_servico = " AND c.id = " . $select_servico;
        }

        $query = "SELECT c.nome AS servico, b.codigo, b.descricao, d.nome AS unidade, a.quantidade, a.preco_unitario, "
                . "DATE_FORMAT(a.data_cadastro, '%d/%m/%Y') AS dtCadastro "
                . "FROM orc_comp_insumo AS a, insumo AS b, servico AS c, unidade AS d, orc_composicao AS e "
                . "WHERE a.id_ins = b.codigo AND b.id_serv = c.id AND b.id_uni = d.id AND a.id_orc_comp = e.id "
                . "AND e.id_orc = " . $array_request["id_orc"]
                . $filtro_servico . " ORDER BY c.nome ASC";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        if ($qtdRegistros > 0) {
            while ($insumo = $this->conexao->getRegistros($resultado)) {
                $conteudo_html .= "<tr>";
                $conteudo_html .= "<td class='textoCentro'>" . $insumo["servico"] . "</td>";
                $conteudo_html .= "<td class='textoCentro'>" . $insumo["codigo"] . "</td>";
                $conteudo_html .= "<td>" . $insumo["descricao"] . "</td>";
                $conteudo_html .= "<td class='textoCentro'>" . $insumo["unidade"] . "</td>";
                $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($insumo["quantidade"], NumeroUtil::NUMERO_BRA) . "</td>";
                $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($insumo["preco_unitario"], NumeroUtil::NUMERO_BRA) . "</td>";

                $valor_parcial = NumeroUtil::multiplicar($insumo["quantidade"], $insumo["preco_unitario"]);
                if ($bdi != 0) {
                    $valor_parcial *= $bdi;
                }
                $conteudo_html .= "<td class='textoDir'>" . NumeroUtil::formatar($valor_parcial, NumeroUtil::NUMERO_BRA) . "</td>";

                $conteudo_html .= "<td class='textoCentro'>" . $insumo["dtCadastro"] . "</td>";

                $conteudo_html .= "</tr>";
            }
        } else {
            $conteudo_html .= "<tr class='textoCentro'><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>";
        }

        $conteudo_html .= "</tbody>";

        return $conteudo_html;
    }

    private function impCronograma($array_request) {
        $orcamentoDAO = new OrcamentoDAO();
        $cronogramaDAO = new CronogramaDAO();
        $cronogramaTitulo = new CronogramaTitulo();
        $tituloDAO = new TituloDAO();
        $conteudo_html = "";
        $cabecalho = "";

        $array_orcamento = $orcamentoDAO->getOrcamento($array_request["id_orc"]);

        $conteudo_html .= "<table id='tblImpConteudo'>";
        $conteudo_html .= "<thead>";
        $conteudo_html .= "<tr>";
        $conteudo_html .= "<th class='textoEsq'>ITEM</th>";
        $conteudo_html .= "<th class='textoEsq'>SERVIÇO</th>";
        $conteudo_html .= "<th>VALOR</th>";

        /* FILTRO */
        $select_rel = filter_input(INPUT_POST, "selectRel4");

        $cronograma = $cronogramaDAO->get($array_request["id_orc"]);
        $id_cabecalho = $cronograma->getCabecalho()->getId();

        for ($i = 1; $i <= $cronograma->getQuantidadeCabecalho(); $i++) {
            if ($i == 1) {
                if ($id_cabecalho == 1) {
                    $cabecalho = $i . " MÊS";
                } else if ($id_cabecalho == 2) {
                    $cabecalho = $i . " SEMANA";
                } else if ($id_cabecalho == 3) {
                    $cabecalho = $i . " DIA";
                }
            } else {
                if ($id_cabecalho == 1) {
                    $cabecalho = $i . " MESES";
                } else if ($id_cabecalho == 2) {
                    $cabecalho = $i . " SEMANAS";
                } else if ($id_cabecalho == 3) {
                    $cabecalho = $i . " DIAS";
                }
            }
            $conteudo_html .= "<th>$cabecalho</th>";
        }

        $conteudo_html .= "</thead>";

        $estilo_linha = "low";

        $query = "SELECT * FROM orc_titulo AS a WHERE a.id_orc = " . $cronograma->getOrcamento()->getId() . " ORDER BY a.num_item";
        $resultado = $this->conexao->executaQuery($query);
        while ($titulo = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<tr>";
            $conteudo_html .= "<td>" . $titulo["num_item"] . "</td>";
            $conteudo_html .= "<td>" . $titulo["titulo"] . "</td>";

            $total = $tituloDAO->getTotal($titulo["id"], $array_orcamento["encargo_social"], $array_orcamento["bdi"], FALSE);
            $conteudo_html .= "<td class='textoCentro'>";
            $conteudo_html .= NumeroUtil::formatar($total, NumeroUtil::NUMERO_BRA);
            $conteudo_html .= "</td>";

            $array_porcentagens = $cronogramaDAO->getPorcentagens($titulo["id"]);
            $porcentagem = "";
            for ($j = 1; $j <= $cronograma->getQuantidadeCabecalho(); $j++) {
                $conteudo_html .= "<td class='textoDir'>";

                if (!empty($array_porcentagens[$j - 1])) {
                    $porcentagem = $array_porcentagens[$j - 1];
                } else {
                    $porcentagem = "";
                }

                if ($select_rel == 3) {
                    $conteudo_html .= "$porcentagem";

                    $conteudo_html .= "<hr />";

                    $valorMes = $cronogramaTitulo->getValorMes($porcentagem, $total);
                    $conteudo_html .= NumeroUtil::formatar($valorMes, NumeroUtil::NUMERO_BRA);
                } else {
                    if ($select_rel == 1) {
                        $conteudo_html .= "$porcentagem";
                    }

                    $conteudo_html .= "<hr />";

                    if ($select_rel == 2) {
                        $valorMes = $cronogramaTitulo->getValorMes($porcentagem, $total);
                        $conteudo_html .= NumeroUtil::formatar($valorMes, NumeroUtil::NUMERO_BRA);
                    }
                }

                $conteudo_html .= "</td>";
            }

            $conteudo_html .= "</tr>";
        }

        $conteudo_html .= "</table>";

        return $conteudo_html;
    }

}

?>
