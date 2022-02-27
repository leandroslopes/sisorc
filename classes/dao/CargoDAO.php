<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";

class CargoDAO {

    private $conexao;
    private $funcionario;

    /**
     * Metodo construtor.
     */
    public function CargoDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();
        
        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    /**
     * Lista os setores.
     * @return html
     */
    public function listarCargos() {
        $conteudo_html_topo = "";
        $conteudo_html = "";
        $conteudo_html_rodape = "";

        $conteudo_html_topo .= "<table id='tabela' class='tablesorter'>";
        $conteudo_html_topo .= "<thead>";
        $conteudo_html_topo .= "<tr>";
        $conteudo_html_topo .= "<th>CARGO</th>";
        $conteudo_html_topo .= "<th>DATA DE CADASTRO</th>";
        $conteudo_html_topo .= "<th>EDITAR</th>";
        $conteudo_html_topo .= "<th>EXCLUIR</th>";
        $conteudo_html_topo .= "</tr>";
        $conteudo_html_topo .= "</thead>";

        $conteudo_html .= "<tbody>";
        $query = "SELECT a.id, a.nome, a.usuario_cadastro, DATE_FORMAT(a.data_cadastro, '%d/%m/%Y') AS dtCadastro FROM cargo AS a ORDER BY a.nome";
        $resultado = $this->conexao->executaQuery($query);
        while ($cargo = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<tr>";
            $conteudo_html .= "<td>" . $cargo["nome"] . "</td>";
            $conteudo_html .= "<td>" . $cargo["dtCadastro"] . "</td>";
            $conteudo_html .= "<td class='celulaIcone cursor'>";
            $conteudo_html .= "<input type='hidden' name='idCargo' id='idCargo' value='" . $cargo["id"] . "'/>";
            $conteudo_html .= "<img src='../../imagens/icones/editar.png' title='Editar' alt='' class='editarCargo tam16' />";
            $conteudo_html .= "</td>";
            $conteudo_html .= "<td class='celulaIcone cursor'>";
            $conteudo_html .= "<img src='../../imagens/icones/excluir.png' title='Excluir' alt='' class='excluirCargo tam16' />";
            $conteudo_html .= "</td>";
            $conteudo_html .= "</tr>";
        }
        $conteudo_html .= "</tbody>";

        $conteudo_html_rodape .= "<tfoot> <tr> <th colspan='4'>";
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
     * Cadastra cargo.
     * @param string $cargo
     * @return boolean
     */
    public function cadastrarCargo($cargo) {
        $cargo = $this->conexao->antiInject($cargo);
        $query = "INSERT INTO cargo (nome, usuario_cadastro, data_cadastro) VALUES ('$cargo', " . $this->funcionario->getCodigo() . ", NOW())";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Verifica se determinado cargo se encontra cadastrado.
     * @param string $cargo
     * @return boolean
     */
    public function isCadastrado($cargo) {
        $cargo = $this->conexao->antiInject($cargo);
        $query = "SELECT * FROM cargo AS a WHERE a.nome = '$cargo'";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        if ($qtdRegistros > 0)
            return true;
        return false;
    }

    /**
     * Exclui cargo.
     * @param string $cargo
     * @return boolean
     */
    public function excluirCargo($cargo) {
        $cargo = $this->conexao->antiInject($cargo);
        $query = "DELETE FROM cargo WHERE nome = '$cargo'";
        return $this->conexao->executaQuery($query);
    }

    /**
     * Edita cargo.
     * @param inteiro $id
     * @param string $cargo
     * @return boolean
     */
    public function editarCargo($id, $cargo) {
        $cargo = $this->conexao->antiInject($cargo);
        $query = "UPDATE cargo SET nome = '$cargo', usuario_cadastro = " . $this->funcionario->getCodigo() . ", data_cadastro = NOW() WHERE id = $id";
        return $this->conexao->executaQuery($query);
    }
    
    /**
     * Verifica se o cargo está relacionado ao submódulo.
     * @param inteiro $id_cargo
     * @return boolean
     */
    public function estaSubmodulo($id_cargo) {
        $query = "SELECT * FROM submod_acesso AS a WHERE a.id_cargo = $id_cargo";
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        if ($qtdRegistros > 0) {
            return true;
        }
        return false;
    }
    
    /**
     * Retorna um 'select' com os cargos.
     * @param string $operacao
     * @return string
     */
    public function getSelect($operacao = "") {
        $conteudo_html = "";
        $select_id = "";
        
        $operacao == "add" ? $select_id = "cargo" : $select_id = "cargo_edd";        
        
        $query = "SELECT * FROM cargo AS a ORDER BY a.nome ASC";
        $resultado = $this->conexao->executaQuery($query);
        
        $conteudo_html .= "<select name='cargo' id='$select_id'>";
        while ($cargo = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<option value='" . $cargo["id"] . "'>" . $cargo["nome"] . "</option>";
        }
        $conteudo_html .= "</select>";

        return $conteudo_html;
    }
}

?>
