function mascaraHellas(valor, id, mascara, evento)
{
    /*
     Diego Hellas
     diegohellas@comunidadephp.com.br
     
     Vers�o 3.0
     Data: 04/05/2007
     Novidades da vers�o: Implementa��o de m�scara para valores monet�rios no padr�o brasileio. 
     TAB autom�tico quando a m�scara n�o for valor monet�rio
     
     Esse script foi desenvolvido com a finalidade de suprir qualquer necessidade
     com m�scaras em formul�rios HTML.
     Para funcionar corretamente o campo tem que ter obrigat�riamente a propriedade
     id no campo input <input name="teste" id="teste" type="text" />, o script so foi
     testado com o evento onkeyup, n�o sei do comportamento do script com outros eventos
     Uma outra caracteristica dele(desde que n�o seja valores monet�rios) � a verifica��o do tipo de dado que entra por posi��o
     na m�scara comforme a tabelinha a seguir:
     ***************************
     *       n�meros      *  # *
     ***************************
     * qualuqer caratcter *  @ *
     ***************************
     */


    /////////////////////////////////////////////////////////////////////////////////
    //                             MODELO  DE M�SCARA                              //
    //onkeyup="mascaraHellas(this.value, this.id, '####.##.##.#######-#/#', event)"//
    /////////////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////////////////////
    //                         MODELO  DE M�SCARA MONET�RIA                        //
    //onkeyup="mascaraHellas(this.value, this.id, 'money', event)"                 //
    /////////////////////////////////////////////////////////////////////////////////


    //inicializa a variavel que vai conter o valor final
    var valorFinal = "";

    //verifica o que foi digitada para que seja verificado se � somente n�meros ou n�o
    var tecla = evento.keyCode;

    //manetem o tamanho original do campo sem retirar a m�scara
    var valorOriginal = valor;

    //inicializa um array com todos os caracteres que ser�o retirado
    var arrNaoPermitidos = new Array("-", ".", "/", "\\", "|", "(", ")", ":", " ", ",");

    //retira qualquer m�scatra que j� tenho sido colocada
    for (i1 = 0; i1 < valor.length; i1++)
    {
        for (i2 = 0; i2 < arrNaoPermitidos.length; i2++)
        {
            if (valor.charAt(i1) == arrNaoPermitidos[i2])
            {
                valor = valor.toString().replace(arrNaoPermitidos[i2], "");
            }
        }
    }

    //merifica se a m�scara � momet�ria ou uma m�scara fixa
    if (mascara == "money")
    {
        //retira os zeros da frente do valor
        var temZero = true;
        while (temZero == true)
        {
            if (valor.substring(0, 1) == "0")
            {
                valor = valor.substring(1, valor.length);
            }
            else
            {
                temZero = false;
            }
        }

        //verifica se foi precionado o backspae
        if (tecla == 8)
        {
            valor = valor.substring(0, valor.length);
        }

        //faz as verifica��es para a formata��o do valor
        if (valor.length == 1)
        {
            valorFinal = "0,0" + valor;
        }
        else if (valor.length == 2)
        {
            valorFinal = "0," + valor;
        }
        else if (valor.length == 4 && valor.substr(0, 2) == "00")
        {
            valorFinal = "0," + valor.substr(2);
        }
        else if (valor.length == 4 && valor.substr(0, 1) == "0" && valor.substr(0, 2) != "00")
        {
            valorFinal = valor.substr(1, 1) + "," + valor.substr(2);
        }
        else if (valor.length >= 4 || valor.length == 3)
        {
            var parteInteira = valor.substr(0, (valor.length - 2));
            var parteDecimal = valor.substr((valor.length - 2))
            var inteiro = "";
            var cont = 1;
            var milhar = "";

            for (i = parteInteira.length; i > 0; i--)
            {
                if (cont == 4)
                {
                    milhar = ".";
                    cont = 2;
                }
                else
                {
                    milhar = "";
                    cont++;
                }

                inteiro = parteInteira.substr((i - 1), 1) + milhar + inteiro;
            }

            valorFinal = inteiro + "," + parteDecimal;
        }
        else
        {
            valorFinal = "0,00";
        }
    }
    else
    {
        //verifica se foi precionado o backspae
        if (tecla != 8)
        {
            //verifica se j� n�o ultrapassou o tamanha m�ximo da m�scara
            if (mascara.length >= valorOriginal.length)
            {
                //loop em cima do valor do campo sem a m�scara
                jaTemMascara = false;
                for (i = 0; i < valor.length; i++)
                {
                    //verifica se a string j� recebeu alguma m�scara ou n�o
                    if (jaTemMascara == false)
                    {
                        //verifica se o tipo da entrada de dados tem que ser n�merica
                        if (mascara.charAt(i) == "#")
                        {
                            //verifica se foi digitado somente n�meros
                            if (((tecla > 95) && (tecla < 106)) || ((tecla > 47) && (tecla < 58)) || tecla == 9 || tecla == 16)
                            {
                                //0 = 96 ou 48
                                //1 = 97 ou 49
                                //2 = 98 ou 50
                                //3 = 99 ou 51
                                //4 = 100 ou 52
                                //5 = 101 ou 53
                                //6 = 102 ou 54
                                //7 = 103 ou 55
                                //8 = 104 ou 56
                                //9 = 105 ou 57
                                //tecla == 9 = tab
                                valorFinal = valorFinal + valor.charAt(i);
                            }
                            else//se n�o foi digitado um n�mero � retirado o caracter da string
                            {
                                valorFinal = valorOriginal.substring(0, valorOriginal.length - 1);
                            }
                        }
                        else if (mascara.charAt(i) == "@")//verifica se o tipo da entrada � qualquer caracter
                        {
                            valorFinal = valorFinal + valor.charAt(i);
                        }
                        else//se n�o for quelaquer caracter � algum elemento da m�scara
                        {
                            //verifica se o pr�xima depois da m�scara � n�merica 
                            if (mascara.charAt(i + 1) == "#")
                            {
                                //verifica se foi digitado somente n�meros
                                if (((tecla > 95) && (tecla < 106)) || ((tecla > 47) && (tecla < 58)) || tecla == 9 || tecla == 16)
                                {
                                    //0 = 96 ou 48
                                    //1 = 97 ou 49
                                    //2 = 98 ou 50
                                    //3 = 99 ou 51
                                    //4 = 100 ou 52
                                    //5 = 101 ou 53
                                    //6 = 102 ou 54
                                    //7 = 103 ou 55
                                    //8 = 104 ou 56
                                    //9 = 105 ou 57
                                    //tecla == 9 = tab
                                    valorFinal = valorFinal + mascara.charAt(i + jaTemMascara) + valor.charAt(i);
                                    jaTemMascara = jaTemMascara + 1;
                                }
                                else//se n�o foi digitado um n�mero � retirado o caracter da string
                                {
                                    valorFinal = valorOriginal.substring(0, valorOriginal.length - 1);
                                }
                            }
                            else// se n�o � n�merico ent�o pode ser qualuqer caracter
                            {
                                valorFinal = valorFinal + mascara.charAt(i + jaTemMascara) + valor.charAt(i);
                                jaTemMascara = jaTemMascara + 1;
                            }
                        }
                    }
                    else//else da verifica��o da m�scara
                    {
                        //verifica se foi digitado somente n�meros
                        if (mascara.charAt(i + jaTemMascara) == "#")
                        {
                            //verifica se foi digitado somente n�meros
                            if (((tecla > 95) && (tecla < 106)) || ((tecla > 47) && (tecla < 58)) || tecla == 9 || tecla == 16)
                            {
                                //0 = 96 ou 48
                                //1 = 97 ou 49
                                //2 = 98 ou 50
                                //3 = 99 ou 51
                                //4 = 100 ou 52
                                //5 = 101 ou 53
                                //6 = 102 ou 54
                                //7 = 103 ou 55
                                //8 = 104 ou 56
                                //9 = 105 ou 57
                                //tecla == 9 = tab
                                valorFinal = valorFinal + valor.charAt(i);
                            }
                            else//se n�o foi digitado um n�mero � retirado o caracter da string
                            {
                                valorFinal = valorOriginal.substring(0, valorOriginal.length - 1);
                            }
                        }
                        else if (mascara.charAt(i + jaTemMascara) == "@")//verifica se o tipo da entrada � qualquer caracter
                        {
                            valorFinal = valorFinal + valor.charAt(i);
                        }
                        else
                        {
                            //verifica se foi digitado somente n�meros
                            if (mascara.charAt(i + jaTemMascara + 1) == "#")
                            {
                                //verifica se foi digitado somente n�meros
                                if (((tecla > 95) && (tecla < 106)) || ((tecla > 47) && (tecla < 58)) || tecla == 9 || tecla == 16)
                                {
                                    //0 = 96 ou 48
                                    //1 = 97 ou 49
                                    //2 = 98 ou 50
                                    //3 = 99 ou 51
                                    //4 = 100 ou 52
                                    //5 = 101 ou 53
                                    //6 = 102 ou 54
                                    //7 = 103 ou 55
                                    //8 = 104 ou 56
                                    //9 = 105 ou 57
                                    //tecla == 9 = tab
                                    valorFinal = valorFinal + mascara.charAt(i + jaTemMascara) + valor.charAt(i);
                                    jaTemMascara = jaTemMascara + 1;
                                }
                                else//se n�o foi digitado um n�mero � retirado o caracter da string
                                {
                                    valorFinal = valorOriginal.substring(0, valorOriginal.length - 1);
                                }
                            }
                            else// se n�o � n�merico ent�o pode ser qualuqer caracter
                            {
                                valorFinal = valorFinal + mascara.charAt(i + jaTemMascara) + valor.charAt(i);
                                jaTemMascara = jaTemMascara + 1;
                            }
                        }
                    }//fim da verifica��o da m�scara	
                }
            }
            else
            {
                valorFinal = valorOriginal.substring(0, mascara.length);
            }//final da verifica��o do tamanha m�ximo da string
        }
        else
        {
            //valorFinal = valorOriginal.substring(0, valorOriginal.length -1)
            valorFinal = valorOriginal.substring(0, valorOriginal.length);
        }//final da verifica��o do backspace		
    }
    document.getElementById(id).value = valorFinal;

    //faz as verifica��es para que seja feito o tab autom�tico
    if (mascara != "money")
    {

    }
}