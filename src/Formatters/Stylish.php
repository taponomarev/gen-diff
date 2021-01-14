<?php

namespace Differ\Formatters\Stylish;

use const Differ\Differ\DIFF_TYPE_ADDED;
use const Differ\Differ\DIFF_TYPE_OBJECT;
use const Differ\Differ\DIFF_TYPE_REMOVED;
use const Differ\Differ\DIFF_TYPE_UNCHANGED;
use const Differ\Differ\DIFF_TYPE_UPDATED;
use const Differ\Differ\INDENT_ADD;
use const Differ\Differ\INDENT_COLON;
use const Differ\Differ\INDENT_DEFAULT;
use const Differ\Differ\INDENT_DOUBLE;
use const Differ\Differ\INDENT_REMOVE;
use const Differ\Differ\PROPERTY_DIFF_KEY;
use const Differ\Differ\PROPERTY_DIFF_OBJECT_CHILDREN;
use const Differ\Differ\PROPERTY_DIFF_TYPE;
use const Differ\Differ\PROPERTY_NEW_VALUE;
use const Differ\Differ\PROPERTY_OLD_VALUE;

function buildFormat(array $three)
{
    return "{\n" . buildFormatThree($three, 1) . "}\n";
}

function buildFormatThree(array $three, int $depth): string
{
    $indent = str_repeat(INDENT_DOUBLE, $depth - 1) . INDENT_DEFAULT;
    $formatterMap = [
        DIFF_TYPE_ADDED => fn($node) => $indent . INDENT_ADD . $node[PROPERTY_DIFF_KEY] . INDENT_COLON
            . formatValue($node[PROPERTY_NEW_VALUE], $depth),

        DIFF_TYPE_REMOVED => fn($node) => $indent . INDENT_REMOVE . $node[PROPERTY_DIFF_KEY] . INDENT_COLON
            . formatValue($node[PROPERTY_OLD_VALUE], $depth),

        DIFF_TYPE_UNCHANGED => fn($node) => $indent . INDENT_DEFAULT . $node[PROPERTY_DIFF_KEY] . INDENT_COLON
            . formatValue($node[PROPERTY_OLD_VALUE], $depth),

        DIFF_TYPE_UPDATED => fn($node) => $indent . INDENT_REMOVE . $node[PROPERTY_DIFF_KEY] . INDENT_COLON
            . formatValue($node[PROPERTY_OLD_VALUE], $depth) . "\n"
            . $indent . INDENT_ADD . $node[PROPERTY_DIFF_KEY] . INDENT_COLON
            . formatValue($node[PROPERTY_NEW_VALUE], $depth),

        DIFF_TYPE_OBJECT => fn($node) => $indent . INDENT_DEFAULT . $node[PROPERTY_DIFF_KEY] . INDENT_COLON . "{\n"
            . buildFormatThree($node[PROPERTY_DIFF_OBJECT_CHILDREN], $depth + 1)
            . $indent . INDENT_DEFAULT . "}"
    ];

    $formattersData = array_map(function ($node) use ($formatterMap) {
        return $formatterMap[$node[PROPERTY_DIFF_TYPE]]($node) . "\n";
    }, $three);

    return implode('', $formattersData);
}

function formatValue($value, $depth): string
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

        return "{\n" . array_reduce($keys, function ($acc, $key) use ($indent, $value, $depth) {
            return $acc . $indent . INDENT_DOUBLE . $key . ': ' . formatValue($value->$key, $depth + 1) . "\n";
        }, '') . $indent . "}";
    }

    return (string) $value;
}
