<?php

/**
 * Arquivo que gera a remessa
 * * * Versão inicial de testes
 * @author Edno
 * 
 * Links úteis:
 * http://download.itau.com.br/bankline/SISPAG_CNAB.pdf (manual ITAU Cnab240)
 */

 // Funções auxiliares:
include 'Classes/RemessaPagamento/Funcoes.php';

// Timezone:
date_default_timezone_set('America/Sao_Paulo');

class Remessa {

    // Lista de bancos disponíveis:
    const ITAU = 341;

    public $caminhoArquivo = 'remessas_geradas';

    public $formato;
    public $dados = [];
    public $detalhes = [];

    /** 
     * Campos que entram na geração do arquivo;
     * nome_campo => valor_padrao ou null
     */
    public $campos = [
        // //
        // 'agencia'                  => null,
        // 'empresa_inscricao_tipo'   => null, // 1 = CPF 2 = CNPJ
        // 'empresa_inscricao_numero' => null, // 14 carac
        // 'empresa_nome'             => null, 
        // 'empresa_endereco'         => null, 
        // 'empresa_endereco_numero'  => null,
        // 'empresa_endereco_complemento' => null,
        // 'empresa_cidade'           => null,
        // 'empresa_cep'              => null,
        // 'empresa_uf'               => null,
        // 'conta'                    => null,
        // 'dac'                      => null,
        'qtd_lotes'                => null,
        // HeaderArquivo
        // 'cod_banco'                => '341',
        // 'cod_lote_servico'         => '0000',
        // 'header_arq_tipo_registro' => '0',
        // 'complemento_registro'     => '',
        // 'layout_arquivo'           => '081',
        // 'empresa_inscricao_tipo'   => '2', //1 = CPF, 2 = CNPJ
        // 'empresa_inscricao_numero' => null,
        // 'agencia'                  => null,
        // HeaderLote
        // 'cod_remessa'              => '1', // 1 = remessa, 2 = retorno
        // 'header_cod_lote'          => '0001', // sequencial (NOTAS 3)
        // 'header_lote_tipo_registro' => '1',
        // 'tipo_operacao'            => 'C', // C = Crédito
        // 'tipo_pagamento'           => '98', // 98 = Diversos, ver a pagina NOTAS > NOTA 4 do PDF
        // 'forma_pagamento'          => '01', // 01 = CRÉDITO EM CONTA CORRENTE NO ITAÚ / (NOTAS 5)
        // 'layout_lote'              => '040',
        // 'identificacao_lancamento' => 'HP13', // NOTAS 13
        // 'finalidade_lote'          => '10', // NOTAS 6
        /* Estes campos vêm na hora de gerar o arquivo:
        // Detalhe Segmento A
        'detalhe_cod_lote'           => '', // sequencial por lote (NOTAS 3)
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
        // Trailer de lote
        'trailer_lote_cod_lote'           => $cod_lote,
        'trailer_lote_tipo_registro'      => '5',
        // Trailer de arquivo
        'trailer_arq_cod_lote'       => '9999',
        'trailer_arq_tipo_registro'  => '9',
        */
    ];

    public function __construct($dados, $detalhes = [], $codigo_banco, $formato = 'cnab240') {

        // validamos os valores recebidos e valores padrão:
        $dados = Self::setValoresPadrao($dados);

        $this->dados        = $dados;
        $this->formato      = $formato;
        $this->codigo_banco = $codigo_banco;
        // favorecidos:
        $this->detalhes     = $detalhes;
    }

    /**
     * Recebe os dados da remessa a ser gerada
     * retorna link para o download do arquivo
     */
    public function gerarRemessa($nome_arquivo)
    {
        $resposta = [];

        // Removemos os arquivos anteriores para não deixar lixo:
        Self::removerArquivosAnteriores();

        $caminho_arquivo = $this->caminhoArquivo . DIRECTORY_SEPARATOR . $nome_arquivo;

        // insere os dados do banco aos parâmetros:
        $this->dados['banco'] = Self::getBanco($this->codigo_banco);

        // Tratamos os dados de entrada:
        $this->dados    = Self::tratarString($this->dados);
        $this->detalhes = Self::tratarString($this->detalhes);

        // qtd de registros(detalhes)
        $this->dados['qtd_registros'] = count($this->detalhes);
        // Soma dos pagamentos:
        $this->dados['valor_total_pagamentos'] = Self::getValorTotalPagamentos($this->detalhes);

        switch ($this->formato) {
            // Formato CNAB240
            case 'cnab240':
                include 'Cnab240/Arquivo240.php';
                $r = new Arquivo240($this->dados, $caminho_arquivo);
                
                foreach($this->detalhes as $detalhe){

                    // Montar o campo agencia_conta dependendo do banco:
                    $detalhe['detalhe_favorecido_agencia_conta'] = Self::montarAgenciaContaFavorecido($detalhe);

                    // mesclamos o detalhe com o array principal:
                    $detalhe = array_merge($detalhe, $this->dados);
                    $r->inserirDetalhe($detalhe);
                }

                $remessa = $r->gerarLinhas();

                // gera o arquivo
                $arq = Self::gerarArquivo( $remessa, $caminho_arquivo );

                $resposta = [
                    'nome_arquivo' => $nome_arquivo,
                    'link'         => '<a href="'.$arq.'" target="_blank">'.$nome_arquivo.'</a>',
                ];
            break;

            default:
                throw new \InvalidArgumentException('Formato Cnab inválido: '. $this->formato);
        }

        return $resposta;
    }

