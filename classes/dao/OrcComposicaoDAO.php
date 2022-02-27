<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/dao/OrcComposicaoInsumoDAO.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/dao/OrcComposicaoSubcomposicaoDAO.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/dao/ComposicaoDAO.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/OrcComposicao.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Composicao.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/util/NumeroUtil.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/OrcComposicao.php";

class OrcComposicaoDAO {

    private $conexao;
    private $funcionario;

    /**
     * Metodo construtor.
     */
    public function OrcComposicaoDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();

        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    /**
     * Lista as composicoes de um orcamento.
     * @param inteiro $id_orc - ID de um orcamento.
     * @param decimal $encargo_social
     * @param decimal $bdi
     * @return string
     */
    public function listar($id_orc, $encargo_social, $bdi) {
        $orcCompSubcompDAO = new OrcComposicaoSubcomposicaoDAO();
        $orcCompInsumoDAO = new OrcComposicaoInsumoDAO();

        $conteudo_html = "";
        $conteudo_vazio = "";
        $estiloLinha = "low";
        $ativa = "";
        $totalComp = 0;
        $subtotalSubComp = 0;
        $valorSubComp = 0;
        $totalCompPai = 0;
        $precoSubcompIns = 0;
        $totalPercentual = 0;
        $percentualLeisSociais = 0;
        $valorLeisSocias = 0;

        $conteudo_html .= "<tbody>";
        $queryComposicoes = "SELECT a.id AS id_orc_comp, b.codigo, b.descricao, c.nome AS unidade, a.ativa "
                . "FROM orc_composicao AS a, composicao AS b, unidade AS c, orcamento AS d "
                . "WHERE b.id_uni = c.id AND a.id_comp = b.codigo AND a.id_orc = d.id AND a.id_orc = $id_orc";
        $resultadoComposicoes = $this->conexao->executaQuery($queryComposicoes);
        $qtdComposicoes = $this->conexao->getQtdRegistros($resultadoComposicoes);
        while ($composicao = $this->conexao->getRegistros($resultadoComposicoes)) {
            $conteudo_html .= "<tr class='composicao'>";
            $conteudo_html .= "<td>";
            $conteudo_html .= "<input type='hidden' name='id_orc_comp' id='id_orc_comp' value='" . $composicao["id_orc_comp"] . "'/>";
            $conteudo_html .= "<input type='hidden' name='id_orc' id='id_orc' value='$id_orc'/>";
            $conteudo_html .= "<input type='hidden' name='cod_comp' id='cod_comp' value='" . $composicao["codigo"] . "'/>SERVIÇO =>";
            $conteudo_html .= "</td>";
            $conteudo_html .= "<td>";
            $conteudo_html .= "<label>" . $composicao["codigo"] . "</label> <label>" . $composicao["descricao"] . "</label>";
            $conteudo_html .= "&nbsp; <img src='../../imagens/icones/editar.png' title='Editar descrição' alt='' class='editarDescComp cursor tam16'/>";
            $conteudo_html .= "</td>";
            $conteudo_html .= "<td>UNIDADE: " . $composicao["unidade"] . "</td>";
            $conteudo_html .= "<td>&nbsp;</td>";
            $conteudo_html .= "<td>&nbsp;</td>";
            $conteudo_html .= "<td>&nbsp;</td>";
            $conteudo_html .= "<td class='textoCentro'>";
            $conteudo_html .= "<a class='cursor opcao adicionarSubcomp'>+ Adicionar <br /> Subcomposição</a>";
            $conteudo_html .= "</td>";
            $conteudo_html .= "<td class='textoCentro'>";
            $conteudo_html .= "<a class='cursor opcao addOrcCompIns'>+ Adicionar <br /> Insumo</a>";
            $conteudo_html .= "</td>";

            if ($composicao["ativa"] == OrcComposicao::ATIVADA) {
                $ativa = "<img src='../../imagens/icones/ativar.png' title='Ativar' alt='' class='ativarComp tam16'/>";
                $ativa .= "<img src='../../imagens/icones/excluir.png' title='Excluir' alt='' class='excluirOrcComp cursor tam16'/>";
            } else {
                $ativa = "<img src='../../imagens/icones/desativar.png' title='Desativar' alt='' class='ativarComp tam16'/>";
                $ativa .= "<img src='../../imagens/icones/excluir.png' title='Excluir' alt='' class='excluirOrcComp cursor tam16'/>";
            }
            $conteudo_html .= "<td class='celulaIcone cursor' id=''>";
            $conteudo_html .= "<input type='hidden' name='ativa' id='ativa' value='" . $composicao["ativa"] . "'/>$ativa";
            $conteudo_html .= "</td>";
            $conteudo_html .= "</tr>";

            $subtotalSubComp = $orcCompSubcompDAO->getTotalSubcomposicoes($composicao["id_orc_comp"], $encargo_social, $bdi, FALSE);

            $querySubcomposicoes = "SELECT * FROM orc_comp_subcomp AS a WHERE a.id_orc_comp = " . $composicao["id_orc_comp"];
            $resultadoSubcomposicoes = $this->conexao->executaQuery($querySubcomposicoes);
            $qtdSubcomposicoes = $this->conexao->getQtdRegistros($resultadoSubcomposicoes);
            if ($qtdSubcomposicoes > 0) {
                $totalComp = 0;
                while ($subcomposicao = $this->conexao->getRegistros($resultadoSubcomposicoes)) {
                    $querySubcomposicao = "SELECT c.nome AS servico, b.codigo, b.descricao, d.nome AS unidade, a.quantidade "
                            . "FROM orc_composicao AS a, composicao AS b, servico AS c, unidade AS d "
                            . "WHERE a.id_comp = b.codigo AND b.id_serv = c.id AND b.id_uni = d.id AND a.id = " . $subcomposicao["id_orc_subcomp"];
                    $resultadoSubcomposicao = $this->conexao->executaQuery($querySubcomposicao);
                    $arraySubComp = $this->conexao->getRegistros($resultadoSubcomposicao);

                    $conteudo_html .= "<tr class='$estiloLinha textoCentro'>";
                    $conteudo_html .= "<td>";
                    $conteudo_html .= "<input type='hidden' name='id_subcomp' id='id_subcomp' value='" . $subcomposicao["id_orc_subcomp"] . "'/>";
                    $conteudo_html .= "<input type='hidden' name='id_sub' id='id_sub' value='" . $subcomposicao["id"] . "'/>";
                    $conteudo_html .= $arraySubComp["servico"] . " " . $arraySubComp["codigo"] . "</td>";
                    $conteudo_html .= "<td>" . $arraySubComp["descricao"] . "</td>";
                    $conteudo_html .= "<td>" . $arraySubComp["unidade"] . "</td>";
                    $conteudo_html .= "<td>" . NumeroUtil::formatar($arraySubComp["quantidade"], NumeroUtil::NUMERO_BRA) . "</td>";

                    $precoSubcompIns = $orcCompInsumoDAO->getTotalInsumos($subcomposicao["id_orc_subcomp"], $encargo_social, $bdi, FALSE);
                    $conteudo_html .= "<td>" . NumeroUtil::formatar($precoSubcompIns, NumeroUtil::NUMERO_BRA) . "</td>";

                    $valorSubComp = NumeroUtil::multiplicar($arraySubComp["quantidade"], $precoSubcompIns);
                    $conteudo_html .= "<td>" . NumeroUtil::formatar($valorSubComp, NumeroUtil::NUMERO_BRA) . "</td>";

                    $totalCompPai = $orcCompInsumoDAO->getTotalInsumos($composicao["id_orc_comp"], $encargo_social, 0, FALSE);
                    $percentual = (($valorSubComp / ($subtotalSubComp + $totalCompPai)) * 100);
                    $conteudo_html .= "<td>" . NumeroUtil::formatar($percentual, NumeroUtil::NUMERO_BRA) . "</td>";

                    $conteudo_html .= "<td></td>";
                    $conteudo_html .= "<td class='celulaIcone cursor'>";
                    $conteudo_html .= "<img src='../../imagens/icones/excluir.png' title='Excluir' alt='' class='excOrcCompSub tam16'/>";
                    $conteudo_html .= "<img src='../../imagens/icones/editar.png' title='Editar' alt='' class='eddOrcCompSub tam16'/></td>";
                    $conteudo_html .= "</td>";
                    $conteudo_html .= "</tr>";

                    $estiloLinha == "low" ? $estiloLinha = "high" : $estiloLinha = "low";
                }

                $percentual = (($subtotalSubComp / ($subtotalSubComp + $totalCompPai)) * 100);
                $totalPercentual += $percentual;
                $conteudo_html .= "<tr class='$estiloLinha'>";
                $conteudo_html .= "<td colspan='5' class='textoCentro'>SUBTOTAL =></td>";
                $conteudo_html .= "<td class='textoCentro'>" . NumeroUtil::formatar($subtotalSubComp, NumeroUtil::NUMERO_BRA) . "</td>";
                $conteudo_html .= "<td class='textoCentro'>" . NumeroUtil::formatar($percentual, NumeroUtil::NUMERO_BRA) . "</td>";
                $conteudo_html .= "<td colspan='2'></td>";
                $conteudo_html .= "</tr>";
                $estiloLinha == "low" ? $estiloLinha = "high" : $estiloLinha = "low";
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
                            $conteudo_html .= "<tr class='$estiloLinha textoCentro'>";
                            $conteudo_html .= "<td><input type='hidden' name='idOrcCompIns' id='idOrcCompIns' value='" . $insumo["idOrcCompIns"] . "'/>";
                            $conteudo_html .= $insumo["servico"] . " " . $insumo["codigo"] . "</td>";
                            $conteudo_html .= "<td>" . $insumo["descricao"] . "</td>";
                            $conteudo_html .= "<td>" . $insumo["unidade"] . "</td>";
                            $conteudo_html .= "<td>" . NumeroUtil::formatar($insumo["quantidade"], NumeroUtil::NUMERO_BRA) . "</td>";
                            $conteudo_html .= "<td>" . NumeroUtil::formatar($insumo["preco_unitario"], NumeroUtil::NUMERO_BRA) . "</td>";

                            $valorInsumo = NumeroUtil::multiplicar($insumo["quantidade"], $insumo["preco_unitario"]);
                            $subtotal += $valorInsumo;
                            $conteudo_html .= "<td>" . NumeroUtil::formatar($valorInsumo, NumeroUtil::NUMERO_BRA) . "</td>";

                            $totalCompPai = $orcCompInsumoDAO->getTotalInsumos($insumo["id_orc_comp"], $encargo_social, 0, FALSE);
                            if ($valorInsumo != 0) {
                                $percentual = (($valorInsumo / ($subtotalSubComp + $totalCompPai)) * 100);
                            } else {
                                $percentual = 0;
                            }
                            $conteudo_html .= "<td>" . NumeroUtil::formatar($percentual, NumeroUtil::NUMERO_BRA) . "</td>";

                            $conteudo_html .= "<td>" . $insumo["dtCadastro"] . "</td>";
                            $conteudo_html .= "<td class='celulaIcone cursor'>";
                            $conteudo_html .= "<img src='../../imagens/icones/editar.png' title='Editar' alt='' class='editarOrcCompIns tam16'/></td>";
                            $conteudo_html .= "</td>";
                            $conteudo_html .= "</tr>";

                            $nomeServico = $insumo["servico"];

                            if ($valorInsumo != 0) {
                                $valorLeisSocias += (($encargo_social / $valorInsumo) * 100);
                            }

                            $estiloLinha == "low" ? $estiloLinha = "high" : $estiloLinha = "low";
                        }
                        $totalComp += $subtotal;

                        if ($subtotal != 0) {
                            $percentual = (($subtotal / ($subtotalSubComp + $totalCompPai)) * 100);
                        } else {
                            $percentual = 0;
                        }
                        $totalPercentual += $percentual;

                        if ($nomeServico == "MO") {
                            if ($valorLeisSocias != 0) {
                                $percentualLeisSociais = (($valorLeisSocias / ($subtotalSubComp + $totalCompPai)) * 100);
                            } else {
                                $percentualLeisSociais = 0;
                            }
                            $totalPercentual += $percentualLeisSociais;

                            $conteudo_html .= "<tr class='$estiloLinha textoCentro'>";
                            $conteudo_html .= "<td colspan='5' class=''>LEIS SOCIAIS =></td>";
                            $conteudo_html .= "<td>" . NumeroUtil::formatar($valorLeisSocias, NumeroUtil::NUMERO_BRA) . "</td>";
                            $conteudo_html .= "<td>" . NumeroUtil::formatar($percentualLeisSociais, NumeroUtil::NUMERO_BRA) . "</td>";
                            $conteudo_html .= "<td colspan='2'></td>";
                            $conteudo_html .= "</tr>";
                            $estiloLinha == "low" ? $estiloLinha = "high" : $estiloLinha = "low";
                            $conteudo_html .= "<tr class='$estiloLinha textoCentro'>";
                            $conteudo_html .= "<td colspan='5' class=''>SUBTOTAL =></td>";
                            $conteudo_html .= "<td>" . NumeroUtil::formatar($subtotal, NumeroUtil::NUMERO_BRA) . "</td>";
                            $conteudo_html .= "<td>" . NumeroUtil::formatar($percentual, NumeroUtil::NUMERO_BRA) . "</td>";
                            $conteudo_html .= "<td colspan='2'></td>";
                            $conteudo_html .= "</tr>";
                            $estiloLinha == "low" ? $estiloLinha = "high" : $estiloLinha = "low";
                        } else {
                            $valorLeisSocias = 0;
                            $percentualLeisSociais = 0;
                            $conteudo_html .= "<tr class='$estiloLinha textoCentro'>";
                            $conteudo_html .= "<td colspan='5' class=''>SUBTOTAL =></td>";
                            $conteudo_html .= "<td>" . NumeroUtil::formatar($subtotal, NumeroUtil::NUMERO_BRA) . "</td>";
                            $conteudo_html .= "<td>" . NumeroUtil::formatar($percentual, NumeroUtil::NUMERO_BRA) . "</td>";
                            $conteudo_html .= "<td colspan='2'></td>";
                            $conteudo_html .= "</tr>";
                            $estiloLinha == "low" ? $estiloLinha = "high" : $estiloLinha = "low";
                        }
                    } else {
                        $estiloLinha == "low" ? $estiloLinha = "high" : $estiloLinha = "low";
                        $conteudo_html .= "<tr class='low textoCentro'><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td></tr>";
                    }
                }
                $totalComp = $totalComp + $subtotalSubComp + $valorLeisSocias;
                $totalBDI = $totalComp * (1 + ($bdi / 100));
                $conteudo_html .= "<tr class='$estiloLinha textoCentro'>";
                $conteudo_html .= "<td colspan='5'>TOTAL =></td>";
                $conteudo_html .= "<td>" . NumeroUtil::formatar($totalComp, NumeroUtil::NUMERO_BRA) . "</td>";
                $conteudo_html .= "<td>" . NumeroUtil::formatar($totalPercentual, NumeroUtil::NUMERO_BRA) . "</td>";
                $conteudo_html .= "<td colspan='2'></td>";
                $conteudo_html .= "</tr>";
                $estiloLinha == "low" ? $estiloLinha = "high" : $estiloLinha = "low";
                $conteudo_html .= "<tr class='$estiloLinha textoCentro'>";
                $conteudo_html .= "<td colspan='5'>TOTAL COM BDI =></td>";
                $conteudo_html .= "<td>" . NumeroUtil::formatar($totalBDI, NumeroUtil::NUMERO_BRA) . "</td>";
                $conteudo_html .= "<td>-</td>";
                $conteudo_html .= "<td colspan='2'></td>";
                $conteudo_html .= "</tr>";
                $estiloLinha == "low" ? $estiloLinha = "high" : $estiloLinha = "low";
                $totalPercentual = 0;
                $percentualLeisSociais = 0;
            }
        }
        $conteudo_html .= "</tbody>";
        $conteudo_vazio .= "<tbody><tr class='low textoCentro'><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td></td></tr></tbody>";
        if ($qtdComposicoes > 0) {
            return $conteudo_html;
        } else {
            return $conteudo_vazio;
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
     * Altera a quantidade da composicao (e/ou subcomposicao).
     * @param OrcComposicao $composicao - Um objeto do tipo OrcComposicao.
     * @return boolean
     */
    public function alterarQuantidade($composicao) {
        $query = "UPDATE orc_composicao "
                . "SET quantidade = " . NumeroUtil::formatar($composicao->getQuantidade(), NumeroUtil::NUMERO_USA)
                . " WHERE id = " . $composicao->getId();
        return $this->conexao->executaQuery($query);
    }

    /**
     * Ativa e/ou desativa uma composicao.
     * @param OrcComposicao $composicao
     * @return inteiro
     */
    public function ativar($composicao) {
        $query = "UPDATE orc_composicao "
                . "SET ativa = " . $composicao->getAtiva()
                . " WHERE id = " . $composicao->getId();
        if ($this->conexao->executaQuery($query)) {
            return $composicao->getAtiva();
        } else {
            if ($composicao->getAtiva() == OrcComposicao::ATIVADA) {
                return OrcComposicao::DESATIVADA;
            } else {
                return OrcComposicao::ATIVADA;
            }
        }
    }

    /**
     * Copia uma composicao ao orcamento.
     * @param OrcComposicao $orc_comp
     * @return boolean
     */
    public function copiar($orc_comp) {
        $qtdOrcCompIns = 0;
        $qtdCompIns = 0;

        $queryCompCop = "SELECT a.id_comp, a.quantidade FROM orc_composicao AS a WHERE a.id = " . $orc_comp->getId();
        $resultOrcCompCop = $this->conexao->executaQuery($queryCompCop);
        while ($composicaoCop = $this->conexao->getRegistros($resultOrcCompCop)) {
            $queryOrcComp = "INSERT INTO orc_composicao (id_orc, id_comp, quantidade) "
                    . "VALUES (" . $orc_comp->getOrcamento()->getId() . ", " . $composicaoCop["id_comp"] . ", " . $composicaoCop["quantidade"] . ")";

            if ($this->conexao->executaQuery($queryOrcComp)) {
                $queryOrcCompInsCop = "SELECT a.id_ins, a.quantidade, a.preco_unitario, a.data_cadastro FROM orc_comp_insumo AS a "
                        . "WHERE a.id_orc_comp = " . $orc_comp->getId();
                $resultOrcCompInsCop = $this->conexao->executaQuery($queryOrcCompInsCop);
                $qtdOrcCompIns = $this->conexao->getQtdRegistros($resultOrcCompInsCop);

                if ($qtdOrcCompIns > 0) {
                    while ($compIns = $this->conexao->getRegistros($resultOrcCompInsCop)) {
                        $queryOrcCompIns = "INSERT INTO orc_comp_insumo (id_orc_comp, id_ins, quantidade, preco_unitario, data_cadastro) "
                                . "VALUES (" . $this->getIdUltimoOrcComp() . ", " . $compIns["id_ins"] . ", " . $compIns["quantidade"] . ", "
                                . $compIns["preco_unitario"] . ", '" . $compIns["data_cadastro"] . "')";
                        if ($this->conexao->executaQuery($queryOrcCompIns)) {
                            $qtdCompIns++;
                        }
                    }
                }
            } else {
                return false;
            }
        }

        if ($qtdOrcCompIns == $qtdCompIns) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Exclui a composicao do orcamento.
     * @param OrcComposicao $orc_composicao
     * @return boolean
     */
    public function excluir($orc_composicao) {
        $query1 = "SELECT a.id "
                . "FROM orc_composicao AS a "
                . "WHERE a.id_orc = " . $orc_composicao->getOrcamento()->getId()
                . " AND a.id_comp = " . $orc_composicao->getComposicao()->getCodigo();
        $resultado1 = $this->conexao->executaQuery($query1);
        $orc_composicao = $this->conexao->getRegistros($resultado1);

        $query2 = "DELETE FROM orc_composicao WHERE id = " . $orc_composicao["id"];
        if ($this->conexao->executaQuery($query2)) {
            $query3 = "DELETE FROM orc_comp_subcomp WHERE id_orc_comp = " . $orc_composicao["id"];
            $resultado3 = $this->conexao->executaQuery($query3);

            $query3 = "DELETE FROM orc_comp_insumo WHERE id_orc_comp = " . $orc_composicao["id"];
            $resultado3 = $this->conexao->executaQuery($query3);

            $query4 = "DELETE FROM titulo_composicao WHERE id_orc_comp = " . $orc_composicao["id"];
            $resultado4 = $this->conexao->executaQuery($query4);

            $query5 = "DELETE FROM subtitulo_composicao WHERE id_orc_comp = " . $orc_composicao["id"];
            $resultado5 = $this->conexao->executaQuery($query5);

            return true;
        }
        return false;
    }

    /**
     * Retorna um 'select' com as composicoes de um orcamento <br /> que ainda nao foram adicionadas num titulo ou subtitulo.
     * @param inteiro $id_orc
     * @return string
     */
    public function getSelectOrcComp($id_orc) {
        $conteudo_html = "";

        $query = "SELECT a.id, b.descricao FROM orc_composicao AS a, composicao AS b "
                . "WHERE a.id_comp = b.codigo AND a.id NOT IN (SELECT id_orc_comp FROM titulo_composicao) "
                . "AND a.id NOT IN (SELECT id_orc_comp FROM subtitulo_composicao) AND a.id_orc = $id_orc ORDER BY b.descricao ASC";
        $resultado = $this->conexao->executaQuery($query);

        $conteudo_html .= "<select name = 'orc_composicao' id = 'orc_composicao' class='selectComp'>";
        while ($composicao = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<option value = '" . $composicao["id"] . "'>" . $composicao["descricao"] . "</option>";
        }
        $conteudo_html .= "</select>";

        return $conteudo_html;
    }

    /**
     * Verifica se a composicao de custo ja tem uma composicao <br /> com a determinada descricao.
     * @param inteiro $id_orc
     * @param string $descricao
     * @return boolean
     */
    public function jaCadastrado($id_orc, $descricao) {
        $query = "SELECT * "
                . "FROM orc_composicao AS a, composicao AS b "
                . "WHERE a.id_comp = b.codigo AND a.id_orc = $id_orc AND b.descricao = '$descricao'";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistro = $this->conexao->getQtdRegistros($resultado);
        if ($qtdRegistro > 0) {
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * Edita a descricao de uma composicao do orcamento.
     * @param inteiro $id_orc_comp
     * @param inteiro $cod_comp_antigo
     * @param string $descricao
     * @return boolean
     */
    public function editarDescricao($id_orc_comp, $cod_comp_antigo, $descricao) {
        $composicaoDAO = new ComposicaoDAO();
        $orcComposicao = new OrcComposicao();
        $composicao = new Composicao();
        
        $array_comp_antigo = $composicaoDAO->getComposicao($cod_comp_antigo);
        
        $array_comp_nova = array();
        $array_comp_nova["descricao"] = $descricao;
        $array_comp_nova["unidade"] = $array_comp_antigo["id_uni"];
        $array_comp_nova["tipo"] = $array_comp_antigo["id_tipo"];
        
        if ($composicaoDAO->cadastrar($array_comp_nova)) {
            $array_comp_nova = $composicaoDAO->getComposicaoPorNome($descricao);
            
            $composicao->setCodigo($array_comp_nova["codigo"]);
            
            $orcComposicao->setId($id_orc_comp);
            $orcComposicao->setComposicao($composicao);
            
            if ($this->alterarCodigoComposicao($orcComposicao)) {
                return TRUE;
            }
        }
        return FALSE;
    }
    
    /**
     * 
     * @param OrcComposicao $orc_composicao
     * @return boolean
     */
    public function alterarCodigoComposicao($orc_composicao) {
        $query = "UPDATE orc_composicao "
                . "SET id_comp = " . $orc_composicao->getComposicao()->getCodigo()
                . " WHERE id = " . $orc_composicao->getId();
        return $this->conexao->executaQuery($query);
    }
}

?>