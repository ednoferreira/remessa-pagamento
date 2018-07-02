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
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"), $string);
}