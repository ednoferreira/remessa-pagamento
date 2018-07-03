<?php 

/**
 * Gerar o header de Lote
 */
class HeaderLote {

    /**
     * Gerar o Header de Arquivo
     * legenda de conteúdo: X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
     */
    public static function gerar($dados) {
        $linha = '';
        // NOME DO CAMPO               | SIGNIFICADO                                          |  POSIÇÃO  | PICTURE | CONTEÚDO
        //============================================================================================
        // CÓDIGO DO BANCO             | CÓDIGO BANCO NA COMPENSAÇÃO                          | 001 003   | 9(03)   | 341
        $linha .= setValor($dados['cod_banco'], 3);
        // CÓDIGO DO LOTE              | LOTE IDENTIFICAÇÃO DE PAGTOS                         | 004 007   | 9(04)   | NOTA 3
        $linha .= setValor($dados['header_cod_lote'], 4);
        // TIPO DE REGISTRO            | REGISTRO HEADER DE LOTE                              | 008 008   | 9(01)   | 1
        $linha .= setValor($dados['tipo_registro'], 1);
        // (1) TIPO DE OPERAÇÃO        | TIPO DA OPERAÇÃO                                     | 009 009   | X(01)   | C=CRÉDITO
        $linha .= setValor($dados['tipo_operacao'], 1);
        // (3) TIPO DE PAGAMENTO       | TIPO DE PAGTO                                        | 010 011   | 9(02)   | NOTA 4
        $linha .= setValor($dados['tipo_pagamento'], 2);
        // (3) FORMA DE PAGAMENTO      | FORMA DE PAGAMENTO                                   | 012 013   | 9(02)   | NOTA 5
        $linha .= setValor($dados['forma_pagamento'], 2);
        // LAYOUT DO LOTE              | N DA VERSÃO DO LAYOUT DO LOTE                        | 014 016   | 9(03)   | 040
        $linha .= setValor($dados['layout_lote'], 3);
        // BRANCOS                     | COMPLEMENTO DE REGISTRO                              | 017 017   | X(01)   |
        $linha .= setValor('', 1);
        // EMPRESA – INSCRIÇÃO         | TIPO INSCRIÇÃO EMPRESA DEBITADA                      | 018 018   | 9(01)   | 1 = CPF 2 = CNPJ
        $linha .= setValor($dados['empresa_inscricao_tipo'], 1);
        // INSCRIÇÃO NÚMERO            | CNPJ EMPRESA DEBITADA                                | 019 032   | 9(14)   | NOTA 1
        $linha .= setValor($dados['empresa_inscricao_numero'], 14);
        // IDENTIFICAÇÃO DO LANÇAMENTO | IDENTIFICAÇÃO DO LANÇAMENTO NO EXTRATO DO FAVORECIDO | 033 036   | X(04)   | NOTA 13
        $linha .= setValor($dados['identificacao_lancamento'], 4);
        // BRANCOS                     | COMPLEMENTO DE REGISTRO                              | 037 052   | X(16)   |
        $linha .= setValor('', 16);
        // AGÊNCIA                     | NÚMERO AGÊNCIA DEBITADA                              | 053 057   | 9(05)   | NOTA 1
        $linha .= setValor($dados['agencia'], 5);
        // BRANCOS                     | COMPLEMENTO DE REGISTRO                              | 058 058   | X(01)   |
        $linha .= setValor('', 1);
        // CONTA                       | NÚMERO DE C/C DEBITADA                               | 059 070   | 9(12)   | NOTA 1
        $linha .= setValor($dados['conta'], 12, '0', 'esquerda');
        // BRANCOS                     | COMPLEMENTO DE REGISTRO                              | 071 071   | X(01)   | 
        $linha .= setValor('', 1);
        // DAC                         | DAC DA AGÊNCIA/CONTA DEBITADA                        | 072 072   | 9(01)   | NOTA 1
        $linha .= setValor($dados['dac'], 1);
        // NOME DA EMPRESA             | NOME DA EMPRESA DEBITADA                             | 073 102   | X(30)   | 
        $linha .= setValor($dados['empresa_nome'], 30);
        //(2) FINALIDADE DO LOTE       | FINALIDADE DOS PAGTOS DO LOTE                        | 103 132   | X(30)   | NOTA 6
        $linha .= setValor($dados['finalidade_lote'], 30);
        // HISTÓRICO DE C/C            | COMPLEMENTO HISTÓRICO C/C DEBITADA                   | 133 142   | X(10)   | NOTA 7
        $linha .= setValor($dados['historico_cc'], 10);
        // ENDEREÇO DA EMPRESA         | NOME DA RUA, AV, PÇA, ETC...                         | 143 172   | X(30)   | 
        $linha .= setValor($dados['empresa_endereco'], 30);
        // NÚMERO                      | NÚMERO DO LOCAL                                      | 173 177   | 9(05)   | 
        $linha .= setValor($dados['empresa_endereco_numero'], 5);
        // COMPLEMENTO.                | CASA, APTO, SALA, ETC...                             | 178 192   | X(15)   | 
        $linha .= setValor($dados['empresa_endereco_complemento'], 15);
        // CIDADE                      | NOME DA CIDADE                                       | 193 212   | X(20)   | 
        $linha .= setValor($dados['empresa_cidade'], 20);
        // CEP                         | CEP                                                  | 213 220   | 9(08)   |
        $linha .= setValor($dados['empresa_cep'], 8);
        // ESTADO                      | SIGLA DO ESTADO                                      | 221 222   | X(02)   | 
        $linha .= setValor($dados['empresa_uf'], 2);
        // BRANCOS                     | COMPLEMENTO DE REGISTRO                              | 223 230   | X(08)   | 
        $linha .= setValor('', 8);
        // (*) OCORRÊNCIAS             | CÓDIGO OCORRÊNCIAS P/RETORNO                         | 231 240   | X(10)   | NOTA 8
        $linha .= setValor($dados['ocorrencias'], 10);

        return $linha;
        
    }
}