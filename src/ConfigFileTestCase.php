<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     14/05/2018
// Time:     17:54
// Project:  ConfigFileUnitTest
//
declare(strict_types=1);
namespace CodeInc\ConfigFileUnitTest;
use CodeInc\ConfigFileUnitTest\Tests\ConfigFileTest;
use PHPUnit\Framework\TestCase;


/**
 * Class ConfigFileTestCase
 *
 * @see ConfigFileTest
 * @package CodeInc\ConfigFileUnitTest
 * @author  Joan Fabrégat <joan@codeinc.fr>
 * @license MIT <https://github.com/CodeIncHQ/ConfigFileUnitTest/blob/master/LICENSE>
 * @link https://github.com/CodeIncHQ/ConfigFileUnitTest
 */
abstract class ConfigFileTestCase extends TestCase
{
    /**
     * Assets that the $testFilePath has all the constants of the $srcFilePath.
     *
     * @param string $testFilePath
     * @param string $srcFilePath
     */
    protected static function assetFileHasFileConstants(string $testFilePath, string $srcFilePath):void
    {
        $testConstants = self::parseFileConstants($testFilePath);
        foreach (self::parseFileConstants($srcFilePath) as $constant) {
            self::assertContains($constant, $testConstants,
                sprintf('The config file %s does not contain the constant %s from the config file %s',
                    $testFilePath, $constant, $srcFilePath));
        }
    }

    /**
     * Asserts that a file has the constants listed in the $constants directory.
     *
     * @param string $filePath
     * @param array  $constants
     */
    protected static function assetFileHasConstants(string $filePath, array $constants):void
    {
        $testConstants = self::parseFileConstants($filePath);
        foreach ($constants as $constant) {
            self::assertContains($constant, $testConstants,
                sprintf('The config file %s does not contain the constant %s',
                    $filePath, $constant));
        }
    }

    /**
     * Parses all the constants contains in a file (the script can detect the constants defined
     * using 'const' and 'define').
     *
     * @param string $filePath
     * @return array
     */
    protected static function parseFileConstants(string $filePath):array
    {
        self::assertFileExists($filePath,
            sprintf('The config file \'%s\' does not exist', $filePath));
        self::assertIsReadable($filePath,
            sprintf('The config file \'%s\' is not readable', $filePath));

        $constants = [];
        if (($f = fopen($filePath, 'r')) !== false) {
            while (($line = fgets($f)) !== false) {
                if (preg_match('/const\s+([\w_]+)\s+=/ui', $line, $matches)) {
                    $constants[] = $matches[1];
                }
                else if (preg_match('/define\s*\\(\s*[\'"]\s*([\w_]+)\s*[\'"]\s*,/ui', $line, $matches)) {
                    $constants[] = $matches[1];
                }
            }
            fclose($f);
        }

        self::assertNotEmpty($constants,
            sprintf('The config file \'%s\' does not contain any constant', $filePath));

        return $constants;
    }

    /**
     * Assert that a file does contain a constant.
     *
     * @param string $filePath
     * @param string $constant
     */
    public static function assetFileHasConstant(string $filePath, string $constant):void
    {
        self::assertContains($constant, self::parseFileConstants($filePath),
            sprintf('The config file \'%s\' does not contain the constant \'%s\'', $filePath, $constant));
    }

    /**
     * Assert that a file does not contain a constant.
     *
     * @param string $filePath
     * @param string $constant
     */
    public static function assetFileNotHasConstant(string $filePath, string $constant):void
    {
        self::assertNotContains($constant, self::parseFileConstants($filePath),
            sprintf('The config file \'%s\' does contain the constant \'%s\'', $filePath, $constant));
    }
}