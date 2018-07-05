<?php

    require 'Classes/RemessaPagamento/Remessa.php';

    // número sequencial do lote:
    $cod_lote = '1';
    
    // Informações da remessa:
    $dados_remessa = [
        'empresa_nome'                 => 'ProSeleta Ltda', 
        'empresa_inscricao_tipo'       => 2, // 1 = CPF 2 = CNPJ
        'empresa_inscricao_numero'     => '17151814161235', // 14 carac
        'empresa_endereco'             => 'Av Formosa',
        'empresa_endereco_numero'      => '100',
        'empresa_endereco_complemento' => 'Vila Nilva',
        'empresa_cidade'               => 'Aracruz',
        'empresa_cep'                  => '12345809',
        'empresa_uf'                   => 'es',
        'agencia'                      => '0011',
        'conta'                        => '74185',
        'dac'                          => '0', // "dígito de auto conferência", é o dígito da conta

        // Detalhe
        'detalhe_cod_lote'           => $cod_lote, // sequencial por lote (NOTAS 3)
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
    ];
    
    // Lista dos favorecidos:
    $favorecidos[] = [
        'detalhe_numero_registro'    => '1', // sequencial por detalhe (NOTAS 9)
        'detalhe_favorecido_banco'   => '341', // código do banco do favorecido
        'detalhe_favorecido_nome'    => 'João da Silva',
        'detalhe_cpf_cnpj'           => '77777777777',
        // agencia + conta do favorecido (**** ver NOTA 11 pois existem especificações!):
        'detalhe_favorecido_agencia' => '1100', 
        'detalhe_favorecido_conta'   => '55555',
        'detalhe_favorecido_digito'  => '9', 
        'detalhe_seu_numero'         => 'F01', // número do documento do favorecido, sequencial
        'detalhe_data_pagamento'     => '05072018',
        'detalhe_valor_pagamento'    => '99,50',
    ];

    $favorecidos[] = [
        'detalhe_numero_registro'    => '2', // sequencial por detalhe (NOTAS 9)
        'detalhe_favorecido_banco'   => '104', // código do banco do favorecido
        'detalhe_favorecido_nome'    => 'Maria Joana Rocha',
        'detalhe_cpf_cnpj'           => '88888888888',
        // agencia + conta do favorecido (**** ver NOTA 11 pois existem especificações!):
        'detalhe_favorecido_agencia' => '4444', 
        'detalhe_favorecido_conta'   => '66666666',
        'detalhe_favorecido_digito'  => '0', 
        'detalhe_seu_numero'         => 'F02', // número do documento do favorecido, sequencial
        'detalhe_data_pagamento'     => '05072018',
        'detalhe_valor_pagamento'    => '15,30',
    ];

    $Remessa = new Remessa('341', 'cnab240', $dados_remessa, $favorecidos);
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