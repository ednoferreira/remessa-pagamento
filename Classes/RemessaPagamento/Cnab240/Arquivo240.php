<?php

include 'Classes/RemessaPagamento/Cnab240/HeaderArquivo.php';
include 'Classes/RemessaPagamento/Cnab240/HeaderLote.php';
include 'Classes/RemessaPagamento/Cnab240/DetalheSegmentoA.php';
include 'Classes/RemessaPagamento/Cnab240/TrailerLote.php';
include 'Classes/RemessaPagamento/Cnab240/TrailerArquivo.php';

class Arquivo240 {

    public $dados;
    public $nome_arquivo;
    public $detalhes = [];
    public $quebra_linha = "\r\n";
    
    public function __construct($dados) {
        //
        $this->dados = $dados;
    }

    public function inserirDetalhe($detalhe){
        $this->detalhes[] = $detalhe;
    }

    /**
     * Gera a string de remessa de pagamento
     */
    public function gerarLinhas() {
        
        $conteudo = '';
        ##### Inserimos as linhas ao arq:
        // Header de arquivo
        $conteudo .= HeaderArquivo::gerar($this->dados) . $this->quebra_linha;
        // Header de lote:
        $conteudo .= HeaderLote::gerar($this->dados) . $this->quebra_linha;
        // Detalhes:;
        foreach($this->detalhes as $detalhe){
            $conteudo .=  DetalheSegmentoA::gerar($detalhe) . $this->quebra_linha;
        }
        // Trailer de lote:
        $conteudo .= TrailerLote::gerar($this->dados) . $this->quebra_linha;
        // Trailer de Arquivo:
        $conteudo .= TrailerArquivo::gerar($this->dados) . $this->quebra_linha;
        
        //
        return $conteudo;
    }
}
