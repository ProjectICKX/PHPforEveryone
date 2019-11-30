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

namespace project_ickx\php_for_everyone\tests\cases\filters;

use project_ickx\php_for_everyone\streams\filters\ConvertEncodingFilter;
use project_ickx\php_for_everyone\streams\filters\ConvertLienfeedFilter;
use project_ickx\php_for_everyone\streams\filters\utilitys\StreamFilterSpec;
use project_ickx\php_for_everyone\streams\filters\utilitys\specs\StreamFilterConvertEncodingSpec;
use project_ickx\php_for_everyone\streams\filters\utilitys\specs\StreamFilterConvertLinefeedSpec;
use project_ickx\php_for_everyone\tests\streams\tester\AbstractTest;

/**
 * エンコーディングを変換するストリームフィルタクラスのテスト
 */
class CsvIoTest extends AbstractTest
{
    /**
     * @var string  テストデータ：ダメ文字開始
     */
    const TEST_DATA_SIMPLE_TEXT1    = 'ソソソソん';

    /**
     * @var string  テストデータ：ダメ文字+セパレータ
     */
    const TEST_DATA_SIMPLE_TEXT2    = 'ソ ソ ソ ソ ソ ';

    /**
     * @var string  テストデータ：複数のダメ文字
     */
    const TEST_DATA_SIMPLE_TEXT3    = 'ソソソソん①㈱㌔髙﨑纊ソｱｲｳｴｵあいうえおabc';

    /**
     * Setup
     */
    protected function setUp()
    {
        StreamFilterSpec::registerConvertEncodingFilter();
        StreamFilterSpec::registerConvertLinefeedFilter();

        ConvertEncodingFilter::startChangeLocale();
    }

    /**
     * Windows向けCSV出力テスト
     */
    public function testCsvOutput()
    {
        $write_parameters   = array(
            StreamFilterConvertEncodingSpec::setupForSjisOut(),
            StreamFilterConvertLinefeedSpec::setupForWindows(),
        );

        $expected   = \mb_convert_encoding(\implode(
            ConvertLienfeedFilter::CRLF,
            array(
                \implode(',', array(static::TEST_DATA_SIMPLE_TEXT1, '"'. static::TEST_DATA_SIMPLE_TEXT2 .'"', static::TEST_DATA_SIMPLE_TEXT3)),
                \implode(',', array('"'. static::TEST_DATA_SIMPLE_TEXT2 .'"', static::TEST_DATA_SIMPLE_TEXT3, static::TEST_DATA_SIMPLE_TEXT1)),
                \implode(',', array(static::TEST_DATA_SIMPLE_TEXT3, static::TEST_DATA_SIMPLE_TEXT1, '"'. static::TEST_DATA_SIMPLE_TEXT2 .'"')),
                ''
            )
        ), 'SJIS-win', 'UTF-8');

        $csv_data   = array(
            array(static::TEST_DATA_SIMPLE_TEXT1, static::TEST_DATA_SIMPLE_TEXT2, static::TEST_DATA_SIMPLE_TEXT3),
            array(static::TEST_DATA_SIMPLE_TEXT2, static::TEST_DATA_SIMPLE_TEXT3, static::TEST_DATA_SIMPLE_TEXT1),
            array(static::TEST_DATA_SIMPLE_TEXT3, static::TEST_DATA_SIMPLE_TEXT1, static::TEST_DATA_SIMPLE_TEXT2),
        );

        $stream_chunk_size  = 1024;

        $this->assertCsvOutputStreamFilterSame($expected, $csv_data, $stream_chunk_size, $write_parameters);
    }

    /**
     * Windows向けCSV入力テスト
     */
    public function testCsvInput()
    {
        $read_parameters    = array(
            StreamFilterConvertEncodingSpec::setupForUtf8Out(),
        );

        $expected   = array(
            array(static::TEST_DATA_SIMPLE_TEXT1, static::TEST_DATA_SIMPLE_TEXT2, static::TEST_DATA_SIMPLE_TEXT3),
            array(static::TEST_DATA_SIMPLE_TEXT2, static::TEST_DATA_SIMPLE_TEXT3, static::TEST_DATA_SIMPLE_TEXT1),
            array(static::TEST_DATA_SIMPLE_TEXT3, static::TEST_DATA_SIMPLE_TEXT1, static::TEST_DATA_SIMPLE_TEXT2),
        );

        $csv_text   = \mb_convert_encoding(\implode(
            ConvertLienfeedFilter::CRLF,
            array(
                \implode(',', array(static::TEST_DATA_SIMPLE_TEXT1, '"'. static::TEST_DATA_SIMPLE_TEXT2 .'"', static::TEST_DATA_SIMPLE_TEXT3)),
                \implode(',', array('"'. static::TEST_DATA_SIMPLE_TEXT2 .'"', static::TEST_DATA_SIMPLE_TEXT3, static::TEST_DATA_SIMPLE_TEXT1)),
                \implode(',', array(static::TEST_DATA_SIMPLE_TEXT3, static::TEST_DATA_SIMPLE_TEXT1, '"'. static::TEST_DATA_SIMPLE_TEXT2 .'"')),
                ''
            )
        ), 'SJIS-win', 'UTF-8');

        $stream_chunk_size  = 1024;

        $this->assertCsvInputStreamFilterSame($expected, $csv_text, $stream_chunk_size, $read_parameters);
    }

    /**
     * Teardown
     */
    protected function tearDown()
    {
        ConvertEncodingFilter::endChangeLocale();
    }
}
