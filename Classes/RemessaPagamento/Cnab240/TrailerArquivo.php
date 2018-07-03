<?php 

/**
 * Gerar o trailer de Lote (rodapé do arquivo)
 */
class TrailerArquivo {

    /**
     * Gerar o Header de Arquivo
     * legenda de conteúdo: X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
     */
    public static function gerar($dados) {
        $linha = '';
        // NOME DO CAMPO               | SIGNIFICADO                                          |  POSIÇÃO  | PICTURE    | CONTEÚDO
        //============================================================================================
        // CÓDIGO DO BANCO             | CÓDIGO BANCO NA COMPENSAÇÃO                          | 001 003   | 9(03)      | 341
        $linha .= setValor($dados['cod_banco'], 3);
        // CÓDIGO DO LOTE              | LOTE DE SERVICO                                      | 004 007   | 9(04)      | 9999
        $linha .= setValor($dados['trailer_arq_cod_lote'], 4, '0', 'esquerda');
        // TIPO DE REGISTRO            | REGISTRO TRAILER DE LOTE                             | 008 008   | 9(01)      | 5
        $linha .= setValor($dados['trailer_arq_tipo_registro'], 1);
        // BRANCOS                     | COMPLEMENTO DE REGISTRO                              | 009 017   | X(09)      | 
        $linha .= setValor('', 9);
        // TOTAL QTDE DE LOTES         | QTDE LOTES DO ARQUIVO                                | 018 023   | 9(06)       | NOTA 17
        $linha .= setValor($dados['qtd_lotes'], 6, '0', 'esquerda');
        // TOTAL QTDE REGISTROS QTDE REGISTRO S DO ARQU IVO                                   | 024 029   | 9(6)        | NOTA 17
        $linha .= setDecimal($dados['qtd_registros'], 6, '0', 'esquerda');
        // BRANCOS                     | COMPLEMENTO DE REGISTRO                              | 030 240   | X(211)     |
        $linha .= setValor('', 211);
        
        return $linha;
        
    }
}