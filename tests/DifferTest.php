<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

/**
 * @covers Differ\Tests\DifferTest
 * @covers Differ\Differ\genDiff
 * @covers Differ\Differ\genDiffTree
 * @covers Differ\Parsers\parseFile
 * @covers Differ\Parsers\Json\parse
 * @covers Differ\Parsers\Yml\parse
 * @covers Differ\Parsers\isFileReadable
 * @covers Differ\Formatters\format
 * @covers Differ\Formatters\Stylish\buildFormat
 * @covers Differ\Formatters\Stylish\buildFormatTree
 * @covers Differ\Formatters\Stylish\getDiffData
 * @covers Differ\Formatters\Stylish\formatValue
 * @covers Differ\Formatters\Plain\formatValue
 * @covers Differ\Formatters\Plain\buildFormat
 * @covers Differ\Formatters\Plain\buildFormatTree
 * @covers Differ\Formatters\Plain\getDiffData
 * @covers Differ\Formatters\Json\buildFormat
 */

class DifferTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     * @param string $filename1
     * @param string $filename2
     * @param string $format
     * @param string $diffFilename
     */
    public function testGenDiffSuccess(string $filename1, string $filename2, string $format, string $diffFilename): void
    {
        $actualString = genDiff(
            $this->generateFixturePath($filename1),
            $this->generateFixturePath($filename2),
            $format
        );
        $this->assertStringEqualsFile(
            $this->generateFixturePath($diffFilename),
            $actualString
        );
    }

    public function testGenDiffSuccessWithDefaultFormat(): void
    {
        $actualString = genDiff(
            $this->generateFixturePath('file1.json'),
            $this->generateFixturePath('file2.json'),
        );
        $this->assertStringEqualsFile(
            $this->generateFixturePath('stylish.txt'),
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

    public function testGenDiffInvalidExtension(): void
    {
        $this->expectExceptionMessage("This extension 'txt' is not supported");
        genDiff(
            $this->generateFixturePath('invalid_extension.txt'),
            $this->generateFixturePath('file2.json'),
        );
    }

    public function testGenDiffIsNotReadableFile(): void
    {
        $actual = $this->generateFixturePath('file3.json');
        $this->expectExceptionMessage("This file '{$actual}' is not readable");

        genDiff(
            $this->generateFixturePath('file1.json'),
            $actual,
        );
    }

    /**
     * @param string $filename
     * @return string
     */
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
            ['file1.yml', 'file2.yml', 'plain', 'plain.txt'],
            ['file1.json', 'file2.json', 'json', 'json.txt'],
            ['file1.yml', 'file2.yml', 'json', 'json.txt'],
            ['file1.yaml', 'file2.yaml', 'stylish', 'stylish.txt'],
            ['file1.yaml', 'file2.yaml', 'plain', 'plain.txt'],
            ['file1.yaml', 'file2.yaml', 'json', 'json.txt'],
        ];
    }
}
