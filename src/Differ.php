<?php

namespace Differ\Differ;

use function Differ\Parser\parseFile;

function genDiff($pathToFile1, $pathToFile2)
{
    $fileData1 = parseFile($pathToFile1);
    $fileData2 = parseFile($pathToFile2);

    $keys = array_merge(array_keys($fileData1), array_keys($fileData2));
    $keys = array_unique($keys);
    sort($keys);
    $ast = [];

    foreach ($keys as $key) {
        if (!array_key_exists($key, $fileData1)) {
            $ast["+{$key}"] = $fileData2[$key];
        } elseif (!array_key_exists($key, $fileData2)) {
            $ast["-{$key}"] = $fileData1[$key];
        } elseif ($fileData1[$key] !== $fileData2[$key]) {
            $ast["-{$key}"] = $fileData1[$key];
            $ast["+{$key}"] = $fileData2[$key];
        } else {
            $ast["{$key}"] = $fileData1[$key];
        }
    }

    return \json_encode($ast, JSON_PRETTY_PRINT) . PHP_EOL;
}
