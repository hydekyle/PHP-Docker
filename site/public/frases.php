<?php
    $frases = file('./frases.txt');
    $num = mt_rand(0, count($frases) - 1);
    echo $frases[$num];
?>