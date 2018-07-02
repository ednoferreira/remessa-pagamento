<?php

include 'Classes/RemessaPagamento/Cnab240/HeaderArquivo.php';

class Arquivo240 {

    public $dados;
    public $nome_arquivo;

    /** 
     * Campos que entram na geração do HEADER;
     * nome_campo => valor_padrao ou null
     */
    public $campos = [
        'cod_banco'                => '341',
        'cod_lote_servico'         => '0000',
        'tipo_registro'            => '0',
        'complemento_registro'     => '',
        'layout_arquivo'           => '081',
        'empresa_inscricao_tipo'   => '2', //1 = CPF, 2 = CNPJ
        'empresa_inscricao_numero' => null,
        'agencia'                  => null,
        'cod_remessa'              => '1', // 1 = remessa, 2 = retorno
        //'' => '',
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

        // Inserimos as linhas ao arq:
        fwrite($arq, $headerArquivo);
        fclose($arq);
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
