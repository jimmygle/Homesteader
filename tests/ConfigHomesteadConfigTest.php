<?php
/**
 * Created by PhpStorm.
 * User: jimmygle
 * Date: 3/8/15
 * Time: 8:12 PM
 */

use org\bovigo\vfs\vfsStreamWrapper;
use org\bovigo\vfs\vfsStreamDirectory;

class ConfigHomesteadConfigTest {

    public function setUp()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory($_SERVER['HOME'] . DIRECTORY_SEPARATOR . './homestead'));
    }

} 