<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\buildFormat as formatStylish;
use function Differ\Formatters\Plain\buildFormat as formatPlain;
use function Differ\Formatters\Json\buildFormat as formatJson;

function format(array $three, string $format): string
{
    $formatsMap = [
        'stylish' => fn($three) => formatStylish($three),
        'plain' => fn($three) => formatPlain($three),
        'json' => fn($three) => formatJson($three)
    ];

    if (!isset($formatsMap[$format])) {
        throw new \Exception("This format '{$format}' is not supported");
    }

    return $formatsMap[$format]($three);
}
