<?php

namespace Differ\Parsers;

use function Differ\Parsers\Json\parse as parseJson;
use function Differ\Parsers\Yml\parse as parseYml;

const EXT_JSON = 'json';
const EXT_YML = 'yml';
const EXT_YAML = 'yaml';

function parseFile(string $filePath): object
{
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    $ExtensionsMap = [
        EXT_JSON => fn($content) => parseJson($content),
        EXT_YML => fn($content) => parseYml($content),
        EXT_YAML => fn($content) => parseYml($content)
    ];

    if (!isset($ExtensionsMap[$extension])) {
        throw new \Exception("This extension '{$extension}' is not supported");
    }

    if (!isFileReadable($filePath)) {
        throw new \Exception("This file '{$filePath}' is not readable");
    }

    $content = file_get_contents($filePath);

    if ($content === false) {
        throw new \Exception("This filepath '{$filePath}' is not readable");
    }

    return $ExtensionsMap[$extension]($content);
}

function isFileReadable(string $filePath): bool
{
    return is_file($filePath) && is_readable($filePath);
}
