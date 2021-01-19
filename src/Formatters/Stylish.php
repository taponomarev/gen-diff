<?php

namespace Differ\Formatters\Stylish;

const INDENT_DOUBLE = '    ';
const INDENT_DEFAULT = '  ';
const INDENT_ADD = '+ ';
const INDENT_REMOVE = '- ';

use const Differ\Differ\DIFF_TYPE_ADDED;
use const Differ\Differ\DIFF_TYPE_OBJECT;
use const Differ\Differ\DIFF_TYPE_REMOVED;
use const Differ\Differ\DIFF_TYPE_UNCHANGED;
use const Differ\Differ\DIFF_TYPE_UPDATED;
use const Differ\Differ\PROPERTY_DIFF_KEY;
use const Differ\Differ\PROPERTY_DIFF_OBJECT_CHILDREN;
use const Differ\Differ\PROPERTY_DIFF_TYPE;
use const Differ\Differ\PROPERTY_NEW_VALUE;
use const Differ\Differ\PROPERTY_OLD_VALUE;

function buildFormat(array $tree): string
{
    return "{\n" . buildFormatTree($tree, 1) . "}";
}

function buildFormatTree(array $tree, int $depth): string
{
    $indent = str_repeat(INDENT_DOUBLE, $depth - 1) . INDENT_DEFAULT;
    $collection = collect($tree);
    $sortedTree = $collection->sortBy('key')->toArray();

    $filteredDiffData = array_map(function ($node) use ($indent, $depth): string {
        return getDiffData($node, $indent, $depth) . "\n";
    }, $sortedTree);

    return implode('', $filteredDiffData);
}

/**
 * @param mixed $node
 * @param string $indent
 * @param int $depth
 * @return string
 */
function getDiffData($node, string $indent, int $depth): string
{
    switch ($node[PROPERTY_DIFF_TYPE]) {
        case DIFF_TYPE_ADDED:
            return $indent . INDENT_ADD . $node[PROPERTY_DIFF_KEY] . ': '
                . formatValue($node[PROPERTY_NEW_VALUE], $depth);
        case DIFF_TYPE_REMOVED:
            return $indent . INDENT_REMOVE . $node[PROPERTY_DIFF_KEY] . ': '
            . formatValue($node[PROPERTY_OLD_VALUE], $depth);
        case DIFF_TYPE_UNCHANGED:
            return $indent . INDENT_DEFAULT . $node[PROPERTY_DIFF_KEY] . ': '
            . formatValue($node[PROPERTY_OLD_VALUE], $depth);
        case DIFF_TYPE_UPDATED:
            return $indent . INDENT_REMOVE . $node[PROPERTY_DIFF_KEY] . ': '
                . formatValue($node[PROPERTY_OLD_VALUE], $depth) . "\n"
                . $indent . INDENT_ADD . $node[PROPERTY_DIFF_KEY] . ': '
                . formatValue($node[PROPERTY_NEW_VALUE], $depth);
        case DIFF_TYPE_OBJECT:
            return $indent . INDENT_DEFAULT . $node[PROPERTY_DIFF_KEY]
                . ': ' . "{\n"
                . buildFormatTree($node[PROPERTY_DIFF_OBJECT_CHILDREN], $depth + 1)
                . $indent . INDENT_DEFAULT . "}";
        default:
            throw new \Exception("This type '{$node[PROPERTY_DIFF_TYPE]}' for format 'Stylish' is not supported");
    }
}

/**
 * @param mixed $value
 * @param int $depth
 * @return string
 */
function formatValue($value, int $depth): string
{
    if (is_array($value)) {
        return '[' . implode(', ', $value) . ']';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_object($value)) {
        $indent = str_repeat(INDENT_DOUBLE, $depth);
        $keys = array_keys((array) $value);

        return "{\n" . array_reduce($keys, function ($acc, $key) use ($indent, $value, $depth): string {
            return $acc . $indent . INDENT_DOUBLE . $key . ': '
                . formatValue($value->$key, $depth + 1) . "\n";
        }, '') . $indent . "}";
    }

    return (string) $value;
}
