<?

class SubmoduloAcesso {

    private $id;
    private $submodulo;
    private $cargo;

    public function SubmoduloAcesso() {
        
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getSubmodulo() {
        return $this->submodulo;
    }

    public function setSubmodulo($submodulo) {
        $this->submodulo = $submodulo;
    }

    public function getCargo() {
        return $this->cargo;
    }

    public function setCargo($cargo) {
        $this->cargo = $cargo;
    }

}

?>
