<?php

namespace Differ\Parsers\Json;

function parse(string $filePath): object
{
    $fileData = file_get_contents($filePath);
    return (object) json_decode($fileData);
}
