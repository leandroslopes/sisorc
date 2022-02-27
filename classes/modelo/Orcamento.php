<?

class Orcamento {

    private $id;
    private $nomeObra;
    private $nomeCliente;
    private $local;
    private $area;
    private $bdi;
    private $encargoSocial;
    private $funcionario;
    private $dataCadastro;
    private $dataAtualizacao;

    public function Orcamento() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getNomeObra() {
        return $this->nomeObra;
    }

    public function getNomeCliente() {
        return $this->nomeCliente;
    }

    public function getLocal() {
        return $this->local;
    }

    public function getArea() {
        return $this->area;
    }

    public function getBdi() {
        return $this->bdi;
    }

    public function getEncargoSocial() {
        return $this->encargoSocial;
    }

    public function getFuncionario() {
        return $this->funcionario;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function getDataAtualizacao() {
        return $this->dataAtualizacao;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNomeObra($nomeObra) {
        $this->nomeObra = $nomeObra;
    }

    public function setNomeCliente($nomeCliente) {
        $this->nomeCliente = $nomeCliente;
    }

    public function setLocal($local) {
        $this->local = $local;
    }

    public function setArea($area) {
        $this->area = $area;
    }

    public function setBdi($bdi) {
        $this->bdi = $bdi;
    }

    public function setEncargoSocial($encargoSocial) {
        $this->encargoSocial = $encargoSocial;
    }

    public function setFuncionario($funcionario) {
        $this->funcionario = $funcionario;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    public function setDataAtualizacao($dataAtualizacao) {
        $this->dataAtualizacao = $dataAtualizacao;
    }

}

?>