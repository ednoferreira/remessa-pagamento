<?php
/**
 * Funções que auxiliam na geração da remessa de pagamento
 */

/**
 * Recebe uma string e retorna a mesma, mas 
 * que pode ou não ser preenchida com zeros ou vazio
 * @param $valor <string a ser processada e retornada>
 * @param $tamanho <tamanho da string a ser retornada>
 * @param $complemento <caso a string não esteja no tamanho correto, este caractere será o preenchimento dela
 * @param $direcao <onde inserir o preenchimento ["direita" ou "esquerta"] do valor)
 * @author Edno
 */
function setValor($valor, $tamanho, $complemento = ' ', $direcao = 'direita') {

    $str = '';
    // se veio menor que a string necessária, preenchemos com o complemento:
    if(strlen($valor) < $tamanho){
        if($direcao == 'esquerda'){
            $valor = str_pad($valor, $tamanho, $complemento, STR_PAD_LEFT);
        }else{ // direita como padrão:
            $valor = str_pad($valor, $tamanho, $complemento);
        }
    }
    // Se veio maior, garantimos que volte menor:
    $str = substr($valor, 0, $tamanho);
    //
    return $str;
}

function removerAcentos($string){
    $string = str_replace('ç', 'c', $string);
    $string = str_replace('Ç', 'C', $string);
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"), $string);
}

/**
 * Recebemos um valor float, ex: 856,50
 * e retornamos uma sequencia determinada, ex: 00085650
 * De acordo com o PDF do Itaú:
 * "Vírgula assumida (picture V): indica a posição da vírgula dentro de um campo numérico.
 *  Exemplo: num campo com picture “9(5)V9(2)”, o número “876,54” será representado por “0087654” "
 */
function setDecimal($valor, $tamanho){
    // removemos os pontos e vírgulas do valor
    $valor = str_replace(',', '', $valor);
    $valor = str_replace('.', '', $valor);
    // queremos o valor com centavos, ex 1 = 100, sendo os dois zeros as duas casas decimais
    $valor = floor($valor * 100);
    // retornamos completo com os zeros de acordo com o tamanho deteminado:
    return setValor($valor, $tamanho, '0', 'esquerda');
}

/**
 * Um dump mais rápido:
 */
function dd($item) {

    if (is_array($item)) {
        echo '<pre>'.print_r($item, 1).'</pre>';
    }else{
        echo $item;
    }

    exit;
}

/**
 * Montar a linha de convênio 
 * segmentos: header de arquivo, header de lote
 */
function montarConvenio($dados, $segmento = 'header de arquivo') {

    $linha = '';
    switch($dados['banco']['cod_banco']) {

        // Itau
        case '341':
            switch($segmento) {
                case 'header de lote':
                    $linha .= setValor($dados['identificacao_lancamento'], 4);
                break;
                default: $linha .= setValor('', 20);
            }
        break;

        // BB
        case '001':
            $linha .= setValor($dados['convenio_numero'], 9, '0', 'esquerda');
            $linha .= setValor($dados['banco']['convenio_codigo'], 4, '0', 'esquerda');
            $linha .= setValor('', 5);
            $linha .= setValor( empty($dados['arquivo_teste']) ? '' : 'TS', 2);
        break;
    }
    return $linha;
}