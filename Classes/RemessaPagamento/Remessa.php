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

    public function __construct() {
        
    }

    /**
     * Recebe os dados da remessa a ser gerada
     * retorna link para o download do arquivo
     */
    public function gerarRemessa($codigo_banco, $formato = 'cnab240', $dados)
    {
        $resposta = [];

        // Removemos os arquivos anteriores para não deixar lixo:
        Self::removerArquivosAnteriores();
        
        // insere os dados do banco aos parâmetros:
        $dados['banco'] = Self::getBanco($codigo_banco);

        // Nome do arquivo a ser gerado:
        $nome_arquivo = Self::getNomeArquivo();
        // Caminho a ser salvo o arquivo;
        $caminho_arquivo = Self::getCaminhoArquivo();

        // Tratamos os dados de entrada:
        $dados = Self::removerAcentos($dados);

        switch ($formato) {
            // Formato CNAB240
            case 'cnab240':
                include 'Cnab240/Arquivo240.php';
                $r = new Arquivo240($dados, $caminho_arquivo);
                $arq = $r->gerarArquivo();

                $resposta = [
                    'nome_arquivo' => $nome_arquivo,
                    'link'         => '<a href="'.$arq.'" target="_blank">'.$nome_arquivo.'</a>',
                ];
            break;

            default:
                throw new \InvalidArgumentException('Formato Cnab inválido: '. $formato);
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
     * Percorremos o array para remover acentos chamando uma função externa
     * Pode ser array multidimensional:
     */
    public function removerAcentos($string){
        if(is_array($string)){
            //var_dump($string); exit;
            foreach($string as $campo => $valor){
                $string[$campo] = Self::removerAcentos($valor);
            }
        }else{
            $string = removerAcentos($string);
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
}
