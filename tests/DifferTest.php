<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

/**
 * @covers Differ\Tests\DifferTest
 * @covers Differ\Differ\genDiff
 * * @covers Differ\Differ\genDiffThree
 * @covers Differ\Parsers\parseFile
 * @covers Differ\Parsers\Json\parse
 * @covers Differ\Parsers\Yml\parse
 * @covers Differ\Parsers\isFileReadable
 * @covers Differ\Formatters\format
 * @covers Differ\Formatters\Stylish\buildFormat
 * @covers Differ\Formatters\Stylish\buildFormatThree
 * @covers Differ\Formatters\Stylish\formatValue
 * @covers Differ\Formatters\Plain\formatValue
 * @covers Differ\Formatters\Plain\buildFormat
 * @covers Differ\Formatters\Plain\buildFormatThree
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

    public function testGenDiffInvalidFormat(): void
    {
        $format = 'invalid';
        $this->expectExceptionMessage("This format '{$format}' is not supported");

        genDiff(
            $this->generateFixturePath('file1.json'),
            $this->generateFixturePath('file2.json'),
            $format
        );
    }

    public function testGenDiffInvalidExtension()
    {
        $this->expectExceptionMessage("This extension 'txt' is not supported");
        genDiff(
            $this->generateFixturePath('invalid_extension.txt'),
            $this->generateFixturePath('file2.json'),
        );
    }

    public function testGenDiffIsNotReadableFile()
    {
        $actual = $this->generateFixturePath('file3.json');
        $this->expectExceptionMessage("This file '{$actual}' is not readable");

        genDiff(
            $this->generateFixturePath('file1.json'),
            $actual,
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
            ['file1.json', 'file2.json', 'plain', 'plain.txt'],
            ['file1.yml', 'file2.yml', 'plain', 'plain.txt']
        ];
    }
}
