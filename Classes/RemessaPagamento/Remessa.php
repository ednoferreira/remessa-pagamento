<?php

/**
 * Arquivo que gera a remessa
 * * * Versão inicial de testes
 * @author Edno
 * 
 * Links úteis:
 * http://download.itau.com.br/bankline/SISPAG_CNAB.pdf (manual ITAU Cnab240)
 * https://github.com/andersondanilo/CnabPHP (biblioteca que gera arquivo de remessa de cobrança, apenas para referência)
 */

 // Funções auxiliares:
include 'Classes/RemessaPagamento/Funcoes.php';

// Timezone:
date_default_timezone_set('America/Sao_Paulo');

class Remessa {

    // Lista de bancos disponíveis:
    const ITAU = 341;

    // nome do arquivo de saida:
    public $nomeArquivo     = 'remessa';
    public $extensaoArquivo = 'txt';
    // onde gerar o arquivo?
    public $caminhoArquivo  = 'arquivos_gerados';
    // concatenar o nome do arquivo com a datahora geracao:
    public $concatenarDataHora = true;

    public $formato;
    public $dados = [];
    public $detalhes = [];

    /** 
     * Campos que entram na geração do arquivo;
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
        'qtd_lotes'                => '1',
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
    ];

    public function __construct($codigo_banco, $formato = 'cnab240', $dados, $detalhes = []) {

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
    public function gerarRemessa()
    {
        $resposta = [];

        // Removemos os arquivos anteriores para não deixar lixo:
        Self::removerArquivosAnteriores();

        // Nome do arquivo a ser gerado:
        $nome_arquivo = Self::getNomeArquivo();
        // Caminho a ser salvo o arquivo;
        $caminho_arquivo = Self::getCaminhoArquivo();

        // insere os dados do banco aos parâmetros:
        $this->dados['banco'] = Self::getBanco($this->codigo_banco);

        // Tratamos os dados de entrada:
        $this->dados    = Self::tratarString($this->dados);
        $this->detalhes = Self::tratarString($this->detalhes);

        // qtd de registros(detalhes)
        $this->dados['qtd_registros'] = count($this->detalhes);
        // Soma dos pagamentos:
        $this->dados['total_pagamentos'] = Self::getTotalPagamentos($this->detalhes);

        switch ($this->formato) {
            // Formato CNAB240
            case 'cnab240':
                include 'Cnab240/Arquivo240.php';
                $r = new Arquivo240($this->dados, $caminho_arquivo);
                
                foreach($this->detalhes as $detalhe){
                    // mesclamos o detalhe com o array principal:
                    $detalhe = array_merge($detalhe, $this->dados);
                    $r->inserirDetalhe($detalhe);
                }

                $arq = $r->gerarArquivo();
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
     * Gera o nome do arquivo de saída, 
     * de acordo com o caminho do diretório, extensão e outras condições:
     */
    public function getNomeArquivo() {
        $nome = $this->nomeArquivo;
        if($this->concatenarDataHora){
            $nome .= '_'.date('d_m_Y_H_i_s');
        }
        $nome .= '.'.$this->extensaoArquivo;
        return $nome;
    }

    /**
     * Obtemos o caminho onde o arquivo será salvo:
     */
    public function getCaminhoArquivo() {
        return $this->caminhoArquivo. DIRECTORY_SEPARATOR .Self::getNomeArquivo();
    }

    /**
     * Remover arquivos gerados anteriormente:
     */
    public function removerArquivosAnteriores() {
        $arquivos = glob($this->caminhoArquivo . DIRECTORY_SEPARATOR . '*');
        foreach($arquivos as $a){
            if(is_file($a))
                unlink($a);
        }
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

    /**
     * Obter o total de pagamentos do lote
     */
    public function getTotalPagamentos($detalhes) {
        $total = 0;
        foreach($detalhes as $d){
            if(isset($d['detalhe_valor_pagamento'])){
                // substituir a vírgula pelo ponto, para não dar problema na soma:
                $valor = str_replace(',', '.', $d['detalhe_valor_pagamento']);
                $total += $valor;
            }
        }
        return $total;
    }
}
