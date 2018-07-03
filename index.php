<?php

    require 'Classes/RemessaPagamento/Remessa.php';
    
    $Remessa = new Remessa();
    $dados = [
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
        'dac'                          => '0',
        // Pagamentos a serem efetuados (para fim de teste)
        'registros'                    => [

        ]
    ];
?>

<h1>Remessa de pagamento</h1>

<p>Atualmente do ITAU Cnab240</p>
<p>Amostra:</p>
<p>
    <?php
        $dados = $Remessa->gerarRemessa('341', 'cnab240', $dados);
        echo $dados['link'];
    ?>
</p>