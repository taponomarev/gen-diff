<?php

namespace Differ\Parsers\Yml;

use Symfony\Component\Yaml\Yaml;

function parse(string $filePath): object
{
    return (object) Yaml::parseFile($filePath, Yaml::PARSE_OBJECT_FOR_MAP);
}
