<?

class NumeroUtil {

    const NUMERO_USA = 1;
    const NUMERO_BRA = 2;

    /**
     * Metodo construtor.
     */
    public function NumeroUtil() {
        
    }

    /**
     * Formata um numero decimal para um padrao escolhido.
     * @param string $numero
     * @param inteiro $formato
     * @return string
     */
    public static function formatar($numero, $formato) {
        $num_formatado = "";
        if (!empty($numero)) {
            if ($formato == self::NUMERO_USA) {
                $source = array('.', ',');
                $replace = array('', '.');
                $num_formatado = str_replace($source, $replace, $numero);
            } else {
                $num_formatado = number_format($numero, 2, ',', '.');
            }
        }
        return $num_formatado;
    }

    /**
     * Multiplica dois numeros.
     * @param decimal $valor1
     * @param decimal $valor2
     * @return string
     */
    public static function multiplicar($valor1, $valor2) {
        $total = 0;
        if (!empty($valor1) && !empty($valor1)) {
            $total = $valor1 * $valor2;
            return $total;
        } else {
            return "0.00";
        }
    }

    /**
     * Calcula a porcentagem.
     * @param decimal $valor
     * @param decimal $total
     * @return decimal
     */
    public static function porcentagem($valor, $total) {
        return self::formatar((($valor / $total) * 100), NumeroUtil::NUMERO_BRA);
    }

    /**
     * Coloca zeros a esquerda de um numero.
     * @param inteiro $numero
     * @param inteiro $zeros
     * @return string
     */
    public static function setZeroEsquerda($numero, $zeros) {
        return str_pad($numero, $zeros, 0, STR_PAD_LEFT);
    }
}

?>
