<?php

function class_auto_loader($classname)
{

    list($namespace, $filename) = explode("\\", $classname);

    if ($namespace == 'App') {
        require_once(__DIR__ . '/' . $filename.".php");
    }
}

spl_autoload_register("class_auto_loader");
