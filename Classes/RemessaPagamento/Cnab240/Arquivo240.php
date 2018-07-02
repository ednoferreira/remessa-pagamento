<?php

include 'Classes/RemessaPagamento/Funcoes.php';

class Arquivo240 {

    public $dados;
    // nome do arquivo de saida:
    public $nomeArquivo     = 'remessa';
    public $extensaoArquivo = 'txt';
    // onde gerar o arquivo?
    public $caminhoArquivo  = 'arquivos_gerados';
    // concatenar o nome do arquivo com a datahora geracao:
    public $concatenarDataHora = true;

    /** 
     * Campos que entram na geração da remessa: nome_campo => valor_padrao ou null
     */
    public $campos = [
        'cod_banco'                => 341,
        'cod_lote_servico'         => '0000',
        'tipo_registro'            => '0',
        'complemento_registro'     => null,
        'layout_arquivo'           => '081',
        'empresa_inscricao_tipo'   => '2', //1 = CPF 2 = CNPJ
        'empresa_inscricao_numero' => '',
        'agencia'                  => null,
        //'' => '',
        //'' => '',
    ];

    
    public function __construct($dados) {
        $this->dados = Self::setValoresPadrao($dados);
    }

    /**
     * Gera o nome do arquivo de saída, 
     * de acordo com o caminho do diretório, extensão e outras condições:
     */
    public function getNomeArquivo() {
        $nome = $this->caminhoArquivo. DIRECTORY_SEPARATOR .$this->nomeArquivo;
        if($this->concatenarDataHora){
            $nome .= '_'.date('d_m_Y_H_i_s');
        }
        $nome .= '.'.$this->extensaoArquivo;
        return $nome;
    }

    /**
     * Gera o arquivo de remessa de pagamento
     */
    public function gerarArquivo() {
        $nome_arq = Self::getNomeArquivo();
        $arq = fopen($nome_arq, 'w');
        if(! $arq){
            die('Nâo foi possível criar o arquivo '. $nome_arq);
        }

        return $nome_arq;
    }

    /**
     * Setamos os valores padrão, caso não estejam no array de entrada
     */
    public function setValoresPadrao($dados) {
        //$dados['cod_lote_servico'] = checkCampo()
        return $dados;
    }

    /**
     * Gerar o Header de Arquivo
     * legenda de conteúdo: X = ALFANUMÉRICO 9 = NUMÉRICO V = VÍRGULA DECIMAL ASSUMIDA
     */
    public function header($dados) {

        $linha = '';
        // NOME DO CAMPO       | SIGNIFICADO                         |  POSIÇÃO  | PICTURE | CONTEÚDO
        //============================================================================================
        // CÓDIGO DO BANCO     | CÓDIGO DO BCO NA COMPENSAÇÃO        | [001,003] | 9(03)   | 341 
        $linha .= setValor($dados['cod_banco'], 1, 3);
        // CÓDIGO DO LOTE      | LOTE DE SERVIÇO                     | 004 007   | 9(04)   | 0000
        /*
        $linha .= $dados['cod_lote_servico'];
        // TIPO DE REGISTRO    | REGISTRO HEADER DE ARQUIVO          | 008 008   | 9(01)   | 0
        $linha .= $dados['tipo_registro'];
        // BRANCOS             | COMPLEMENTO DE REGISTRO             | 009 014   | X(06)   | 
        $linha .= $dados['complemento_registro'];
        // LAYOUT DE ARQUIVO   | N DA VERSÃO DO LAYOUT DO ARQUIVO    | 015 017   | 9(03)   | 081
        $linha .= $dados['layout_arquivo'];
        // EMPRESA – INSCRIÇÃO | TIPO DE INSCRIÇÃO DA EMPRESA        | 018 018   | 9(01)   | 1 = CPF 2 = CNPJ
        $linha .= $dados['empresa_inscricao_tipo'];
        // INSCRIÇÃO NÚMERO    | CNPJ EMPRESA DEBITADA               | 019 032   | 9(14)   | NOTA 1
        $linha .= $dados['empresa_inscricao_numero'];
        // BRANCOS             | COMPLEMENTO DE REGISTRO             | 033 052   | X(20)   | 
        $linha .= setValor(' ', 33, 52);
        // AGÊNCIA             | NÚMERO AGÊNCIA DEBITADA             | 053 057   | 9(05)   | NOTA 1
        $linha .= $dados['agencia'];
        // BRANCOS             | COMPLEMENTO DE REGISTRO             | 058 058   | X(01)   |
        $linha .= setBrancos(1)
        // CONTA               | NÚMERO DE C/C DEBITADA              | 059 070   | 9(12)   | NOTA 1
        $linha .= $dados['conta'];
        // BRANCOS             | COMPLEMENTO DE REGISTRO             | 071 071   | X(01)   | 
        $linha .= setBrancos(1);
        // DAC                 | DAC DA AGÊNCIA/CONTA DEBITADA       | 072 072   | 9(01)   | NOTA 1
        $linha .= $dados[''];
        // NOME DA EMPRESA     | NOME DA EMPRESA                     | 073 102   | X(30)   |
        $linha .= $dados[''];
        // NOME DO BANCO       | NOME DO BANCO                       | 103 132   | X(30)   | 
        $linha .= $dados[''];
        // BRANCOS             | COMPLEMENTO DE REGISTRO             | 133 142   | X(10)   |
        $linha .= $dados[''];
        // ARQUIVO-CÓDIGO      | CÓDIGO REMESSA/RETORNO              | 143 143   | 9(01)   | 1=REMESSA 2=RETORNO
        $linha .= $dados[''];
        // DATA DE GERAÇÃO     | DATA DE GERAÇÃO DO ARQUIVO          | 144 151   | 9(08)   | DDMMAAAA
        $linha .= $dados[''];
        // HORA DA GERAÇÃO     | HORA DE GERAÇÃO DO ARQUIVO          | 152 157   | 9(06)   | HHMMSS
        $linha .= $dados[''];
        // ZEROS               | COMPLEMENTO DE REGISTRO             | 158 166   | 9(09)   |
        $linha .= $dados[''];
        // UNIDADE DE DENSIDADE| DENSIDADE DE GRAVAÇÃO DO ARQUIVO    | 167 171   | 9(05)   | NOTA 2
        $linha .= $dados[''];
        // BRANCOS             | COMPLEMENTO DE REGISTRO             | 172 240   | X(69)   |
        $linha .= $dados[''];
        */

        return $linha;
    }
}
