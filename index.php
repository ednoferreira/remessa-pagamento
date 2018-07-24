<?php

    require 'Classes/RemessaPagamento/Remessa.php';

    // Dados vindos de um json:
    $json = json_decode(file_get_contents('dados.json'), true);
    // Dados da empresa + configurações gerais
    $dados_remessa = array_merge($json['empresa'], $json['configuracoes']);
    // Lista de favorecidos
    $favorecidos = $json['favorecidos'];

    //echo '<pre>'.print_r($dados_remessa, 1).'</pre>'; exit;

    // Configuramos alguns campos em tempo de execução:
    $sequencial = 1;
    foreach ($favorecidos as $index => $f) {
        $favorecidos[$index]['detalhe_numero_registro'] = $sequencial;
        $sequencial++;
    }

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