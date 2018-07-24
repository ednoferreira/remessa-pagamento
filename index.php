<?php

    require 'Classes/RemessaPagamento/Remessa.php';

    // número sequencial do lote: 
    $cod_lote = '1';

    // Data de pagamento:
    $data_pagamento  = date('ddmmYYYY'); // ddmmaaaa
    $valor_pagamento = '1';
    
    // Informacoees da remessa:
    // $dados_remessa = [
    //     'empresa_nome'                 => 'EMPRESA LTDA', 
    //     'empresa_inscricao_tipo'       => 2, // 1 = CPF 2 = CNPJ
    //     'empresa_inscricao_numero'     => '012345674815815', // 14 carac
    //     'empresa_endereco'             => 'AV Anapolis, Vila Rica',
    //     'empresa_endereco_numero'      => '100',
    //     'empresa_endereco_complemento' => 'CJ 03',
    //     'empresa_cidade'               => 'Barueri',
    //     'empresa_cep'                  => '74185296',
    //     'empresa_uf'                   => 'SP',
    //     'agencia'                      => '1234',
    //     'conta'                        => '12345',
    //     'dac'                          => '9', // "digito de auto conferencia"
    //     // Detalhe
    //     'detalhe_cod_lote'           => $cod_lote, // sequencial por lote (NOTAS 3)
    //     'detalhe_tipo_registro'      => '3',
    //     'segmento_codigo'            => 'A',
    //     'detalhe_tipo_movimento'     => '000', // 000 = inclusão de pagamento (NOTAS 10)
    //     'detalhe_camara'             => '888', // Notas 37
    //     'detalhe_moeda'              => 'REA', // REA ou 009
    //     'detalhe_cod_ispb'           => '888', // NOTAS 37
    //     'detalhe_finalidade'         => '', // NOTAS 13
    //     'detalhe_finalidade_doc_status' => '01', // 01 = crédito em conta - NOTAS 30
    //     'detalhe_finalidade_ted'        => '00010', // 00010 = crédito em conta - NOTAS 26
    //     'detalhe_aviso'              => '0', // Notas 16
    //     // Campos que constam apenas no arquivo de retorno, ou seja, vão em branco ou zeros na remessa:
    //     'detalhe_nosso_numero'       => '', // para gerar: brancos. No retorno que vem preenchido (NOTAS 12)
    //     'detalhe_data_efetiva'       => '',
    //     'detalhe_valor_efetivo'      => '',
    //     'detalhe_numero_documento'   => '',
    //     // Trailer de lote
    //     'trailer_lote_cod_lote'           => $cod_lote,
    //     'trailer_lote_tipo_registro'      => '5',
    //     // Trailer de arquivo
    //     'trailer_arq_cod_lote'       => '9999',
    //     'trailer_arq_tipo_registro'  => '9',
    // ];
    
    // A lista dos favorecidos deve ser um array dessa forma:
    // $favorecidos[] = [
    //     'detalhe_numero_registro'    => '1', // sequencial por detalhe (NOTAS 9)
    //     'detalhe_favorecido_banco'   => '341', // codigo do banco do favorecido
    //     'detalhe_favorecido_nome'    => 'NOME DA PESSOA',
    //     'detalhe_cpf_cnpj'           => '99999999999',
    //     // agencia + conta do favorecido (**** ver NOTA 11 pois existem especificacoes!):
    //     'detalhe_favorecido_agencia' => '4444', 
    //     'detalhe_favorecido_conta'   => '01425',
    //     'detalhe_favorecido_digito'  => '5', 
    //     'detalhe_seu_numero'         => '001', // numero do documento do favorecido, sequencial (tamanho max: 15)
    //     'detalhe_data_pagamento'     => '24072018', //ddmmaaaa
    //     'detalhe_valor_pagamento'    => '1', // ex: 1 ou 1,00 ou 1.00 (para 1 real)
    // ];

    // Dados vindos de um json:
    $json = json_decode(file_get_contents('dados.json'), true);
    // Dados da empresa + configurações gerais
    $dados_remessa = array_merge($json['empresa'], $json['configuracoes']);
    // Lista de favorecidos
    $favorecidos = $json['favorecidos'];

    //echo '<pre>'.print_r($dados_remessa, 1).'</pre>'; exit;

    $Remessa = new Remessa($dados_remessa, $favorecidos, '341', 'cnab240');
    $remessa = $Remessa->gerarRemessa('remessa_'.date('dmyhis').'.txt');
?>

<h1>Remessa de pagamento</h1>

<p>Atualmente do ITAU Cnab240</p>
<p>Amostra:</p>
<p>
    <?php
        echo $remessa['link'];
    ?>
</p>