<?

include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/conexao/ConexaoMySql.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/modelo/Funcionario.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/util/DataUtil.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/sisorc/classes/util/StringUtil.php";

class CadastroDAO {

    private $conexao;
    private $funcionario;

    public function CadastroDAO() {
        $this->conexao = new ConexaoMySql();
        $this->funcionario = new Funcionario();

        if (isset($_SESSION["funcionario"]["codigo"])) {
            $this->funcionario->setCodigo($_SESSION["funcionario"]["codigo"]);
        }
    }

    public function listarCadastros($campos, $id_modulo) {

        $filtro = "";
        $filtroCodigo = "";
        $filtroNome = "";
        $conteudo_html_topo = "";
        $conteudo_html = "";
        $conteudo_vazio = "";
        $conteudo_html_rodape = "";

        if (!empty($campos["codigo"]) && !empty($campos["nome"])) {
            $filtro = "WHERE a.codigo = " . $campos["codigo"] . "AND a.nome LIKE '%" . $campos["nome"] . "%'";
        } elseif (!empty($campos["codigo"])) {
            $filtroCodigo = "WHERE a.codigo = " . $campos["codigo"];
        } else {
            $filtroNome = "WHERE a.nome LIKE '%" . $campos["nome"] . "%'";
        }

        $conteudo_html_topo .= "<table id='tabela' class='tablesorter'> ";
        $conteudo_html_topo .= "<thead>";
        $conteudo_html_topo .= "<tr>";
        $conteudo_html_topo .= "<th>CÓDIGO</th>";
        $conteudo_html_topo .= "<th>NOME</th>";
        $conteudo_html_topo .= "<th>FONE</th>";
        $conteudo_html_topo .= "<th>Editar</th>";
        $conteudo_html_topo .= "</tr>";
        $conteudo_html_topo .= "</thead>";

        $conteudo_html .= "<tbody>";
        $query = "SELECT a.*, DATE_FORMAT(a.data_cadastro, '%d/%m/%Y') AS dtCadastro "
                . "FROM pessoa AS a "
                . $filtro
                . $filtroCodigo
                . $filtroNome
                . " ORDER BY a.nome"; //echo $query;
        $resultado = $this->conexao->executaQuery($query);
        $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        while ($cadastro = $this->conexao->getRegistros($resultado)) {
            $conteudo_html .= "<tr>";
            $conteudo_html .= "<td>" . $cadastro["codigo"] . "</td>";
            $conteudo_html .= "<td>" . $cadastro["nome"] . "</td>";
            $conteudo_html .= "<td>" . $cadastro["telefone"] . " / " . $cadastro["celular"] . "</td>";
            $conteudo_html .= "<td class='celulaIcone cursor'>";
            $conteudo_html .= "<a href='frmCadastrar.php?id_cad=" . $cadastro["id"] . "&id=$id_modulo' />";
            $conteudo_html .= "<img src='../../imagens/icones/editar.png' title='Editar' alt='' class='editarCadastro tam16' />";
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

        $conteudo_vazio = "<tbody><tr><td>-</td><td>-</td><td>-</td><td>-</td></tr></tbody>";

        if ($qtdRegistros > 0) {
            return $conteudo_html_topo . $conteudo_html . $conteudo_html_rodape;
        } else {
            return $conteudo_html_topo . $conteudo_vazio . $conteudo_html_rodape;
        }
    }

    public function getCadastro($id) {
        $query = "SELECT a.*, DATE_FORMAT(a.data_nasc, '%d/%m/%Y') AS dt_nasc FROM pessoa AS a WHERE a.id = $id";
        $resultado = $this->conexao->executaQuery($query);
        $cadastro = $this->conexao->getRegistros($resultado);
        return $cadastro;
    }

    private function geraCodigo() {
        $codigo = "";
        do {
            $codigo = rand(111111, 999999);
            $query = "SELECT * FROM pessoa AS a WHERE a.codigo = $codigo";
            $resultado = $this->conexao->executaQuery($query);
            $qtdRegistros = $this->conexao->getQtdRegistros($resultado);
        } while ($qtdRegistros > 0);
        return $codigo;
    }

    public function cadastrar($pessoa) {
        $codigo = $this->geraCodigo();

        $query = "INSERT INTO pessoa (codigo, nome, data_nasc, sexo, est_civil, cpf, rg, pai, mae, endereco, "
                . "bairro, cidade, telefone, celular, email, usuario_cadastro, data_cadastro) "
                . "VALUES ($codigo, "
                . "'" . $pessoa["nome"] . "', "
                . "'" . DataUtil::formatar($pessoa["dt_nasc"], DataUtil::DATA_USA) . "', "
                . $pessoa["sexo"] . ", "
                . $pessoa["est_civil"] . ", "
                . "'" . $pessoa["cpf"] . "', "
                . "'" . $pessoa["rg"] . "', "
                . "'" . $pessoa["pai"] . "', "
                . "'" . $pessoa["mae"] . "', "
                . "'" . $pessoa["endereco"] . "', "
                . "'" . $pessoa["bairro"] . "', "
                . "'" . $pessoa["cidade"] . "', "
                . "'" . $pessoa["telefone"] . "', "
                . "'" . $pessoa["celular"] . "', "
                . "'" . $pessoa["email"] . "', "
                . $this->funcionario->getCodigo() . ","
                . "NOW())";
        return $this->conexao->executaQuery($query);
    }

    public function editar($pessoa) {
        $query = "UPDATE pessoa "
                . "SET nome = '" . $pessoa["nome"] . "', "
                . "data_nasc = '" . DataUtil::formatar($pessoa["dt_nasc"], DataUtil::DATA_USA) . "', "
                . "sexo = " . $pessoa["sexo"] . ", "
                . "est_civil = " . $pessoa["est_civil"] . ", "
                . "cpf = " . "'" . $pessoa["cpf"] . "', "
                . "rg = " . "'" . $pessoa["rg"] . "', "
                . "pai = '" . $pessoa["pai"] . "', "
                . "mae = '" . $pessoa["mae"] . "', "
                . "endereco = '" . $pessoa["endereco"] . "', "
                . "bairro = '" . $pessoa["bairro"] . "', "
                . "cidade = '" . $pessoa["cidade"] . "', "
                . "telefone = '" . $pessoa["telefone"] . "', "
                . "celular = '" . $pessoa["celular"] . "', "
                . "email = '" . $pessoa["email"] . "', "
                . "usuario_atualizacao = " . $this->funcionario->getCodigo() . ", "
                . "data_atualizacao = NOW() WHERE id = " . $pessoa["idCadastro"]; //echo $query;
        return $this->conexao->executaQuery($query);
    }

}

?>