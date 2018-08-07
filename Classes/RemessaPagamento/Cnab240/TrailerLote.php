<?php 

/**
 * Gerar o trailer de Lote (rodapé do arquivo)
 */
class TrailerLote {

    /**
     * Gerar o Header de Arquivo
     * legenda de conteúdo: X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
     */
    public static function gerar($dados) {
        $linha = '';
        // NOME DO CAMPO               | SIGNIFICADO                                          |  POSIÇÃO  | PICTURE    | CONTEÚDO
        //============================================================================================
        // CÓDIGO DO BANCO             | CÓDIGO BANCO NA COMPENSAÇÃO                          | 001 003   | 9(03)      | 341
        $linha .= setValor($dados['banco']['cod_banco'], 3, '0', 'esquerda');
        // CÓDIGO DO LOTE              | LOTE IDENTIFICAÇÃO DE PAGTOS                         | 004 007   | 9(04)      | NOTA 3
        $linha .= setValor($dados['trailer_lote_cod_lote'], 4, '0', 'esquerda');
        // TIPO DE REGISTRO            | REGISTRO TRAILER DE LOTE                             | 008 008   | 9(01)      | 5
        $linha .= setValor($dados['trailer_lote_tipo_registro'], 1);
        // BRANCOS                     | COMPLEMENTO DE REGISTRO                              | 009 017   | X(09)      | 
        $linha .= setValor('', 9);
        // TOTAL QTDE REGISTROS        | QTDE REGISTROS DO LOTE                               | 018 023   | 9(06)      | NOTA 17
        $linha .= setValor($dados['qtd_registros'], 6, '0', 'esquerda');
        // (1) TOTAL VALOR PAGTOS      | SOMA VALOR DOS PGTOS DO LOTE                         | 024 041   | 9(16)V9(2) | NOTA 17
        $linha .= setDecimal($dados['valor_total_pagamentos'], 18, '0', 'esquerda');
        // ZEROS                       | COMPLEMENTO DE REGISTRO                              | 042 059   | 9(18)      |
        $linha .= setValor('', 18, '0', 'esquerda');
        // BRANCOS                     | COMPLEMENTO DE REGISTRO                              | 060 230   | X(171)     |
        $linha .= setValor('', 171);
        //(*) OCORRÊNCIAS              | CÓDIGOS OCORRÊNCIAS P/ RETORNO                       | 231 240   | X(10)      | NOTA 8
        $linha .= setValor('', 10);
        
        return $linha;
        
    }
}