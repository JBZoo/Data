<?php
/**
 * Data
 *
 * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.
 *
 * @package   Data
 * @author    Denis Smetannikov <denis@jbzoo.com>
 * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>
 * @link      http://github.com/SmetDenis/Data
 */

namespace SmetDenis\Data;

/**
 * Class Exception
 * @package SmetDenis\Data
 */
class CodeStyleTest extends PHPUnit
{

    protected $le = "\n";

    protected $validHeader = array(
        '<?php',
        '/**',
        ' * Data',
        ' *',
        ' * Copyright (c) 2015, Denis Smetannikov <denis@jbzoo.com>.',
        ' *',
        ' * @package   Data',
        ' * @author    Denis Smetannikov <denis@jbzoo.com>',
        ' * @copyright 2015 Denis Smetannikov <denis@jbzoo.com>',
        ' * @link      http://github.com/SmetDenis/Data',
        ' */',
        '',
        'namespace SmetDenis\Data;',
        '',
        '/**',
    );

    public function testLineEndings()
    {
        $files = $this->getFileList(ROOT_PATH);

        foreach ($files as $file) {
            $content = $this->openFile($file);
            self::assertNotContains("\r", $content);
        }
    }

    public function testHeaders()
    {
        $this->excludeList[] = 'autoload.php';
        $this->excludeList[] = 'demo.php';

        $files = $this->getFileList(ROOT_PATH, '#\.php$#i');
        $valid = implode($this->validHeader, $this->le);

        foreach ($files as $file) {
            $content = $this->openFile($file);

            self::assertContains($valid, $content, 'File has no valid header: ' . $file);
        }
    }

    public function testCyrillic()
    {
        $files = $this->getFileList(ROOT_PATH . '/src', '#\.php$#i');

        foreach ($files as $file) {
            $content = $this->openFile($file);

            self::assertEquals(0, preg_match('/[А-Яа-яЁё]/u', $content), 'File has no valid chars: ' . $file);
        }
    }
}
