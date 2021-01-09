<?php

namespace Differ\Differ;

use Symfony\Component\Yaml\Yaml;

const DIFF_TYPE_ADDED = 'added';
const DIFF_TYPE_REMOVED = 'removed';
const DIFF_TYPE_UPDATED = 'update';
const DIFF_TYPE_UNCHANGED = 'unchanged';
const PROPERTY_NEW_VALUE = 'new_value';
const PROPERTY_OLD_VALUE = 'old_value';
const PROPERTY_DIFF_KEY = 'key';
const PROPERTY_DIFF_TYPE = 'type';

use function Differ\Parser\parseFile;
use function Differ\Formaters\parseFormat;

function genDiff($pathToFile1, $pathToFile2, $format)
{
    $fileData1 = parseFile($pathToFile1);
    $fileData2 = parseFile($pathToFile2);

    $keys = array_merge(array_keys($fileData1), array_keys($fileData2));
    $keys = array_unique($keys);
    sort($keys);
    $ast = [];

    foreach ($keys as $key) {
        if (!array_key_exists($key, $fileData1)) {
            $ast[] = [
                PROPERTY_DIFF_KEY => $key,
                PROPERTY_DIFF_TYPE => DIFF_TYPE_ADDED,
                PROPERTY_NEW_VALUE => formatValue($fileData2[$key])
            ];
        } elseif (!array_key_exists($key, $fileData2)) {
            $ast[] = [
                PROPERTY_DIFF_KEY => $key,
                PROPERTY_DIFF_TYPE => DIFF_TYPE_REMOVED,
                PROPERTY_OLD_VALUE => formatValue($fileData1[$key])
            ];
        } elseif ($fileData1[$key] !== $fileData2[$key]) {
            $ast[] = [
                PROPERTY_DIFF_KEY => $key,
                PROPERTY_DIFF_TYPE => DIFF_TYPE_UPDATED,
                PROPERTY_OLD_VALUE => formatValue($fileData1[$key]),
                PROPERTY_NEW_VALUE => formatValue($fileData2[$key])
            ];
        } else {
            $ast[] = [
                PROPERTY_DIFF_KEY => $key,
                PROPERTY_DIFF_TYPE => DIFF_TYPE_UNCHANGED,
                PROPERTY_OLD_VALUE => formatValue($fileData1[$key]),
            ];
        }
    }

    return parseFormat($ast, $format);
}

function formatValue($value)
{
    $typesMap = [
        'boolean' => fn($value) => $value ? 'true' : 'false',
    ];
    $variableType = gettype($value);

    if (isset($typesMap[$variableType])) {
        return $typesMap[$variableType]($value);
    }

    return $value;
}
