<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

/**
 * @covers Differ\Tests\DifferTest
 * @covers Differ\Differ\genDiff
 * * @covers Differ\Differ\genDiffThree
 * @covers Differ\Parsers\parseFile
 * @covers Differ\Formatters\format
 * @covers Differ\Formatters\Stylish\buildFormat
 * @covers Differ\Formatters\Stylish\buildFormatThree
 * @covers Differ\Formatters\Stylish\formatValue
 */

class DifferTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     * @param string $filename1
     * @param string $filename2
     * @param string $format
     * @param string $resultFilename
     */
    public function testGenDiffSuccess(string $filename1, string $filename2, string $format, string $resultFilename): void
    {
        $actualString = genDiff(
            $this->generateFixturePath($filename1),
            $this->generateFixturePath($filename2),
            $format
        );
        $this->assertStringEqualsFile(
            $this->generateFixturePath($resultFilename),
            $actualString
        );

    }

    public function generateFixturePath(string $filename): string
    {
        return implode(DIRECTORY_SEPARATOR, ['tests', 'fixtures', $filename]);
    }

    public function additionProvider(): array
    {
        return [
            ['file1.json', 'file2.json', 'stylish', 'stylish.txt'],
            ['file1.yml', 'file2.yml', 'stylish', 'stylish.txt'],
        ];
    }
}
