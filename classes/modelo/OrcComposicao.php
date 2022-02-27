<?

class OrcComposicao {

    const ATIVADA = 1;
    const DESATIVADA = 2;

    private $id;
    /**
     *
     * @var Orcamento 
     */
    private $orcamento;
    /**
     *
     * @var Composicao 
     */
    private $composicao;
    private $quantidade;
    private $ativa;

    public function OrcComposicao() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getOrcamento() {
        return $this->orcamento;
    }

    public function getComposicao() {
        return $this->composicao;
    }

    public function getQuantidade() {
        return $this->quantidade;
    }

    public function getAtiva() {
        return $this->ativa;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setOrcamento($orcamento) {
        $this->orcamento = $orcamento;
    }

    public function setComposicao($composicao) {
        $this->composicao = $composicao;
    }

    public function setQuantidade($quantidade) {
        $this->quantidade = $quantidade;
    }

    public function setAtiva($ativa) {
        $this->ativa = $ativa;
    }

}

?>