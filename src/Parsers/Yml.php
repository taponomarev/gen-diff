<?php

namespace Differ\Parsers\Yml;

use Symfony\Component\Yaml\Yaml;

function parse(string $content): object
{
    return (object) Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
}
