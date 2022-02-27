<?

class Cronograma {

    private $id;

    /**
     *
     * @var Orcamento
     */
    private $orcamento;

    /**
     *
     * @var CronogramaCabecalho
     */
    private $cabecalho;
    private $quantidadeCabecalho;
    private $dataCadastro;

    public function Cronograma() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getOrcamento() {
        return $this->orcamento;
    }

    public function getCabecalho() {
        return $this->cabecalho;
    }

    public function getQuantidadeCabecalho() {
        return $this->quantidadeCabecalho;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setOrcamento(Orcamento $orcamento) {
        $this->orcamento = $orcamento;
    }

    public function setCabecalho(CronogramaCabecalho $cabecalho) {
        $this->cabecalho = $cabecalho;
    }

    public function setQuantidadeCabecalho($quantidadeCabecalho) {
        $this->quantidadeCabecalho = $quantidadeCabecalho;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

}
?>

