<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\buildFormat as formatStylish;
use function Differ\Formatters\Plain\buildFormat as formatPlain;
use function Differ\Formatters\Json\buildFormat as formatJson;

function format(array $tree, string $format): string
{
    $formatsMap = [
        'stylish' => fn($tree) => formatStylish($tree),
        'plain' => fn($tree) => formatPlain($tree),
        'json' => fn($tree) => formatJson($tree)
    ];

    if (!isset($formatsMap[$format])) {
        throw new \Exception("This format '{$format}' is not supported");
    }

    return $formatsMap[$format]($tree);
}
