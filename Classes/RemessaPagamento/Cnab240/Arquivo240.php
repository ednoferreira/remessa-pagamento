<?php

include 'Classes/RemessaPagamento/Cnab240/HeaderArquivo.php';
include 'Classes/RemessaPagamento/Cnab240/HeaderLote.php';
include 'Classes/RemessaPagamento/Cnab240/DetalheSegmentoA.php';

class Arquivo240 {

    public $dados;
    public $nome_arquivo;

    /** 
     * Campos que entram na geração do HEADER;
     * nome_campo => valor_padrao ou null
     */
    public $campos = [
        //
        'agencia'                  => null,
        'empresa_inscricao_tipo'   => null, // 1 = CPF 2 = CNPJ
        'empresa_inscricao_numero' => null, // 14 carac
        'empresa_nome'             => null, 
        'empresa_endereco'         => null, 
        'empresa_endereco_numero'  => null,
        'empresa_endereco_complemento' => null,
        'empresa_cidade'           => null,
        'empresa_cep'              => null,
        'empresa_uf'               => null,
        'conta'                    => null,
        'dac'                      => null,
        // HeaderArquivo
        'cod_banco'                => '341',
        'cod_lote_servico'         => '0000',
        'tipo_registro'            => '0',
        'complemento_registro'     => '',
        'layout_arquivo'           => '081',
        'empresa_inscricao_tipo'   => '2', //1 = CPF, 2 = CNPJ
        'empresa_inscricao_numero' => null,
        'agencia'                  => null,
        // HeaderLote
        'cod_remessa'              => '1', // 1 = remessa, 2 = retorno
        'header_cod_lote'          => '0001', // sequencial (NOTAS 3)
        'tipo_registro'            => '1',
        'tipo_operacao'            => 'C', // C = Crédito
        'tipo_pagamento'           => '98', // 98 = Diversos, ver a pagina NOTAS > NOTA 4 do PDF
        'forma_pagamento'          => '01', // 01 = CRÉDITO EM CONTA CORRENTE NO ITAÚ / (NOTAS 5)
        'layout_lote'              => '040',
        'identificacao_lancamento' => 'HP13', // NOTAS 13
        'finalidade_lote'          => '10', // NOTAS 6
        // Detalhe Segmento A
            
        // Valores fixos por detalhe
            'detalhe_cod_lote'           => '0001', // sequencial por lote (NOTAS 3)
            'detalhe_tipo_registro'      => '3',
            'segmento_codigo'            => 'A',
            'detalhe_tipo_movimento'     => '000', // 000 = inclusão de pagamento (NOTAS 10)
            'detalhe_camara'             => '888', // Notas 37
            'detalhe_moeda'              => 'REA', // REA ou 009
            'detalhe_cod_ispb'           => '888', // NOTAS 37
            'detalhe_finalidade'         => '', // NOTAS 13
            'detalhe_finalidade_doc_status' => '01', // 01 = crédito em conta - NOTAS 30
            'detalhe_finalidade_ted'        => '00010', // 00010 = crédito em conta - NOTAS 26
            'detalhe_aviso'              => '0', // Notas 16
            // Campos que constam apenas no arquivo de retorno, ou seja, vão em branco ou zeros na remessa:
            'detalhe_nosso_numero'       => '', // para gerar: brancos. No retorno que vem preenchido (NOTAS 12)
            'detalhe_data_efetiva'       => '',
            'detalhe_valor_efetivo'      => '',
            'detalhe_numero_documento'   => '',
        // valores variáveis por detalhe(favorecido):
            'detalhe_numero_registro'    => '1', // sequencial por detalhe (NOTAS 9)
            'detalhe_favorecido_banco'   => '341', // código do banco do favorecido
            // agencia + conta do favorecido (**** ver NOTA 11 pois existem especificações!):
            'detalhe_favorecido_agencia_conta' => '00024 000000014062 6', 
            'detalhe_favorecido_nome'    => 'Maria Joana Santos',
            'detalhe_seu_numero'         => 'F01', // número do documento do favorecido, sequencial
            'detalhe_data_pagamento'     => '01122018',
            'detalhe_valor_pagamento'    => '152,25',
            'detalhe_cpf_cnpj'           => '66666666666',
    ];
    
    public function __construct($dados, $nome_arquivo) {
        // validamos os valores recebidos e valores padrão:
        $dados = Self::tratarValoresEntrada($dados);
        //
        $this->dados = $dados;
        // nome do arquivo a ser gerado:
        $this->nome_arquivo = $nome_arquivo;
        // TO DO: Colocar o próprio remessa para gerar o arquivo após receber apenas a string
    }

    /**
     * Gera o arquivo de remessa de pagamento
     */
    public function gerarArquivo() {

        $nome_arq = $this->nome_arquivo;
        $arq = fopen($nome_arq, 'w');
        if(! $arq){
            die('Nâo foi possível criar o arquivo '. $nome_arq);
        }
        // Montamos o arquivo:
        $headerArquivo = HeaderArquivo::gerar($this->dados);
        $headerLote    = HeaderLote::gerar($this->dados);
        $detalheA      = DetalheSegmentoA::gerar($this->dados);

        // Inserimos as linhas ao arq:
        fwrite($arq, $headerArquivo . PHP_EOL);
        fwrite($arq, $headerLote    . PHP_EOL);
        fwrite($arq, $detalheA      . PHP_EOL);
        fclose($arq);
        //
        return $nome_arq;
    }

    /**
     * - - - - - - - - - - - - Métodos auxiliares - - - - - - - - - - - - 
     */

    /**
     * Trata os valores de entrada: verificando padrões...
     */
    public function tratarValoresEntrada($dados) {
        $dados = Self::setValoresPadrao($dados);
        return $dados;
    }

    /**
     * Setamos os valores padrão, caso não estejam no array de entrada
     */
    public function setValoresPadrao($dados) {
        foreach($this->campos as $campo => $valor) {
            if(!isset($dados[$campo]) ){
                if ( is_null($valor) )
                    die('O campo "'.$campo.'" não possui um valor padrão.');
                else
                    $dados[$campo] = $valor;
            }
        }
        return $dados;
    }
}
