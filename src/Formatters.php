<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\buildFormat as formatStylish;
use function Differ\Formatters\Plain\buildFormat as formatPlain;

function format(array $three, string $format)
{
    $formatsMap = [
        'stylish' => fn($three) => formatStylish($three),
        'plain' => fn($three) => formatPlain($three)
    ];

    if (!isset($formatsMap[$format])) {
        throw new \Exception("This format '{$format}' is not supported");
    }

    return $formatsMap[$format]($three);
}
