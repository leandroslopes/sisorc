/**
 * Funçoes de validação de formulários.
 */


/**
 * Valida formulário de pesquisar cadastro.
 */
function validaFrmPesquisar() {
    with (document.frmPesquisar) {

        btPesquisar.disabled = true;
        btPesquisar.value = "Pesquisar ...";

        /**
         * Validar cógido e nome
         */
        if (codigo.value == "" && nome.value == "") {
            var msgErro = document.getElementById("msgErroForms");
            var resultado = "&bull; Preencha um campo";
            resultado = resultado.replace(/\+/g, " ");
            resultado = unescape(resultado);
            msgErro.setAttribute("style", "display: block");
            msgErro.innerHTML = resultado;
            btPesquisar.disabled = false;
            btPesquisar.value = "Pesquisar";
            return false;
        }
        var msgErro = document.getElementById("msgErroForms");
        msgErro.innerHTML = "";
    }
}