<?php

include_once("Conexao.php");

class ConexaoMySql extends Conexao {

    const SERVIDOR = "localhost";
    const USUARIO = "root";
    const SENHA = "apotex";
    const BANCO_DE_DADOS = "apotex_dev";
    
    private $conexao;
            
    /**
     * Construtor
     */
    public function ConexaoMySql() {
        $this->conectar();
    }

    /**
     * Conecta com o banco de dados.
     * @return resource
     */
    public function conectar() {
        $this->conexao = mysqli_connect(ConexaoMySql::SERVIDOR, ConexaoMySql::USUARIO, ConexaoMySql::SENHA);
        if ($this->conexao) {
            mysqli_select_db($this->conexao, ConexaoMySql::BANCO_DE_DADOS);
        }
        return $this->conexao;
    }

    /**
     * Executa a query.
     * @param string $query
     * @return resource
     */
    public function executaQuery($query) {
        return mysqli_query($this->conexao, $query);
    }

    /**
     * Retorna a quantidade de registros encontrados.
     * @param resource $resultado
     * @return int
     */
    public function getQtdRegistros($resultado) {
        return mysqli_num_rows($resultado);
    }

    /**
     * Retorna um array com todos os registros encontrados.
     * @param resource $resultado
     * @return array
     */
    public function getRegistros($resultado) {
        return mysqli_fetch_array($resultado);
    }

    /**
     * Escapa caracteres especiais para o comando SQL.
     * @param string $string
     * @return string
     */
    public function antiInject($string) {
        $string = trim($string); // Remove espacos
        $string = strip_tags($string); // Remove tags
        $string = addslashes($string); // Barras invertidas
        $string = mysqli_real_escape_string($this->conexao, $string); // Escapa caracteres
        return $string;
    }

}

?>
