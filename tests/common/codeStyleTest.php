<?php
/**
 * JBZoo Data
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   Data
 * @license   MIT
 * @copyright Copyright (C) JBZoo.com,  All rights reserved.
 * @link      https://github.com/JBZoo/Data
 * @author    Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\Data;

/**
 * Class CodeStyleTest
 * @package JBZoo\Data
 */
class CodeStyleTest extends PHPUnit
{
    protected $le = "\n";

    protected $replace = array(
        '__LINK__'       => 'https://github.com/JBZoo/__PACKAGE__',
        '__NAMESPACE__'  => 'JBZoo\__PACKAGE__',
        '__PACKAGE__'    => 'Data',
        '__AUTHOR__'     => 'Denis Smetannikov <denis@jbzoo.com>',
        '__LICENSE__'    => 'MIT',
        '__COPYRIGHTS__' => 'Copyright (C) JBZoo.com,  All rights reserved.',
    );

    protected $validHeader = array(
        '<?php',
        '/**',
        ' * JBZoo __PACKAGE__',
        ' *',
        ' * This file is part of the JBZoo CCK package.',
        ' * For the full copyright and license information, please view the LICENSE',
        ' * file that was distributed with this source code.',
        ' *',
        ' * @package   __PACKAGE__',
        ' * @license   __LICENSE__',
        ' * @copyright __COPYRIGHTS__',
        ' * @link      __LINK__',
    );

    protected $excludeList = array(
        '.',
        '..',
        '.idea',
        '.git',
        'build',
        'vendor',
        'composer.phar',
        'composer.lock',
    );

    /**
     * Render copyrights
     * @param $text
     * @return mixed
     */
    protected function replaceCopyright($text)
    {
        foreach ($this->replace as $const => $value) {
            $text = str_replace($const, $value, $text);
        }

        return $text;
    }

    /**
     * Test line endings
     */
    public function testFiles()
    {
        $files = $this->getFileList(ROOT_PATH, '[/\\\\](src|tests)[/\\\\].*\.php$');

        foreach ($files as $file) {
            $content = $this->openFile($file);
            self::assertNotContains("\r", $content);
        }
    }

    /**
     * Test copyright headers
     */
    public function testHeaders()
    {
        $files = $this->getFileList(ROOT_PATH, '[/\\\\](src|tests)[/\\\\].*\.php$');

        foreach ($files as $file) {
            $content = $this->openFile($file);

            // build copyrights
            $validHeader = $this->validHeader;
            if (isset($this->replace['__AUTHOR__'])) {
                $validHeader[] = ' * @author    __AUTHOR__';
            }
            $validHeader[] = ' */';

            $namespace = $this->replaceCopyright('namespace __NAMESPACE__;');
            if (strpos($content, $namespace)) {
                $validHeader[] = '';
                $validHeader[] = 'namespace __NAMESPACE__;';
                $validHeader[] = '';
            }

            $valid = $this->replaceCopyright(implode($validHeader, $this->le));
            self::assertContains($valid, $content, 'File has no valid header: ' . $file);
        }
    }

    /**
     * Try to find cyrilic symbols in the code
     */
    public function testCyrillic()
    {
        $files = $this->getFileList(ROOT_PATH, '/src/.*\.php$');

        foreach ($files as $file) {
            $content = $this->openFile($file);

            self::assertEquals(0, preg_match('/[А-Яа-яЁё]/u', $content), 'File has no valid chars: ' . $file);
        }
    }

}