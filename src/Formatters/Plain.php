<?php

namespace Differ\Formatters\Plain;

use const Differ\Differ\DIFF_TYPE_ADDED;
use const Differ\Differ\DIFF_TYPE_OBJECT;
use const Differ\Differ\DIFF_TYPE_REMOVED;
use const Differ\Differ\DIFF_TYPE_UPDATED;
use const Differ\Differ\INDENT_NEW_LINE;
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
    $collection = collect($tree);
    $sortedTree = $collection->sortBy('key')->toArray();

    $filteredDiffData = array_map(function ($node) use ($key): string {
        $formattedKey = $key != '' ? "{$key}.{$node[PROPERTY_DIFF_KEY]}" : $node[PROPERTY_DIFF_KEY];
        if (getDiffData($node, $formattedKey) != '') {
            return trim(getDiffData($node, $formattedKey)) . INDENT_NEW_LINE;
        }
        return '';
    }, $sortedTree);

    return implode('', $filteredDiffData);
}

/**
 * @param mixed $node
 * @param string $key
 * @return string
 */
function getDiffData($node, string $key): string
{
    switch ($node[PROPERTY_DIFF_TYPE]) {
        case DIFF_TYPE_ADDED:
            return "Property '{$key}' was added with value: "
                . formatValue($node[PROPERTY_NEW_VALUE]);
        case DIFF_TYPE_REMOVED:
            return "Property '{$key}' was removed";
        case DIFF_TYPE_UPDATED:
            return "Property '{$key}' was updated. From "
                . formatValue($node[PROPERTY_OLD_VALUE]) . " to "
                . formatValue($node[PROPERTY_NEW_VALUE]);
        case DIFF_TYPE_OBJECT:
            return buildFormatTree($node[PROPERTY_DIFF_OBJECT_CHILDREN], $key);
        default:
            return '';
    }
}

/**
 * @param mixed $value
 * @return string
 */
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
