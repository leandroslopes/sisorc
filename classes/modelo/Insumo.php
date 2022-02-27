<?

class Insumo {

    private $codigo;
    private $descricao;
    /**
     *
     * @var Unidade 
     */
    private $unidade;
    /**
     *
     * @var Servico 
     */
    private $servico;
    /**
     *
     * @var Tipo 
     */
    private $tipo;

    public function Insumo() {
        
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function getUnidade() {
        return $this->unidade;
    }

    public function getServico() {
        return $this->servico;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function setUnidade($unidade) {
        $this->unidade = $unidade;
    }

    public function setServico($servico) {
        $this->servico = $servico;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }



}
?>

