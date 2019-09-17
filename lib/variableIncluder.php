<?php 

foreach($GLOBALS['PREFIX_ARRAY'][LOD_CONFIG] as $prefix => $value){
    $$prefix = $value;
}

foreach(properties as $property => $pId){
    $property = ucwords($property);
    $property = str_replace(",", "", $property);
    $property = str_replace(" ", "", $property);
    $property = lcfirst($property);
    $$property = $pId;
}

foreach(classes as $class => $qId){
    $class = ucwords($class);
    $property = str_replace(",", "", $property);
    $class = str_replace(" ", "", $class);
    $class = lcfirst($class);
    $$class = $qId;
}