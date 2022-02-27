<?

class DataUtil {

    const DATA_USA = 1;
    const DATA_BRA = 2;

    /**
     * Metodo construtor
     */
    public function DataUtil() {
        
    }

    /**
     * Retorna o nome do dia.
     * @param inteiro $diaSemana
     * @return string
     */
    public static function getNomeDia($diaSemana) {
        $nomeDia = "";

        switch ($diaSemana) {
            case 1:
                $nomeDia = "Segunda";
                break;
            case 2:
                $nomeDia = "Terça";
                break;
            case 3:
                $nomeDia = "Quarta";
                break;
            case 4:
                $nomeDia = "Quinta";
                break;
            case 5:
                $nomeDia = "Sexta";
                break;
            case 6:
                $nomeDia = "Sábado";
                break;
            case 0:
                $nomeDia = "Domingo";
                break;
        }

        return $nomeDia;
    }

    /**
     * Retorna o nome do mes.
     * @param inteiro $mes
     * @return string
     */
    public static function getNomeMes($mes) {
        $nomeMes = "";

        switch ($mes) {
            case 1:
                $nomeMes = "Janeiro";
                break;
            case 2:
                $nomeMes = "Fevereiro";
                break;
            case 3:
                $nomeMes = "Março";
                break;
            case 4:
                $nomeMes = "Abril";
                break;
            case 5:
                $nomeMes = "Maio";
                break;
            case 6:
                $nomeMes = "Junho";
                break;
            case 7:
                $nomeMes = "Julho";
                break;
            case 8:
                $nomeMes = "Agosto";
                break;
            case 9:
                $nomeMes = "Setembro";
                break;
            case 10:
                $nomeMes = "Outubro";
                break;
            case 11:
                $nomeMes = "Novembro";
                break;
            case 12:
                $nomeMes = "Dezembro";
                break;
        }

        return $nomeMes;
    }

    /**
     * Imprimir a data atual.
     * @return string
     */
    public static function imprimirDataAtual() {
        return self::getNomeDia(date("w")) . ", " . date("d") . " de " . self::getNomeMes(date("n")) . " de " . date("Y");
    }

    /**
     * Formata uma data para um padra escolhido.
     * @param string $stringData
     * @param inteiro $formato
     * @return string
     */
    public static function formatar($stringData, $formato) {
        if ($formato == self::DATA_USA) {
            $dtFormatada = substr($stringData, 6, 4) . "-" . substr($stringData, 3, 2) . "-" . substr($stringData, 0, 2);
        } else {
            $dtFormatada = substr($stringData, 8, 2) . "-" . substr($stringData, 5, 2) . "-" . substr($stringData, 0, 4);
        }
        return $dtFormatada;
    }

    /**
     * Retorna a data de hoje.
     * @return string
     */
    public static function getHoje() {
        return date("d/m/Y");
    }
}

?>
