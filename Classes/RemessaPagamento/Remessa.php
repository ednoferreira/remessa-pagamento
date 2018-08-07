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
    const BRASIL = 001;
    const ITAU   = 341;

    public $caminhoArquivo = 'remessas_geradas';

    public $formato;
    public $dados = [];
    public $detalhes = [];

    public function __construct($dados, $detalhes = [], $codigo_banco, $formato = 'cnab240') {

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

        // Verificamos os dados obrigatórios
        $this->dados = Self::verificarObrigatorios($this->dados);

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
     * Recebe um código de banco e retorna o código, nome e alguns valores específicos/fixos de cada instituição
     * @author Edno
     */
    public static function getBanco($cod_banco)
    {
        $retorno = false;

        if($cod_banco == self::ITAU){
            $retorno = [
                'cod_banco'  => self::ITAU,
                'nome_banco' => 'ITAÚ UNIBANCO S.A.',
                // Layout de arquivo 240 (015 a 017)
                'header_arquivo' => [
                    'layout_arquivo' => '081',
                ]
            ];
        }

        if($cod_banco == self::BRASIL){
            $retorno = [
                'cod_banco'  => self::BRASIL,
                'nome_banco' => 'Banco Do Brasil S.A.',
                // Layout de arquivo 240 (015 a 017)
                'header_arquivo' => [
                    'layout_arquivo' => '000',
                ],
                'convenio_codigo' => '0126',
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

    /**
     * Verificar campos que são obrigatório na hora de gerar a remessa, ex:
     * alguns bancos têm convênio, outros não tem e preenchemos com brancos
     */
    function verificarObrigatorios($dados) {
        
        if(empty($dados['convenio']))
            $dados['convenio'] = '';
        if(empty($dados['convenio_codigo']))
            $dados['convenio_codigo'] = '';
        
        return $dados;
    }
}
