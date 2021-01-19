<?php

namespace Differ\Formatters\Json;

function buildFormat(array $tree): string
{
    return (string) json_encode($tree, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
