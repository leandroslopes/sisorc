<?php

/**
 * Classe abstrata Conexao
 */
abstract class Conexao {

    public function Conexao() {
        
    }

    abstract protected function conectar();

    abstract protected function executaQuery($query);

    abstract protected function getQtdRegistros($resultado);

    abstract protected function getRegistros($resultado);

    abstract protected function antiInject($string);
}

?>
