<?php 

/**
 * Detalhe - segmento A
 * Pagamentos através de cheque, OP, DOC, TED e credito em conta corrente 
 */
class DetalheSegmentoA {

    /**
     * Gerar o Header de Arquivo
     * legenda de conteudo: X = ALFANUMERICO 9 = NUMERICO V = VIRGULA DECIMAL ASSUMIDA
     */
    public static function gerar($detalhe) {
        $linha = '';
        // NOME DO CAMPO               | SIGNIFICADO                                          |  POSICAO  | PICTURE     | CONTEUDO
        //============================================================================================
        // CÓDIGO DO BANCO             | CÓDIGO BANCO NA COMPENSAÇÃO                          | 001 003   | 9(03)       | 341
        $linha .= setValor($detalhe['cod_banco'], 3);
        // CÓDIGO DO LOTE              | LOTE DE SERVIÇO                                      | 004 007   | 9(04)       | NOTA 3
        $linha .= setValor($detalhe['detalhe_cod_lote'], 4, '0', 'esquerda');
        // TIPO DE REGISTRO            | REGISTRO DETALHE DE LOTE                             | 008 008   | 9(01)       | 3
        $linha .= setValor($detalhe['detalhe_tipo_registro'], 1);
        // NÚMERO DO REGISTRO          | N° SEQUENCIAL REGISTRO NO LOTE                       | 009 013   | 9(05)       | NOTA 9
        $linha .= setValor($detalhe['detalhe_numero_registro'], 5, '0', 'esquerda');
        // SEGMENTO CÓDIGO             | SEGMENTO REG. DETALHE                                | 014 014   | X(01)       | A
        $linha .= setValor($detalhe['segmento_codigo'], 1);
        // TIPO DE MOVIMENTO           | TIPO DE MOVIMENTO                                    | 015 017   | 9(03)       | NOTA 10
        $linha .= setValor($detalhe['detalhe_tipo_movimento'], 3);
        // CÃMARA                      | CÓDIGO DA CÃMARA CENTRALIZADORA                      | 018 020   | 9(03)       | NOTA 37
        $linha .= setValor($detalhe['detalhe_camara'], 3);
        // BANCO FAVORECIDO            | CÓDIGO BANCO FAVORECIDO                              | 021 023   | 9(03)       | 
        $linha .= setValor($detalhe['detalhe_favorecido_banco'], 3);
        // AGÊNCIA CONTA               | AGÊNCIA CONTA FAVORECIDO                             | 024 043   | X(20)       | NOTA 11
        $linha .= setValor($detalhe['detalhe_favorecido_agencia_conta'], 20);
        // NOME DO FAVORECIDO          | NOME DO FAVORECIDO                                   | 044 073   | X(30)       | NOTA 35
        $linha .= setValor($detalhe['detalhe_favorecido_nome'], 30, ' ');
        // SEU NÚMERO                  | N° DOCTO ATRIBUÍDO PELA EMPRESA                      | 074 093   | X(20)       | 
        $linha .= setValor($detalhe['detalhe_seu_numero'], 20);
        // (1) DATA DE PAGTO           | DATA PREVISTA PARA PAGTO                             | 094 101   | 9(08)       | DDMMAAAA
        $linha .= setValor($detalhe['detalhe_data_pagamento'], 8);
        // MOEDA TIPO                | TIPO DA MOEDA                                          | 102 104   | X(03)       | REA OU 009
        $linha .= setValor($detalhe['detalhe_moeda'], 3);
        // CÓDIGO ISPB                 | IDENTIFICAÇÃO DA INSTITUIÇÃO PARA O SPB              | 105 112   | 9(08)       | NOTA 37
        $linha .= setValor($detalhe['detalhe_cod_ispb'], 8, '0', 'esquerda');
        // ZEROS                       | COMPLEMENTO DE REGISTRO                              | 113 119   | 9(07)       | 
        $linha .= setValor('', 7, '0');
        // (1) VALOR DO PAGTO          | VALOR PREVISTO DO PAGTO                              | 120 134   | 9(13)V9(02) |
        $linha .= setDecimal($detalhe['detalhe_valor_pagamento'], 15, '0', 'esquerda');
        // (*) NOSSO NÚMERO            | N° DOCTO ATRIBUÍDO PELO BANCO                        | 135 149   | X(15)       | NOTA 12
        $linha .= setValor($detalhe['detalhe_nosso_numero'], 15);
        // BRANCOS                     | COMPLEMENTO DE REGISTRO                              | 150 154   | X(05)
        $linha .= setValor('', 5);
        //(*) DATA EFETIVA             | DATA REAL EFETIVAÇÃO DO PAGTO                        | 155 162   | 9(08)       | DDMMAAAA
        $linha .= setValor($detalhe['detalhe_data_efetiva'], 8, '0');
        // (*) VALOR EFETIVO           | VALOR REAL EFETIVAÇÃO DO PAGTO                       | 163 177   | 9(13)V9(02) | 
        $linha .= setValor($detalhe['detalhe_valor_efetivo'], 15, '0');
        // FINALIDADE DETALHE          | INFORMAÇÃO COMPLEMENTAR P/ HIST. DE C/C              | 178 195   | X(18)       | NOTA 13
        $linha .= setValor($detalhe['detalhe_finalidade'], 18);
        // BRANCOS                     | COMPLEMENTO DE REGISTRO                              | 196 197   | X(2)        | 
        $linha .= setValor('', 2);
        // (*) N DO DOCUMENTO          | N° DO DOC/TED/ OP/ CHEQUE NO RETORNO                 | 198 203   | 9(6)        | NOTA 14
        $linha .= setValor($detalhe['detalhe_numero_documento'], 6, '0');
        // N DE INSCRIÇÃO              | N° DE INSCRIÇÃO DO FAVORECIDO (CPF/CNPJ)             | 204 217   | 9(14)       | NOTA 15
        $linha .= setValor($detalhe['detalhe_cpf_cnpj'], 14, ' ', 'esquerda');
        // FINALIDADE DOC E STATUS     |  FINALIDADE DO DOC E STATUS DO FUNCIONÃRIO NA EMPRESA| 218 219   | X(02)       |  NOTA 30
        $linha .= setValor($detalhe['detalhe_finalidade_doc_status'], 2);
        // (2) FINALIDADE TED          | FINALIDADE DA TED                                    | 220 224   | X(05)       | NOTA 26
        $linha .= setValor($detalhe['detalhe_finalidade_ted'], 5);
        // BRANCOS                     | COMPLEMENTO DE REGISTRO                              | 225 229   | X(05)       | 
        $linha .= setValor('', 5);
        // AVISO                       | AVISO AO FAVORECIDO                                  | 230 230   | X(01)       | NOTA 16
        $linha .= setValor($detalhe['detalhe_aviso'], 1);
        // (*) OCORRÊNCIAS             | CÓDIGO OCORRÊNCIAS NO RETORNO                        | 231 240   | X(10)       | NOTA 8
        $linha .= setValor('', 10);

        return $linha;
        
    }
}