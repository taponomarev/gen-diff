<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

const EXT_JSON = 'json';
const EXT_YAML = 'yml';

function parseFile($fileToPath)
{
    $extension = pathinfo($fileToPath, PATHINFO_EXTENSION);
    switch ($extension) {
        case EXT_JSON:
            $fileData = file_get_contents($fileToPath);
            return (object) json_decode($fileData);
        case EXT_YAML:
            return (object) Yaml::parseFile($fileToPath, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            break;
    }
}
