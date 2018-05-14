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
// Time:     18:04
// Project:  ConfigFileUnitTest
//
declare(strict_types=1);
namespace CodeInc\ConfigFileUnitTest\Tests;
use CodeInc\ConfigFileUnitTest\ConfigFileTestCase;


/**
 * Class ConfigFileTest
 *
 * @uses    ConfigFileTestCase
 * @package CodeInc\ConfigFileUnitTest\Tests
 * @author  Joan Fabrégat <joan@codeinc.fr>
 */
class ConfigFileTest extends ConfigFileTestCase
{
    private const CONSTANTS = ['CONST_1', 'CONST_2', 'CONST_3', 'CONST_4', 'CONST_5'];

    /**
     * @return array
     */
    public function testConfigFiles():array
    {
        $configFiles = [];
        foreach (glob(__DIR__.'/assets/*ConfigFile.php') as $configFile) {
            self::assertFileExists($configFile);
            self::assertFileIsReadable($configFile);
            $configFiles[] = $configFile;
        }
        return $configFiles;
    }

    /**
     * @return array
     */
    public function testParseMixConfigFile():array
    {
        return self::parseFileConstants(__DIR__.'/assets/mixConfigFile.php');
    }

    /**
     * @depends testParseMixConfigFile
     * @param array $constants
     */
    public function testMixConfigFileConstants(array $constants):void
    {
        self::assertSame($constants, self::CONSTANTS);
    }

    /**
     * @return array
     */
    public function testParseConstConfigFile():array
    {
        return self::parseFileConstants(__DIR__.'/assets/constConfigFile.php');
    }

    /**
     * @depends testParseConstConfigFile
     * @param array $constants
     */
    public function testConstConfigFileConstants(array $constants):void
    {
        self::assertSame($constants, self::CONSTANTS);
    }

    /**
     * @return array
     */
    public function testParseDefineConfigFile():array
    {
        return self::parseFileConstants(__DIR__.'/assets/defineConfigFile.php');
    }

    /**
     * @depends testParseDefineConfigFile
     * @param array $constants
     */
    public function testDefineConfigFileConstants(array $constants):void
    {
        self::assertSame($constants, self::CONSTANTS);
    }

    /**
     * @depends testConfigFiles
     * @param array $configFiles
     */
    public function testFileHasConstant(array $configFiles):void
    {
        foreach ($configFiles as $configFilePath) {
            foreach (self::CONSTANTS as $constant) {
                $this->assetFileHasConstant($configFilePath, $constant);
            }
        }
    }

    /**
     * @depends testConfigFiles
     * @param array $configFiles
     */
    public function testFileNotHasConstant(array $configFiles):void
    {
        foreach ($configFiles as $configFilePath) {
            for ($i = 6; $i <= 10; $i++) {
                $this->assetFileNotHasConstant($configFilePath, 'CONST_'.$i);
            }
        }
    }

    /**
     * @depends testConfigFiles
     * @param array $configFiles
     */
    public function testFileHasConstants(array $configFiles):void
    {
        foreach ($configFiles as $configFile) {
            self::assetFileHasConstants($configFile, self::CONSTANTS);
        }
    }

    /**
     * @depends testConfigFiles
     * @param array $configFiles
     */
    public function testFileHasFileConstants(array $configFiles):void
    {
        foreach ($configFiles as $configFile1) {
            foreach ($configFiles as $configFile2) {
                if ($configFile1 != $configFile2) {
                    self::assetFileHasFileConstants($configFile1, $configFile2);
                }
            }
        }
    }
}