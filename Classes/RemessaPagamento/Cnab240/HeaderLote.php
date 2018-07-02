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
        // NOME DO CAMPO       | SIGNIFICADO                         |  POSIÇÃO  | PICTURE | CONTEÚDO
        //============================================================================================
        // CÓDIGO DO BANCO     | CÓDIGO BANCO NA COMPENSAÇÃO         | 001 003   | 9(03)   | 341
        $linha .= setValor($dados['cod_banco'], 3);
        // CÓDIGO DO LOTE LOTE | IDENTIFICAÇÃO DE PAGTOS             | 004 007   | 9(04)   | NOTA 3
        // $linha .= setValor($dados[''], );
        // TIPO DE REGISTRO    | REGISTRO HEADER DE LOTE             | 008 008   | 9(01)   | 1
        // $linha .= setValor($dados[''], );
        (1) TIPO DE OPERAÇÃO TIPO DA OPERAÇÃO 009 009 X(01) C=CRÉDITO
        (3) TIPO DE PAGAMENTO TIPO DE PAGTO 010 011 9(02) NOTA 4
        (3) FORMA DE PAGAMENTO FORMA DE PAGAMENTO 012 013 9(02) NOTA 5
        LAYOUT DO LOTE N DA VERSÃO DO LAYOUT DO LOTE 014 016 9(03) 040
        BRANCOS COMPLEMENTO DE REGISTRO 017 017 X(01)
        EMPRESA – INSCRIÇÃO TIPO INSCRIÇÃO EMPRESA DEBITADA 018 018 9(01) 1 = CPF
        2 = CNPJ
        INSCRIÇÃO NÚMERO CNPJ EMPRESA DEBITADA 019 032 9(14) NOTA 1
        IDENTIFICAÇÃO DO
        LANÇAMENTO IDENTIFICAÇÃO DO LANÇAMENTO NO EXTRATO DO FAVORECIDO 033 036 X(04) NOTA 13
        BRANCOS COMPLEMENTO DE REGISTRO 037 052 X(16)
        AGÊNCIA NÚMERO AGÊNCIA DEBITADA 053 057 9(05) NOTA 1
        BRANCOS COMPLEMENTO DE REGISTRO 058 058 X(01)
        CONTA NÚMERO DE C/C DEBITADA 059 070 9(12) NOTA 1
        BRANCOS COMPLEMENTO DE REGISTRO 071 071 X(01)
        DAC DAC DA AGÊNCIA/CONTA DEBITADA 072 072 9(01) NOTA 1
        NOME DA EMPRESA NOME DA EMPRESA DEBITADA 073 102 X(30)
        (2) FINALIDADE DO LOTE FINALIDADE DOS PAGTOS DO LOTE 103 132 X(30) NOTA 6
        HISTÓRICO DE C/C COMPLEMENTO HISTÓRICO C/C DEBITADA 133 142 X(10) NOTA 7
        ENDEREÇO DA EMPRESA NOME DA RUA, AV, PÇA, ETC... 143 172 X(30)
        NÚMERO NÚMERO DO LOCAL 173 177 9(05)
        COMPLEMENTO. CASA, APTO, SALA, ETC... 178 192 X(15)
        CIDADE NOME DA CIDADE 193 212 X(20)
        CEP CEP 213 220 9(08)
        ESTADO SIGLA DO ESTADO 221 222 X(02)
        BRANCOS COMPLEMENTO DE REGISTRO 223 230 X(08)
        (*) OCORRÊNCIAS CÓDIGO OCORRÊNCIAS P/RETORNO 231 240 X(10) NOTA 8
        return $linha;
    }

    /*
        (*) Campos que constam apenas no arquivo retorno. Na remessa, devem ser informados com brancos ou
        zeros, conforme a picture de cada campo.
        (1) Quando se tratar de lote para financiamento de Bens e Serviços (COMPROR/FINABS) ou Desconto
        de NPR (Crédito Rural), previamente contratado junto ao banco, o conteúdo deste campo deve ser
        identificado pela letra "F". Esta sistemática só se aplica ao Pagamento de Fornecedores, tipo 20, cuja
        forma seja crédito em Conta Corrente, DOC e TED.
        (2) Sempre que o serviço for referente à “Holerite - Demonstrativo de Pagamentos / Informe de
        Rendimentos” e débito em Conta Investimento, deverá ser indicado na posição 103 a 104 o código
        correspondente, conforme Nota 6 deste manual;
        (3) Quando se tratar de “Informe de Rendimentos”, utilizar nestes campos conteúdo “30” para Tipo de
        Pagamento e “01” para Forma de Pagamento. Para operações de débito/crédito em Conta
        Investimento utilizar somente Formas de Pagamento “06” (crédito em conta de mesma titularidade) ou
        “43” (TED – mesmo titular). Especificamente para movimentações envolvendo Conta Investimento, os
        pagamentos na forma de TED de mesma titularidade podem ser de qualquer valor, inclusive inferior
        ao limite estabelecido pelo Banco Central do Brasil.
     */

}