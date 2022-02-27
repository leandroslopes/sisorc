<?

class OrcComposicaoSubcomposicao {

    private $id;
    private $orcComposicao;
    private $orcSubcomp;

    public function OrcComposicaoSubcomposicao() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function getOrcComposicao() {
        return $this->orcComposicao;
    }

    public function getOrcSubcomp() {
        return $this->orcSubcomp;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setOrcComposicao($orcComposicao) {
        $this->orcComposicao = $orcComposicao;
    }

    public function setOrcSubcomp($orcSubcomp) {
        $this->orcSubcomp = $orcSubcomp;
    }

}

?>