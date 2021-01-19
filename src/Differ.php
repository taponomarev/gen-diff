<?php

namespace Differ\Differ;

const DIFF_TYPE_ADDED = 'added';
const DIFF_TYPE_REMOVED = 'removed';
const DIFF_TYPE_UPDATED = 'update';
const DIFF_TYPE_OBJECT = 'object';
const DIFF_TYPE_UNCHANGED = 'unchanged';
const PROPERTY_NEW_VALUE = 'new_value';
const PROPERTY_OLD_VALUE = 'old_value';
const PROPERTY_DIFF_KEY = 'key';
const PROPERTY_DIFF_TYPE = 'type';
const PROPERTY_DIFF_OBJECT_CHILDREN = 'children';
const DIFF_DEFAULT_FORMAT = 'stylish';
const INDENT_NEW_LINE = "\n";

use function Differ\Parsers\parseFile;
use function Differ\Formatters\format;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = DIFF_DEFAULT_FORMAT): string
{
    $firstData = parseFile($pathToFile1);
    $secondData = parseFile($pathToFile2);
    $diffAst = genDiffTree($firstData, $secondData);
    return format($diffAst, $format);
}

function genDiffTree(object $firstData, object $secondData): array
{
    $mergedKeys = array_merge(array_keys((array) $firstData), array_keys((array) $secondData));
    $keys = array_values(array_unique($mergedKeys));

    return array_map(function (string $key) use ($firstData, $secondData): array {
        if (!property_exists($firstData, $key)) {
            return [
                PROPERTY_DIFF_KEY => $key,
                PROPERTY_DIFF_TYPE => DIFF_TYPE_ADDED,
                PROPERTY_NEW_VALUE => $secondData->$key
            ];
        } elseif (!property_exists($secondData, $key)) {
            return [
                PROPERTY_DIFF_KEY => $key,
                PROPERTY_DIFF_TYPE => DIFF_TYPE_REMOVED,
                PROPERTY_OLD_VALUE => $firstData->$key
            ];
        } elseif ($firstData->$key === $secondData->$key) {
            return [
                PROPERTY_DIFF_KEY => $key,
                PROPERTY_DIFF_TYPE => DIFF_TYPE_UNCHANGED,
                PROPERTY_OLD_VALUE => $secondData->$key,
            ];
        } elseif (is_object($firstData->$key) && is_object($secondData->$key)) {
            return [
                PROPERTY_DIFF_KEY => $key,
                PROPERTY_DIFF_TYPE => DIFF_TYPE_OBJECT,
                PROPERTY_DIFF_OBJECT_CHILDREN => genDiffTree($firstData->$key, $secondData->$key)
            ];
        }

        return [
            PROPERTY_DIFF_KEY => $key,
            PROPERTY_DIFF_TYPE => DIFF_TYPE_UPDATED,
            PROPERTY_OLD_VALUE => $firstData->$key,
            PROPERTY_NEW_VALUE => $secondData->$key
        ];
    }, $keys);
}
