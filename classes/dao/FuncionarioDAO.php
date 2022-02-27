<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";

class FuncionarioDAO {

    private $conexao;
    private $funcionario;

    /**
     * Metodo construtor.
     */
    public function FuncionarioDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();

        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    /**
     * Verifica se o funcionario tem acesso ao sistema.
     * @param Funcionario $funcionario
     * @return boolean
     */
    public function login($funcionario) {
        $query = "SELECT * 
                  FROM funcionario as a 
                  WHERE a.codigo = " . $this->conexao->antiInject($funcionario->getCodigo()) . "
                  AND a.senha = '" . md5($this->conexao->antiInject($funcionario->getSenha())) . "' 
                  AND a.situacao = " . Funcionario::ATIVO; //echo $query;
        $resultado = $this->conexao->executaQuery($query);
        $linhas = $this->conexao->getQtdRegistros($resultado);
        if ($linhas > 0) {
            return true;
        }
        return false;
    }

    /**
     * Retorna o registro de um funcionário.
     * @param inteiro $codigo
     * @return array
     */
    public function getFuncionario($codigo) {
        $query = "SELECT a.codigo, b.nome AS nomeFuncionario, a.id_cargo, c.nome AS nomeCargo, a.id_setor, d.nome AS nomeSetor
                  FROM funcionario AS a, pessoa AS b, cargo AS c, setor AS d 
                  WHERE a.codigo = b.codigo AND a.id_cargo = c.id AND a.id_setor = d.id AND a.codigo = $codigo";
        $resultado = $this->conexao->executaQuery($query);
        return $this->conexao->getRegistros($resultado);
    }

    /**
     * Altera a senha do funcionário.
     * @param string $senha
     * @return boolean
     */
    public function alterarSenha($senha) {
        $query = "UPDATE funcionario SET senha = '" . md5($senha) . "' WHERE codigo = " . $this->funcionario->getCodigo();
        if ($this->conexao->executaQuery($query)) {
            return true;
        }
        return false;
    }

