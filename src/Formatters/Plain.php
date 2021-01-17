<?php

namespace Differ\Formatters\Plain;

use const Differ\Differ\DIFF_TYPE_ADDED;
use const Differ\Differ\DIFF_TYPE_OBJECT;
use const Differ\Differ\DIFF_TYPE_REMOVED;
use const Differ\Differ\DIFF_TYPE_UPDATED;
use const Differ\Differ\PROPERTY_DIFF_KEY;
use const Differ\Differ\PROPERTY_DIFF_OBJECT_CHILDREN;
use const Differ\Differ\PROPERTY_DIFF_TYPE;
use const Differ\Differ\PROPERTY_NEW_VALUE;
use const Differ\Differ\PROPERTY_OLD_VALUE;

function buildFormat(array $tree): string
{
    return rtrim(buildFormatTree($tree, ''));
}

function buildFormatTree(array $tree, string $key): string
{
    $formatKeysMap = fn($key, $node) => $key ? "{$key}.{$node[PROPERTY_DIFF_KEY]}" : $node[PROPERTY_DIFF_KEY];
    $formatterMap = [
        DIFF_TYPE_ADDED => fn($node) => "Property '{$formatKeysMap($key, $node)}' was added with value: "
            . formatValue($node[PROPERTY_NEW_VALUE]),

        DIFF_TYPE_REMOVED => fn($node) => "Property '{$formatKeysMap($key, $node)}' was removed",

        DIFF_TYPE_UPDATED => fn($node) => "Property '{$formatKeysMap($key, $node)}' was updated. From "
            . formatValue($node[PROPERTY_OLD_VALUE]) . " to "
            . formatValue($node[PROPERTY_NEW_VALUE]),

        DIFF_TYPE_OBJECT => fn($node) => buildFormatTree(
            $node[PROPERTY_DIFF_OBJECT_CHILDREN],
            $formatKeysMap($key, $node)
        )
    ];

    $collection = collect($tree);
    $sortedTree = $collection->sortBy('key')->toArray();

    $formattersData = array_map(function ($node) use ($formatterMap): string {
        if (isset($formatterMap[$node[PROPERTY_DIFF_TYPE]])) {
            return trim($formatterMap[$node[PROPERTY_DIFF_TYPE]]($node)) . PHP_EOL;
        }
        return '';
    }, $sortedTree);

    return implode('', $formattersData);
}

function formatValue($value): string
{
    if (is_array($value) || is_object($value)) {
        return '[complex value]';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_integer($value)) {
        return (string) $value;
    }

    return "'{$value}'";
}
