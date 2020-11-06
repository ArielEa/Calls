<?php

function para()
{
    include_once "Spyc.php";
    $yaml = Spyc::YAMLLoad('Bn.yaml');
    return $yaml;
}
