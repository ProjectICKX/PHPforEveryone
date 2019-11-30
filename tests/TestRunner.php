<?php
/**  ___ _  _ ___  __         ___
 *  | _ | || | _ \/ _|___ _ _| ____ _____ _ _ _  _ ___ _ _  ___
 *  |  _| __ |  _|  _/ _ | '_| _|\ V / -_| '_| || / _ | ' \/ -_)
 *  |_| |_||_|_| |_| \___|_| |___|\_/\___|_|  \_, \___|_||_\___|
 *                                            |__/
 *
 * PHPforEveryone: sample
 *
 * @category    test
 * @package     sample
 * @author      akira wakaba <wakabadou@gmail.com>
 * @copyright   Copyright (c) @2019  Wakabadou (http://www.wakabadou.net/) / Project ICKX (https://ickx.jp/). All rights reserved.
 * @license     http://opensource.org/licenses/MIT The MIT License.
 *              This software is released under the MIT License.
 * @varsion     1.0.0
 */

namespace project_ickx\php_for_everyone\tests\streams;

/**
 * 本稿で実装したファイル用のテストランナーです。
 */
class TestRunner
{
    /**
     * テストランナーインスタンスを返します。
     *
     * @return  TestRunner  テストランナーインスタンス
     */
    public static function factory()
    {
        return new static();
    }

    /**
     * プロジェクトルートパスを補完した上で指定されたPHPファイルを require_once します。
     *
     * @param   string  $path   PHPファイルパス
     */
    public static function load($path)
    {
        require_once \sprintf('%s/%s', \dirname(__DIR__), $path);
    }

    /**
     * テストを実行します。
     */
    public function run()
    {
        $test_case_paths    = $this->pickupTestCase();
        $result             = $this->test($test_case_paths);
        $this->render($result);
    }

    /**
     * テストケースをピックアップします。
     *
     * @return  array   テストケースファイルパス
     */
    protected function pickupTestCase()
    {
        $test_case_paths    = array();

        foreach (new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(\sprintf('%s/cases', __DIR__), \FilesystemIterator::CURRENT_AS_FILEINFO),
            \RecursiveIteratorIterator::LEAVES_ONLY
        ) as $fileinfo) {
            if ($fileinfo->isFile() && \substr($fileinfo->getBasename(), -8) === 'Test.php') {
                $test_case_paths[]  = $fileinfo->getPathname();
            }
        }

        return $test_case_paths;
    }

    /**
     * テストを実施します。
     *
     * @param   array   $test_case_paths    テストケースパス
     * @return  array   テスト実行結果
     */
    protected function test($test_case_paths)
    {
        $loaded_classes = \get_declared_classes();

        foreach ($test_case_paths as $test_case_path) {
            require_once $test_case_path;
        }

        $result = array();

        foreach (\array_diff(\get_declared_classes(), $loaded_classes) as $added_class) {
            if (\substr($added_class, -4) === 'Test') {
                $test_class = new $added_class();

                try {
                    $test_class->test();
                    $result[\get_class($test_class)]    = $test_class->getLogs();
                } catch (\Exception $e) {
                    throw $e;
                }
            }
        }

        return $result;
    }

    /**
     * テスト結果を描画します。
     *
     * @param   array   $result テスト結果
     */
    protected function render($result)
    {
        $success_total  = 0;
        $failed_total   = 0;

        $detail_message = array();
        $is_error       = false;

        foreach ($result as $class => $test_result) {
            $success    = count($test_result['success']);
            $failed     = count($test_result['failed']);
            $total      = $success + $failed;

            $success_total  += $success;
            $failed_total   += $failed;

            if ($failed > 0) {
                $is_error = true;
            }

            $message    = array(
                \sprintf('  class:%s (%s / %s)', $class, $success, $total),
            );

            foreach ($test_result['failed'] as $failed) {
                $expected   = static::toText($failed['expected'], 999);
                $actual     = static::toText($failed['actual'], 999);

                $message[]    = \sprintf('    %s', $failed['backtrace']);
                $message[]    = \sprintf('      expected: %s', $expected);
                $message[]    = \sprintf('      actual:   %s', $actual);
            }

            $detail_message[]   = \implode(\PHP_EOL, $message);
        }

        echo \sprintf('test result: %s (%s / %s)', $is_error ? 'failed' : 'success', $success_total, $success_total + $failed_total), \PHP_EOL;
        echo \implode(\PHP_EOL, $detail_message);
    }

    /**
     * 変数の型情報付きの文字列表現を返します。
     *
     * @param   mixed   $var    文字列表現化したい変数
     * @param   int     $depth  文字列表現化する階層
     * @return  string  文字列表現化した変数
     */
    public static function toText($var, $depth = 0)
    {
        $type   = \gettype($var);
        switch ($type) {
            case 'boolean':
                return $var ? 'TRUE' : 'FALSE';
            case 'integer':
                return (string) $var;
            case 'double':
                return false === \strpos($var, '.') ? \sprintf('%s.0', $var) : (string) $var;
            case 'string':
                return \sprintf('\'%s\'', $var);
            case 'array':
                if ($depth < 1) {
                    return 'Array';
                }

                --$depth;
                $ret = array();

                foreach ($var as $key => $value) {
                    $ret[] = \sprintf('%s => %s', static::toText($key), static::toText($value, $depth));
                }

                return \sprintf('[%s]', \implode(', ', $ret));
            case 'object':
                \ob_start();
                \var_dump($var);
                $object_status  = \ob_get_clean();

                $object_status  = \substr($object_status, 0, \strpos($object_status, ' ('));
                $object_status  = \sprintf('object(%s)', \substr($object_status, 6));

                if ($depth < 1) {
                    return $object_status;
                }

                --$depth;

                $ro         = new \ReflectionObject($var);

                $tmp_properties = array();
                foreach ($ro->getProperties() as $property) {
                    $state      = $property->isStatic() ? 'static' : 'dynamic';
                    $modifier   = $property->isPublic() ? 'public' : ($property->isProtected() ? 'protected' : ($property->isPrivate() ? 'private' : 'unkown modifier'));
                    $tmp_properties[$state][$modifier][]    = $property;
                }

                $properties = array();
                foreach (array('static', 'dynamic') as $state) {
                    $state_text = $state === 'static' ? 'static ' : '';
                    foreach (array('public', 'protected', 'private', 'unkown modifier') as $modifier) {
                        foreach (isset($tmp_properties[$state][$modifier]) ? $tmp_properties[$state][$modifier] : array() as $property) {
                            $property->setAccessible(true);
                            $properties[] = \sprintf('%s%s %s = %s', $state_text, $modifier, static::toText($property->getName()), static::toText($property->getValue($var), $depth));
                        }
                    }
                }

                return \sprintf('%s {%s}', $object_status, \implode(', ', $properties));
            case 'resource':
                return \sprintf('%s %s', \get_resource_type($var), $var);
            case 'resource (closed)':
                return \sprintf('resource (closed) %s', $var);
            case 'NULL':
                return 'NULL';
            case 'unknown type':
            default:
                return 'unknown type';
        }
    }
}
