<?php

    require 'Classes/RemessaPagamento/Remessa.php';

    // número sequencial do lote:
    $cod_lote = '1';
    
    // Informações da remessa:
    $dados_remessa = [
        'agencia'                      => '0172',
        'empresa_nome'                 => 'PROSELETA INFORMÁTICA LTDA', 
        'empresa_inscricao_tipo'       => 2, // 1 = CPF 2 = CNPJ
        'empresa_inscricao_numero'     => '77777777777777', // 14 carac
        'empresa_endereco'             => 'SIMONE AKEMI LIMA TAKEDA',
        'empresa_endereco_numero'      => '33',
        'empresa_endereco_complemento' => 'apto 05',
        'empresa_cidade'               => 'Aracruz',
        'empresa_cep'                  => '5555555',
        'empresa_uf'                   => 'es',
        'conta'                        => '10341',
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
        'detalhe_numero_registro'    => '2', // sequencial por detalhe (NOTAS 9)
        'detalhe_favorecido_banco'   => '341', // código do banco do favorecido
        // agencia + conta do favorecido (**** ver NOTA 11 pois existem especificações!):
        'detalhe_favorecido_agencia_conta' => '00024 000000014058 6', 
        'detalhe_favorecido_nome'    => 'João Augusto Matos',
        'detalhe_seu_numero'         => 'F02', // número do documento do favorecido, sequencial
        'detalhe_data_pagamento'     => '01122018',
        'detalhe_valor_pagamento'    => '99,30',
        'detalhe_cpf_cnpj'           => '77777777777',
    ];
    $favorecidos[] = [
        'detalhe_numero_registro'    => '3', // sequencial por detalhe (NOTAS 9)
        'detalhe_favorecido_banco'   => '341', // código do banco do favorecido
        // agencia + conta do favorecido (**** ver NOTA 11 pois existem especificações!):
        'detalhe_favorecido_agencia_conta' => '00024 000000014058 6', 
        'detalhe_favorecido_nome'    => 'Fernanda Nogueira Ramos',
        'detalhe_seu_numero'         => 'F03', // número do documento do favorecido, sequencial
        'detalhe_data_pagamento'     => '01122018',
        'detalhe_valor_pagamento'    => '25,55',
        'detalhe_cpf_cnpj'           => '99999999999',
    ];
    $favorecidos[] = [
        'detalhe_numero_registro'    => '3', // sequencial por detalhe (NOTAS 9)
        'detalhe_favorecido_banco'   => '341', // código do banco do favorecido
        // agencia + conta do favorecido (**** ver NOTA 11 pois existem especificações!):
        'detalhe_favorecido_agencia_conta' => '00024 000000014099 6', 
        'detalhe_favorecido_nome'    => 'Ricardo Morais',
        'detalhe_seu_numero'         => 'F04', // número do documento do favorecido, sequencial
        'detalhe_data_pagamento'     => '01122018',
        'detalhe_valor_pagamento'    => '90',
        'detalhe_cpf_cnpj'           => '33333333333',
    ];

    $Remessa = new Remessa('341', 'cnab240', $dados_remessa, $favorecidos);
    $remessa = $Remessa->gerarRemessa();
?>

<h1>Remessa de pagamento</h1>

<p>Atualmente do ITAU Cnab240</p>
<p>Amostra:</p>
<p>
    <?php
        echo $remessa['link'];
    ?>
</p>