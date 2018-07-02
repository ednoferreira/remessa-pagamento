<?php

    require 'Classes/RemessaPagamento/Remessa.php';
    
    $Remessa = new Remessa();
    $dados = [
        'cliente_nome' => 'Edno Nunes Ferreira',
    ];
    echo $Remessa->gerarRemessa('314', 'cnab240', $dados);
?>

<h1>Teste</h1>

<p>Apenas um teste</p>