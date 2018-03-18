<?php
/**
 * Pabana : PHP Framework (https://pabana.futurasoft.fr)
 * Copyright (c) FuturaSoft (https://futurasoft.fr)
 *
 * Licensed under BSD-3-Clause License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) FuturaSoft (https://futurasoft.fr)
 * @link          https://pabana.futurasoft.fr Pabana Project
 * @since         1.1
 * @license       https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause License
 */
namespace Pabana\Test\Core;

use PHPUnit\Framework\TestCase;
use Pabana\Core\Configuration;

/**
 * ConfigurationTest
 */
final class ConfigurationTest extends TestCase
{
    /**
     * testBase method
     *
     * @return void
     */
    public function testBase()
    {
        Configuration::registerConstant();
        // Test if constant définition work
        $this->assertEquals(DIRECTORY_SEPARATOR, DS);
        // Test if APP_ROOT isn't defined (is defined after base another registerConstant)
        $this->assertNotTrue(defined('APP_ROOT'));
        Configuration::base();
        Configuration::registerConstant();
        // Test if APP_ROOT exist now
        $this->assertTrue(defined('APP_ROOT'));
    }
    
    /**
     * testCheck method
     *
     * @return void
     */
    public function testCheck()
    {
        $result = Configuration::check('application.encoding');
        $this->assertTrue($result);
        $result = Configuration::check('application.env');
        $this->assertTrue($result);
        $result = Configuration::check('application.toto');
        $this->assertNotTrue($result);
    }
    
    /**
     * testRead method
     *
     * @return void
     */
    public function testRead()
    {
        // Test read of exist key
        $result = Configuration::read('application.encoding');
        $this->assertEquals('UTF8', $result);
        // Test read of exist key
        $result = Configuration::read('application.env');
        $this->assertEquals('dev', $result);
        // Test Exception if Configuration key doesn't exist
        $this->expectException('\Exception');
        $result = Configuration::read('test.read');
    }
    
    /**
     * testWriteAndDelete method
     *
     * @return void
     */
    public function testWriteAndDelete()
    {
        // Test Exception if Configuration key doesn't exist
        //$this->expectException('\Exception');
        //$result = Configuration::delete('test.write_and_delete');
        // Test
        $this->assertTrue(Configuration::write('test.write_and_delete', 'test'));
        $result = Configuration::read('test.write_and_delete');
        $this->assertEquals('test', $result);
        $this->assertTrue(Configuration::delete('test.write_and_delete'));
    }
    
    /**
     * testWriteAndPrepare method
     *
     * @return void
     */
    public function testWriteAndPrepare()
    {
        // Test Exception if Configuration key doesn't exist
        //$this->expectException('\Exception');
        //$result = Configuration::read('test.write_and_prepare');
        // Test change from 'true' to true
        $this->assertTrue(Configuration::write('test.write_and_prepare', 'true'));
        $result = Configuration::read('test.write_and_prepare');
        $this->assertNotSame('true', $result);
        $this->assertSame(true, $result);
        // Test change from 'E_ALL' to E_ALL constant
        $this->assertTrue(Configuration::write('debug.level', 'E_ALL'));
        $result = Configuration::read('debug.level');
        $this->assertNotSame('E_ALL', $result);
        $this->assertSame(E_ALL, $result);
    }
    
    /**
     * testWriteAndRead method
     *
     * @return void
     */
    public function testWriteAndRead()
    {
        // Test Exception if Configuration key doesn't exist
        //$this->expectException('\Exception');
        //$result = Configuration::read('test.write_and_read');
        // Test
        $this->assertTrue(Configuration::write('test.write_and_read', 'test'));
        $result = Configuration::read('test.write_and_read');
        $this->assertEquals('test', $result);
        $this->assertTrue(Configuration::write('test.write_and_read', 'test2'));
        $result = Configuration::read('test.write_and_read');
        $this->assertEquals('test2', $result);
    }
}
