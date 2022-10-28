<?php

function require_dir(string $path)
{
    if (!is_dir($path)) return;
    if ($dh = opendir($path)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != '.' && $file != '..') require $path . '/' . $file;
        }
        closedir($dh);
    }
}
