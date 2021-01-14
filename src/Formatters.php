<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\buildFormat as formatStylish;

function format(array $three, string $format)
{
    $formatsMap = [
        'stylish' => fn($three) => formatStylish($three)
    ];

    if (!isset($formatsMap[$format])) {
        throw new \Exception("This format '{$format}' not supported");
    }

    return $formatsMap[$format]($three);
}
