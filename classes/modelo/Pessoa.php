<?

class Pessoa {

    private $id;
    private $codigo;
    private $nome;
    private $dataNascimento;
    private $sexo;
    private $estadoCivil;
    private $cpf;
    private $rg;
    private $pai;
    private $mae;
    private $endereco;
    private $numero;
    private $bairro;
    private $cidade;
    private $estado;
    private $cep;
    private $telefone;
    private $celular;
    private $email;
    private $grauEscolar;
    private $banco;
    private $usuarioCadastro;
    private $dataCadastro;
    private $usuarioAtualizacao;
    private $dataAtualizacao;

    public function __construct() {
        ;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    public function setDataNascimento($dataNascimento) {
        $this->dataNascimento = $dataNascimento;
    }

    public function getSexo() {
        return $this->sexo;
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    public function getEstadoCivil() {
        return $this->estadoCivil;
    }

    public function setEstadoCivil($estadoCivil) {
        $this->estadoCivil = $estadoCivil;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    public function getRg() {
        return $this->rg;
    }

    public function setRg($rg) {
        $this->rg = $rg;
    }

    public function getPai() {
        return $this->pai;
    }

    public function setPai($pai) {
        $this->pai = $pai;
    }

    public function getMae() {
        return $this->mae;
    }

    public function setMae($mae) {
        $this->mae = $mae;
    }

    public function getEndereco() {
        return $this->endereco;
    }

    public function setEndereco($endereco) {
        $this->endereco = $endereco;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    }

    public function getBairro() {
        return $this->bairro;
    }

    public function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    public function getCidade() {
        return $this->cidade;
    }

    public function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getCep() {
        return $this->cep;
    }

    public function setCep($cep) {
        $this->cep = $cep;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    public function getCelular() {
        return $this->celular;
    }

    public function setCelular($celular) {
        $this->celular = $celular;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getGrauEscolar() {
        return $this->grauEscolar;
    }

    public function setGrauEscolar($grauEscolar) {
        $this->grauEscolar = $grauEscolar;
    }

    public function getBanco() {
        return $this->banco;
    }

    public function setBanco($banco) {
        $this->banco = $banco;
    }

    public function getUsuarioCadastro() {
        return $this->usuarioCadastro;
    }

    public function setUsuarioCadastro($usuarioCadastro) {
        $this->usuarioCadastro = $usuarioCadastro;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    public function getUsuarioAtualizacao() {
        return $this->usuarioAtualizacao;
    }

    public function setUsuarioAtualizacao($usuarioAtualizacao) {
        $this->usuarioAtualizacao = $usuarioAtualizacao;
    }

    public function getDataAtualizacao() {
        return $this->dataAtualizacao;
    }

    public function setDataAtualizacao($dataAtualizacao) {
        $this->dataAtualizacao = $dataAtualizacao;
    }

}

?>