    /**
     * Lista os funcioarios.
     * @return string
     */
    public function listar() {
        $conteudo_html_topo = "";
        $conteudo_html = "";
        $conteudo_html_rodape = "";

        $conteudo_html_topo .= "<table id='tabela' class='tablesorter'> ";
        $conteudo_html_topo .= "<thead>";
        $conteudo_html_topo .= "<tr>";
        $conteudo_html_topo .= "<th>NOME</th>";
        $conteudo_html_topo .= "<th>CARGO</th>";
        $conteudo_html_topo .= "<th>SETOR</th>";
        $conteudo_html_topo .= "<th>SITUAÇÃO</th>";
        $conteudo_html_topo .= "<th>EDITAR</th>";
        $conteudo_html_topo .= "</tr>";
        $conteudo_html_topo .= "</thead>";

        $conteudo_html .= "<tbody>";
        $query = "SELECT a.codigo, b.nome, c.id AS id_cargo, c.nome AS cargo, d.id AS id_setor, d.nome AS setor, a.situacao "
                . "FROM funcionario AS a, pessoa AS b, cargo AS c, setor AS d "
                . "WHERE a.codigo = b.codigo AND a.id_cargo = c.id AND a.id_setor = d.id ORDER BY b.nome ASC ";
        $resultado = $this->conexao->executaQuery($query);
        while ($funcionario = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<tr>";
            $conteudo_html .= "<td>";
            $conteudo_html .= "<input type='hidden' name='codigo_func' id='codigo_func' value='" . $funcionario["codigo"] . "'/>";
            $conteudo_html .= "<label>" . $funcionario["nome"] . "</label>";
            $conteudo_html .= "</td>";
            $conteudo_html .= "<td>";
            $conteudo_html .= "<input type='hidden' name='id_cargo' id='id_cargo' value='" . $funcionario["id_cargo"] . "'/>";
            $conteudo_html .= "<label>" . $funcionario["cargo"] . "</label>";
            $conteudo_html .= "</td>";
            $conteudo_html .= "<td>";
            $conteudo_html .= "<input type='hidden' name='id_setor' id='id_setor' value='" . $funcionario["id_setor"] . "'/>";
            $conteudo_html .= "<label>" . $funcionario["setor"] . "</label>";
            $conteudo_html .= "</td>";

            $conteudo_html .= "<td>";
            $conteudo_html .= "<input type='hidden' name='id_situacao' id='id_situacao' value='" . $funcionario["situacao"] . "'/>";
            $conteudo_html .= "<label>" . ($funcionario["situacao"] == Funcionario::ATIVO ? "ATIVO" : "INATIVO") . "</label>";
            $conteudo_html .= "</td>";

            $conteudo_html .= "<td class='celulaIcone cursor'>";
            $conteudo_html .= "<img src='../../imagens/icones/editar.png' title='Editar' alt='' class='editarFuncionario tam16' />";
            $conteudo_html .= "</td>";
            $conteudo_html .= "</tr>";
        }
        $conteudo_html .= "</tbody>";

        $conteudo_html_rodape .= "<tfoot> <tr> <th colspan='5'>";
        $conteudo_html_rodape .= "<div id='paginacao' class='paginacao'> <form action=''>";
        $conteudo_html_rodape .= "<img src='../../plugins/jquery-tablesorter/addons/pager/icons/first.png' alt='' class='first'/>";
        $conteudo_html_rodape .= "<img src='../../plugins/jquery-tablesorter/addons/pager/icons/prev.png' alt='' class='prev'/>";
        $conteudo_html_rodape .= "<input type='text' class='pagedisplay' size='5'/>";
        $conteudo_html_rodape .= "<img src='../../plugins/jquery-tablesorter/addons/pager/icons/next.png' alt='' class='next'/>";
        $conteudo_html_rodape .= "<img src='../../plugins/jquery-tablesorter/addons/pager/icons/last.png' alt='' class='last'/>";
        $conteudo_html_rodape .= "<select class='pagesize'>";
        $conteudo_html_rodape .= "<option selected='selected' value='10'>10</option>";
        $conteudo_html_rodape .= "<option value='20'>20</option>";
        $conteudo_html_rodape .= "</select> </form> </div> </th> </tr> </tfoot> </table>";

        return $conteudo_html_topo . $conteudo_html . $conteudo_html_rodape;
    }

    /**
     * Pesquisa os cadastrados para adicionar como funcionario.
     * @param inteiro $codigo
     * @return array
     */
    public function pesquisarPessoasAdd($codigo) {
        $funcionarios = "";

        $query = "SELECT b.codigo, b.nome FROM pessoa AS b "
                . "WHERE b.codigo = $codigo AND b.codigo NOT IN (SELECT codigo FROM funcionario) "
                . "ORDER BY b.nome ASC ";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);

        $x = 0;
        while ($funcionario = $this->conexao->getRegistros($resultado)) {
            $x = $x + 1;
            if ($x == 1) {
                $funcionarios = array(array($funcionario["codigo"] => $funcionario["nome"]));
            } else {
                $funcionarios[] = array($funcionario["codigo"] => $funcionario["nome"]);
            }
        }

        if ($qtdRegistros > 0) {
            return $funcionarios;
        }
        return "";
    }

    /**
     * Adiciona um funcionario.
     * @param Funcionario $funcionario
     * @return boolean
     */
    public function adicionar($funcionario) {
        $query = "INSERT INTO funcionario (codigo, senha, id_cargo, id_setor) "
                . "VALUES (" . $funcionario->getCodigo() . ", "
                . "'" . $funcionario->getSenha() . "', "
                . $funcionario->getCargo()->getId() . ", "
                . $funcionario->getSetor()->getId() . ")";
        return $this->conexao->executaQuery($query);
    }

    
    /**
     * Edita dados do funcionario.
     * @param Funcionario $funcionario
     * @return type
     */
    public function editar($funcionario) {
        $query = "UPDATE funcionario "
                . "SET id_cargo = " . $funcionario->getCargo()->getId() . ", "
                . " id_setor = " . $funcionario->getSetor()->getId() . ", "
                . " situacao = " . $funcionario->getSituacao() 
                . " WHERE codigo = " . $funcionario->getCodigo();
        return $this->conexao->executaQuery($query);
    }
}

?>