    /**
     * Recebe um código de banco e retorna o código e nome do mesmo
     * @author Edno
     */
    public static function getBanco($cod_banco)
    {
        $retorno = false;

        if($cod_banco == self::ITAU){
            $retorno = [
                'cod_banco'  => self::ITAU,
                'nome_banco' => 'ITAÚ UNIBANCO S.A.',    
            ];
        }

        if(!$retorno){
            die('Código de banco inexistente: ' . $cod_banco);
        }

        return $retorno;
    }

    /**
     * Gera o arquivo txt (ou outra extensão) a partir da string recebida
     */
    public function gerarArquivo($conteudo, $caminho_arquivo) {

        $arq = fopen($caminho_arquivo, 'w');
        if(! $arq){
            die('Nâo foi possível criar o arquivo '. $caminho_arquivo);
        }
        fwrite($arq, $conteudo);
        fclose($arq);
        return $caminho_arquivo;
    }

    /**
     * Percorremos o array para remover acentos chamando uma função externa,
     * também colocamos em uppercase
     * Pode ser array multidimensional:
     */
    public function tratarString($string){
        if(is_array($string)){
            //var_dump($string); exit;
            foreach($string as $campo => $valor){
                $valor = Self::tratarString($valor);
                $string[$campo] = $valor;
            }
        }else{
            $string  = removerAcentos($string);
            $string  = strtoupper($string);
        }
        return $string;
    }

    /**
     * Remover arquivos gerados anteriormente:
     */
    public function removerArquivosAnteriores() {
        $path =  '.' . DIRECTORY_SEPARATOR . $this->caminhoArquivo . DIRECTORY_SEPARATOR . '*';
        $arquivos = glob($path);
        foreach($arquivos as $a){
            if(is_file($a))
                unlink($a);
        }
    }

    /**
     * Setamos os valores padrão, caso não estejam no array de entrada
     */
    public function setValoresPadrao($dados) {
        foreach ($this->campos as $campo => $valor) {
            if (!isset($dados[$campo])) {
                if (is_null($valor))
                    die('O campo "'.$campo.'" não possui um valor padrão.');
                else
                    $dados[$campo] = $valor;
            }
        }
        return $dados;
    }

    /**
     * Obter o total de pagamentos do lote
     */
    public function getValorTotalPagamentos($detalhes) {
        $total = 0;
        foreach ($detalhes as $d) {
            if (isset($d['detalhe_valor_pagamento']) && ($d['detalhe_valor_pagamento'] > 0)) {
                // substituir a vírgula pelo ponto, para não dar problema na soma:
                $valor = str_replace(',', '.', $d['detalhe_valor_pagamento']);
                $total += $valor;
            }
        }
        return $total;
    }

    /**
     * Montar o campo agencia + conta do favorecido
     * de acordo com algumas especificações do Itaú:
     * Ver o item NOTAS 11 do pdf para referência
     * retorna algo assim:
     * 00024 000000014058 6
     */
    public function montarAgenciaContaFavorecido($detalhe) {

        $conteudo = '';
        // Se for do banco Itau:
        if($detalhe['detalhe_favorecido_banco'] == '341'){
            $conteudo  = setValor('', 1, '0');
            $conteudo .= setValor($detalhe['detalhe_favorecido_agencia'], 4, '0', 'esquerda');
            $conteudo .= setValor('', 1);
            $conteudo .= setValor('', 6, '0');
            $conteudo .= setValor($detalhe['detalhe_favorecido_conta'], 6, '0', 'esquerda');
            $conteudo .= setValor('', 1);
            $conteudo .= setValor($detalhe['detalhe_favorecido_digito'], 1);
        }else{
            $conteudo .= setValor($detalhe['detalhe_favorecido_agencia'], 5, '0', 'esquerda');
            $conteudo .= setValor('', 1);
            $conteudo .= setValor($detalhe['detalhe_favorecido_conta'], 12, '0', 'esquerda');
            $conteudo .= setValor('', 1);
            $conteudo .= setValor($detalhe['detalhe_favorecido_digito'], 1);
        }

        return $conteudo;
    }
}
