<?php 

/**
 * Gerar o header do arquivo
 */
class HeaderArquivo {

    /**
     * Gerar o Header de Arquivo
     * legenda de conteúdo: X = ALFANUMÉRICO 9 = NUMMERAÇÃO V = VÍRGULA DECIMAL ASSUMIDA
     */
    public static function gerar($dados) {
        
        $linha = '';
        // NOME DO CAMPO       | SIGNIFICADO                         |  POSIÇÃO  | PICTURE | conteúdo
        //============================================================================================
        // CÓDIGO DO BANCO     | CÓDIGO DO BCO NA COMPENSAÇÃO        | [001,003] | 9(03)   | 341 
        $linha .= setValor($dados['banco']['cod_banco'], 3, '0', 'esquerda');
        // CÓDIGO DO LOTE      | LOTE DE SERVIÇO                     | 004 007   | 9(04)   | 0000
        $linha .= setValor($dados['cod_lote_servico'], 4);
        // TIPO DE REGISTRO    | REGISTRO HEADER DE ARQUIVO          | 008 008   | 9(01)   | 0
        $linha .= setValor($dados['header_arq_tipo_registro'], 1);
        // BRANCOS             | COMPLEMENTO DE REGISTRO             | 009 014   | X(06)   | 
        $linha .= setValor('', 6);
        // LAYOUT DE ARQUIVO   | N DA VERSÃO DO LAYOUT DO ARQUIVO    | 015 017   | 9(03)   | 081
        $linha .= setValor($dados['banco']['header_arquivo']['layout_arquivo'], 3);
        // EMPRESA INSCRIÇÃO   | TIPO DE INSCRIÇÃO DA EMPRESA          | 018 018   | 9(01)   | 1 = CPF 2 = CNPJ
        $linha .= setValor($dados['empresa_inscricao_tipo'], 1);
        // INSCRIÇÃO NÚMERO    | CNPJ EMPRESA DEBITADA               | 019 032   | 9(14)   | NOTA 1
        $linha .= setValor($dados['empresa_inscricao_numero'], 14);
        // Linha com especificações em cada banco:                   | 033 052   | X(20)   | 
        $linha .= montarConvenio($dados);
        // AGÊNCIA             | NÚMERO AGÊNCIA DEBITADA             | 053 057   | 9(05)   | NOTA 1
        $linha .= setValor($dados['agencia'], 5, '0', 'esquerda');
        // BRANCOS             | COMPLEMENTO DE REGISTRO             | 058 058   | X(01)   |
        $linha .= setValor($dados['agencia_dv'], 1);
        // CONTA               | NÚMERO DE C/C DEBITADA              | 059 070   | 9(12)   | NOTA 1
        $linha .= setValor($dados['conta'], 12, '0', 'esquerda');
        // BRANCOS             | COMPLEMENTO DE REGISTRO             | 071 071   | X(01)   | 
        $linha .= setValor($dados['conta_dv'], 1);
        // DAC                 | DAC DA AGÊNCIA/CONTA DEBITADA       | 072 072   | 9(01)   | NOTA 1
        $linha .= setValor($dados['dac'], 1);
        // NOME DA EMPRESA     | NOME DA EMPRESA                     | 073 102   | X(30)   |
        $linha .= setValor($dados['empresa_nome'], 30);
        // NOME DO BANCO       | NOME DO BANCO                       | 103 132   | X(30)   | 
        $linha .= setValor($dados['banco']['nome_banco'], 30);
        // BRANCOS             | COMPLEMENTO DE REGISTRO             | 133 142   | X(10)   |
        $linha .= setValor('', 10);
        // ARQUIVO-CÓDIGO      | CÓDIGO REMESSA/RETORNO              | 143 143   | 9(01)   | 1=REMESSA 2=RETORNO
        $linha .= setValor($dados['cod_remessa'], 1);
        // DATA DE GERAÇÃO     | DATA DE GERAÇÃO DO ARQUIVO          | 144 151   | 9(08)   | DDMMAAAA
        $linha .= date('dmY');
        // HORA DA GERAÇÃO     | HORA DE GERAÇÃO DO ARQUIVO          | 152 157   | 9(06)   | HHMMSS
        $linha .= date('His');
        // NSA                 | Número Sequencial do Arquivo        | 158 163   | 9(06)   |
        $linha .= setValor(empty($dados['numero_sequencial_arquivo']) ? '' : $dados['numero_sequencial_arquivo'], 6, '0');
        // Num da versão do layout do arquivo                        | 164 166   | 9(3)    | 103 [Febraban]
        $linha .= setValor(empty($dados['numero_versao_layout_arquivo']) ? '' : $dados['numero_versao_layout_arquivo'], 3, '0');
        // DENSIDADE           | DENSIDADE DE GRAVAÇÃO DO ARQUIVO    | 167 171   | 9(05)   | NOTA 2
        $linha .= setValor('', 5, '0');
        // Reservado banco     | COMPLEMENTO DE REGISTRO       | 172 191   | X(19)   |
        $linha .= setValor(empty($dados['header_172_191']) ? '' : $dados['header_172_191'], 20);
        // Reservado empresa   | COMPLEMENTO DE REGISTRO     | 192 211   | X(20)   |
        $linha .= setValor(empty($dados['header_192_211']) ? '' : $dados['header_192_211'], 20);
        // Reservado FEBRABAN  | COMPLEMENTO DE REGISTRO     | 212 240   | X(29)   |
        $linha .= setValor('', 29);
        return $linha;
    }

}