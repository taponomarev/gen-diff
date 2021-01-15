<?php

namespace Differ\Parsers;

use function Differ\Parsers\Json\parse as parseJson;
use function Differ\Parsers\Yml\parse as parseYml;

const EXT_JSON = 'json';
const EXT_YAML = 'yml';

function parseFile(string $filePath): object
{
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    $formatToMap = [
        EXT_JSON => fn($filePath) => parseJson($filePath),
        EXT_YAML => fn($filePath) => parseYml($filePath)
    ];

    if (!isset($formatToMap[$extension])) {
        throw new \Exception("This extension '{$extension}' is not supported");
    }

    if (isFileReadable($filePath)) {
        throw new \Exception("This file '{$filePath}' is not readable");
    }

    if (file_get_contents($filePath) === false) {
        throw new \Exception("This filepath '{$filePath}' is not readable");
    }

    return $formatToMap[$extension]($filePath);
}

function isFileReadable(string $filePath): bool
{
    return !is_file($filePath) && !is_readable($filePath);
}
