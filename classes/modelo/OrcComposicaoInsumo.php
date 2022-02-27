<?

class OrcComposicaoInsumo {

    private $id;
    /**
     *
     * @var OrcComposicao 
     */
    private $orcComposicao;
    /**
     *
     * @var Insumo 
     */
    private $insumo;
    private $quantidade;
    private $preco;
    private $dataCadastro;

    public function OrcComposicaoInsumo() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getOrcComposicao() {
        return $this->orcComposicao;
    }

    public function getInsumo() {
        return $this->insumo;
    }

    public function getQuantidade() {
        return $this->quantidade;
    }

    public function getPreco() {
        return $this->preco;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setOrcComposicao($orcComposicao) {
        $this->orcComposicao = $orcComposicao;
    }

    public function setInsumo($insumo) {
        $this->insumo = $insumo;
    }

    public function setQuantidade($quantidade) {
        $this->quantidade = $quantidade;
    }

    public function setPreco($preco) {
        $this->preco = $preco;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

}

?>