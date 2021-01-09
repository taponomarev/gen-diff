<?php

namespace Differ\Formaters;

use const Differ\Differ\DIFF_TYPE_ADDED;
use const Differ\Differ\DIFF_TYPE_REMOVED;
use const Differ\Differ\DIFF_TYPE_UNCHANGED;
use const Differ\Differ\DIFF_TYPE_UPDATED;
use const Differ\Differ\PROPERTY_DIFF_KEY;
use const Differ\Differ\PROPERTY_DIFF_TYPE;
use const Differ\Differ\PROPERTY_NEW_VALUE;
use const Differ\Differ\PROPERTY_OLD_VALUE;

function parseFormat($ast, $format)
{
    switch ($format) {
        case 'json':
            return parseJson($ast);
        case 'stylish':
            return parseStylish($ast);
        case 'yml':
            return parseYml($ast);
        default:
            return parseStylish($ast);
    }
}

function parseStylish($ast)
{
    $diff = [];
    foreach ($ast as $item) {
        switch ($item[PROPERTY_DIFF_TYPE]) {
            case DIFF_TYPE_ADDED:
                $diff[] = '+ ' . $item[PROPERTY_DIFF_KEY] . ': ' . $item[PROPERTY_NEW_VALUE];
                break;
            case DIFF_TYPE_UPDATED:
                $diff[] = '- ' . $item[PROPERTY_DIFF_KEY] . ': ' . $item[PROPERTY_OLD_VALUE];
                $diff[] = '+ ' . $item[PROPERTY_DIFF_KEY] . ': ' . $item[PROPERTY_NEW_VALUE];
                break;
            case DIFF_TYPE_REMOVED:
                $diff[] = '- ' . $item[PROPERTY_DIFF_KEY] . ': ' . $item[PROPERTY_OLD_VALUE];
                break;
            case DIFF_TYPE_UNCHANGED:
                $diff[] = '  ' . $item[PROPERTY_DIFF_KEY] . ': ' . $item[PROPERTY_OLD_VALUE];
                break;
        }
    }

    $buildingData = array_reduce($diff, function ($acc, $row) {
        $acc .= "  " . $row . "\n";
        return $acc;
    }, '');

    return "{\n" . $buildingData . "}";
}
