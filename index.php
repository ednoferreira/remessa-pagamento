<?php

    require 'Classes/RemessaPagamento/Remessa.php';
    
    $Remessa = new Remessa();
    $dados = [
        'agencia'      => '0172',
        'empresa_inscricao_tipo'   => 2, // 1 = CPF 2 = CNPJ
        'empresa_inscricao_numero' => '77777777777777', // 14 carac
        'conta' => '10341',
        'dac'   => '0',
        'nome_empresa' => 'MAKIYAMA INFORMÁTICA LTDA', 
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