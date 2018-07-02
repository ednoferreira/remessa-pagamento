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

class Remessa {

    // Lista de bancos disponíveis:
    const ITAU = 341;

    /**
     * Recebe os dados da remessa a ser gerada
     * retorna link para o download do arquivo
     */
    public static function gerarRemessa($codigo_banco, $formato = 'cnab240', $dados)
    {
        switch ($formato) {
            // Formato CNAB240
            case 'cnab240':
                include 'Cnab240/Arquivo240.php';
                $r = new Arquivo240($dados);
                echo $r->gerarArquivo();
            break;

            default:
                throw new \InvalidArgumentException('Formato Cnab inválido: '. $formato);
        }
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
                'codigo_do_banco' => self::ITAU,
                'nome_do_banco' => 'BANCO ITAU SA',    
            ];
        }
        return $retorno;
    }
}
