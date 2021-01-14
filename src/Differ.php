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
const PROPERTY_DIFF_OBJECT_CHILDREN = 'object_children';
const INDENT_DOUBLE = '    ';
const INDENT_DEFAULT = '  ';
const INDENT_ONE = ' ';
const INDENT_ADD = '+ ';
const INDENT_REMOVE = '- ';
const INDENT_COLON = ': ';

use function Differ\Parsers\parseFile;
use function Differ\Formatters\format;

function genDiff($pathToFile1, $pathToFile2, $format)
{
    $firstFile = parseFile($pathToFile1);
    $secondFile = parseFile($pathToFile2);
    $ast = manageDiff($firstFile, $secondFile);
    return format($ast, $format);
}

function manageDiff(object $firstFile, object $secondFile)
{
    $mergedKeys = array_merge(array_keys((array) $firstFile), array_keys((array) $secondFile));
    $keys = array_unique($mergedKeys);
    sort($keys);

    return array_map(function ($key) use ($firstFile, $secondFile) {
        if (!property_exists($firstFile, $key)) {
            return [
                PROPERTY_DIFF_KEY => $key,
                PROPERTY_DIFF_TYPE => DIFF_TYPE_ADDED,
                PROPERTY_NEW_VALUE => $secondFile->$key
            ];
        } elseif (!property_exists($secondFile, $key)) {
            return [
                PROPERTY_DIFF_KEY => $key,
                PROPERTY_DIFF_TYPE => DIFF_TYPE_REMOVED,
                PROPERTY_OLD_VALUE => $firstFile->$key
            ];
        } elseif ($firstFile->$key === $secondFile->$key) {
            return [
                PROPERTY_DIFF_KEY => $key,
                PROPERTY_DIFF_TYPE => DIFF_TYPE_UNCHANGED,
                PROPERTY_OLD_VALUE => $secondFile->$key,
            ];
        } elseif (is_object($firstFile->$key) && is_object($secondFile->$key)) {
            return [
                PROPERTY_DIFF_KEY => $key,
                PROPERTY_DIFF_TYPE => DIFF_TYPE_OBJECT,
                PROPERTY_DIFF_OBJECT_CHILDREN => manageDiff($firstFile->$key, $secondFile->$key)
            ];
        }

        return [
            PROPERTY_DIFF_KEY => $key,
            PROPERTY_DIFF_TYPE => DIFF_TYPE_UPDATED,
            PROPERTY_OLD_VALUE => $firstFile->$key,
            PROPERTY_NEW_VALUE => $secondFile->$key
        ];
    }, $keys);
}
