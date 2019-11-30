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

use project_ickx\php_for_everyone\streams\filters\ConvertLienfeedFilter;
use project_ickx\php_for_everyone\streams\filters\utilitys\StreamFilterSpec;
use project_ickx\php_for_everyone\streams\filters\utilitys\specs\StreamFilterConvertEncodingSpec;
use project_ickx\php_for_everyone\streams\filters\utilitys\specs\StreamFilterConvertLinefeedSpec;
use project_ickx\php_for_everyone\tests\streams\tester\AbstractTest;

/**
 * 行末の改行コードを変換するストリームフィルタクラスのテスト
 */
class ConvertLienfeedFilterTest extends AbstractTest
{
    /**
     * @var string  テストデータ：空文字
     */
    const TEST_DATA_EMPTY       = '';

    /**
     * @var string  テストデータ：パターン1：CR
     */
    const TEST_DATA_ONLY_CR1    = "\r";

    /**
     * @var string  テストデータ：パターン1：LF
     */
    const TEST_DATA_ONLY_LF1    = "\n";

    /**
     * @var string  テストデータ：パターン1：CRLF
     */
    const TEST_DATA_ONLY_CRLF1  = "\r\n";

    /**
     * @var string  テストデータ：パターン2：CR
     */
    const TEST_DATA_ONLY_CR2    = "\r\r";

    /**
     * @var string  テストデータ：パターン2：LF
     */
    const TEST_DATA_ONLY_LF2    = "\n\n";

    /**
     * @var string  テストデータ：パターン2：CRLF
     */
    const TEST_DATA_ONLY_CRLF2  = "\r\n\r\n";

    /**
     * @var string  テストデータ：パターン3：CR
     */
    const TEST_DATA_ONLY_CR3    = "\r\r\r";

    /**
     * @var string  テストデータ：パターン3：LF
     */
    const TEST_DATA_ONLY_LF3    = "\n\n\n";

    /**
     * @var string  テストデータ：パターン3：CRLF
     */
    const TEST_DATA_ONLY_CRLF3  = "\r\n\r\n\r\n";

    /**
     * @var string  テストデータ：パターン4：CR
     */
    const TEST_DATA_ONLY_CR4    = "\r\r\r\r";

    /**
     * @var string  テストデータ：パターン4：LF
     */
    const TEST_DATA_ONLY_LF4    = "\n\n\n\n";

    /**
     * @var string  テストデータ：パターン4：CRLF
     */
    const TEST_DATA_ONLY_CRLF4  = "\r\n\r\n\r\n\r\n";

    /**
     * @var string  テストデータ：複雑な組み合わせ1
     */
    const TEST_DATA_COMPLEX1    = "\r\n\n\r";

    /**
     * @var string  テストデータ：複雑な組み合わせ2
     */
    const TEST_DATA_COMPLEX2    = "\n\r\r\n";

    /**
     * @var string  テストデータ：複雑な組み合わせ3
     */
    const TEST_DATA_COMPLEX3    = "\r\r\n\n";

    /**
     * @var string  テストデータ：複雑な組み合わせ4
     */
    const TEST_DATA_COMPLEX4    = "\n\n\r\r";

    /**
     * @var string  テストデータ：複雑な組み合わせ5
     */
    const TEST_DATA_COMPLEX5    = "\n\r";

    /**
     * Setup
     */
    protected function setUp()
    {
        StreamFilterSpec::registerConvertLinefeedFilter();
    }

    /**
     * フィルタ名テスト
     */
    public function testFilterName()
    {
        StreamFilterSpec::registerConvertLinefeedFilter('aaaa');
        $this->assertWriteStreamFilterSame("\r\n\n", "\r\n\r", 'php://filter/write=aaaa.lf:cr/resource=php://temp');

        StreamFilterSpec::registerConvertLinefeedFilter('aaaa.bbb.ccc');
        $this->assertWriteStreamFilterSame("\r\n\n", "\r\n\r", 'php://filter/write=aaaa.bbb.ccc.lf:cr/resource=php://temp');

        StreamFilterSpec::registerConvertLinefeedFilter();
    }

    /**
     * 例外テスト
     */
    public function testException()
    {
        try {
            $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF1, static::TEST_DATA_ONLY_LF1, array(StreamFilterConvertLinefeedSpec::toLf()->fromLf()));
            throw new \Exception();
        } catch (\Exception $e) {
            $this->assertSame('変換前後の改行コード指定が同じです。to_linefeed:LF, from_linefeed:LF', $e->getMessage());
        }

        try {
            $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF1, static::TEST_DATA_ONLY_LF1, 'php://filter/write=convert.linefeed.aaa:lf/resource=php://temp');
            throw new \Exception();
        } catch (\Exception $e) {
            $this->assertSame('変換先の改行コード指定が無効です。to_linefeed:aaa', $e->getMessage());
        }

        try {
            $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF1, static::TEST_DATA_ONLY_LF1, 'php://filter/write=convert.linefeed.cr:aaa/resource=php://temp');
            throw new \Exception();
        } catch (\Exception $e) {
            $this->assertSame('変換元の改行コード指定が無効です。from_linefeed:aaa', $e->getMessage());
        }
    }

    /**
     * LFへの変換テスト
     */
    public function testConvert2Lf()
    {
        $stream_wrapper = array(StreamFilterConvertLinefeedSpec::toLf()->fromCr());
        $this->assertWriteStreamFilterSame("\r\n\n", "\r\n\r", $stream_wrapper);

        $stream_wrapper = array(StreamFilterConvertLinefeedSpec::toLf()->fromCr());
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF1, static::TEST_DATA_ONLY_CR1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF2, static::TEST_DATA_ONLY_CR2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF3, static::TEST_DATA_ONLY_CR3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF4, static::TEST_DATA_ONLY_CR4, $stream_wrapper);

        $stream_wrapper = array(StreamFilterConvertLinefeedSpec::toLf()->fromCrLf());
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF1, static::TEST_DATA_ONLY_CRLF1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF2, static::TEST_DATA_ONLY_CRLF2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF3, static::TEST_DATA_ONLY_CRLF3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF4, static::TEST_DATA_ONLY_CRLF4, $stream_wrapper);

        $stream_wrapper = array(StreamFilterConvertLinefeedSpec::toLf()->fromAll());
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF1, static::TEST_DATA_ONLY_LF1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF2, static::TEST_DATA_ONLY_LF2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF3, static::TEST_DATA_ONLY_LF3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF4, static::TEST_DATA_ONLY_LF4, $stream_wrapper);

        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF1, static::TEST_DATA_ONLY_CR1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF2, static::TEST_DATA_ONLY_CR2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF3, static::TEST_DATA_ONLY_CR3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF4, static::TEST_DATA_ONLY_CR4, $stream_wrapper);

        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF1, static::TEST_DATA_ONLY_CRLF1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF2, static::TEST_DATA_ONLY_CRLF2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF3, static::TEST_DATA_ONLY_CRLF3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF4, static::TEST_DATA_ONLY_CRLF4, $stream_wrapper);

        $stream_wrapper = array(StreamFilterConvertLinefeedSpec::toLf());
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF1, static::TEST_DATA_ONLY_LF1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF2, static::TEST_DATA_ONLY_LF2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF3, static::TEST_DATA_ONLY_LF3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF4, static::TEST_DATA_ONLY_LF4, $stream_wrapper);

        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF1, static::TEST_DATA_ONLY_CR1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF2, static::TEST_DATA_ONLY_CR2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF3, static::TEST_DATA_ONLY_CR3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF4, static::TEST_DATA_ONLY_CR4, $stream_wrapper);

        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF1, static::TEST_DATA_ONLY_CRLF1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF2, static::TEST_DATA_ONLY_CRLF2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF3, static::TEST_DATA_ONLY_CRLF3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_LF4, static::TEST_DATA_ONLY_CRLF4, $stream_wrapper);
    }

    /**
     * CRへの変換テスト
     */
    public function testConvert2Cr()
    {
        $stream_wrapper = array(StreamFilterConvertLinefeedSpec::toCr()->fromLf());
        $this->assertWriteStreamFilterSame("\n\r\r", "\n\r\n", $stream_wrapper);

        $stream_wrapper = array(StreamFilterConvertLinefeedSpec::toCr()->fromLf());
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR1, static::TEST_DATA_ONLY_LF1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR2, static::TEST_DATA_ONLY_LF2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR3, static::TEST_DATA_ONLY_LF3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR4, static::TEST_DATA_ONLY_LF4, $stream_wrapper);

        $stream_wrapper = array(StreamFilterConvertLinefeedSpec::toCr()->fromCrLf());
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR1, static::TEST_DATA_ONLY_CRLF1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR2, static::TEST_DATA_ONLY_CRLF2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR3, static::TEST_DATA_ONLY_CRLF3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR4, static::TEST_DATA_ONLY_CRLF4, $stream_wrapper);

        $stream_wrapper = array(StreamFilterConvertLinefeedSpec::toCr());
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR1, static::TEST_DATA_ONLY_LF1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR2, static::TEST_DATA_ONLY_LF2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR3, static::TEST_DATA_ONLY_LF3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR4, static::TEST_DATA_ONLY_LF4, $stream_wrapper);

        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR1, static::TEST_DATA_ONLY_CR1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR2, static::TEST_DATA_ONLY_CR2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR3, static::TEST_DATA_ONLY_CR3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR4, static::TEST_DATA_ONLY_CR4, $stream_wrapper);

        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR1, static::TEST_DATA_ONLY_CRLF1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR2, static::TEST_DATA_ONLY_CRLF2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR3, static::TEST_DATA_ONLY_CRLF3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR4, static::TEST_DATA_ONLY_CRLF4, $stream_wrapper);

        $stream_wrapper = array(StreamFilterConvertLinefeedSpec::toCr()->fromAll());
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR1, static::TEST_DATA_ONLY_LF1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR2, static::TEST_DATA_ONLY_LF2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR3, static::TEST_DATA_ONLY_LF3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR4, static::TEST_DATA_ONLY_LF4, $stream_wrapper);

        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR1, static::TEST_DATA_ONLY_CR1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR2, static::TEST_DATA_ONLY_CR2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR3, static::TEST_DATA_ONLY_CR3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR4, static::TEST_DATA_ONLY_CR4, $stream_wrapper);

        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR1, static::TEST_DATA_ONLY_CRLF1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR2, static::TEST_DATA_ONLY_CRLF2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR3, static::TEST_DATA_ONLY_CRLF3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CR4, static::TEST_DATA_ONLY_CRLF4, $stream_wrapper);
    }

    /**
     * CRLFへの変換テスト
     */
    public function testConvert2CrLf()
    {
        $stream_wrapper = array(StreamFilterConvertLinefeedSpec::toCrLf()->fromCr());
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF1, static::TEST_DATA_ONLY_CR1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF2, static::TEST_DATA_ONLY_CR2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF3, static::TEST_DATA_ONLY_CR3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF4, static::TEST_DATA_ONLY_CR4, $stream_wrapper);

        $stream_wrapper = array(StreamFilterConvertLinefeedSpec::toCrLf()->fromLf());
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF1, static::TEST_DATA_ONLY_LF1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF2, static::TEST_DATA_ONLY_LF2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF3, static::TEST_DATA_ONLY_LF3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF4, static::TEST_DATA_ONLY_LF4, $stream_wrapper);

        $stream_wrapper = array(StreamFilterConvertLinefeedSpec::toCrLf()->fromAll());
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF1, static::TEST_DATA_ONLY_CR1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF2, static::TEST_DATA_ONLY_CR2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF3, static::TEST_DATA_ONLY_CR3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF4, static::TEST_DATA_ONLY_CR4, $stream_wrapper);

        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF1, static::TEST_DATA_ONLY_LF1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF2, static::TEST_DATA_ONLY_LF2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF3, static::TEST_DATA_ONLY_LF3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF4, static::TEST_DATA_ONLY_LF4, $stream_wrapper);

        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF1, static::TEST_DATA_ONLY_CRLF1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF2, static::TEST_DATA_ONLY_CRLF2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF3, static::TEST_DATA_ONLY_CRLF3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF4, static::TEST_DATA_ONLY_CRLF4, $stream_wrapper);

        $stream_wrapper = array(StreamFilterConvertLinefeedSpec::toCrLf());
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF1, static::TEST_DATA_ONLY_CR1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF2, static::TEST_DATA_ONLY_CR2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF3, static::TEST_DATA_ONLY_CR3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF4, static::TEST_DATA_ONLY_CR4, $stream_wrapper);

        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF1, static::TEST_DATA_ONLY_LF1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF2, static::TEST_DATA_ONLY_LF2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF3, static::TEST_DATA_ONLY_LF3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF4, static::TEST_DATA_ONLY_LF4, $stream_wrapper);

        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF1, static::TEST_DATA_ONLY_CRLF1, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF2, static::TEST_DATA_ONLY_CRLF2, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF3, static::TEST_DATA_ONLY_CRLF3, $stream_wrapper);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_ONLY_CRLF4, static::TEST_DATA_ONLY_CRLF4, $stream_wrapper);
    }

    /**
     * i/7テスト
     */
    public function testI01()
    {
        $actual     = implode(ConvertLienfeedFilter::LF, array(
            '1111,1111',
            '2222,2222',
            '3333,3333',
            '4444,4444',
            '5555,5555',
            '6666,6666',
            '7777,7777',
            '8888,8888',
        ));

        $expected   = array(
            array('1111', '1111'),
            array('2222', '2222'),
            array('3333', '3333'),
            array('4444', '4444'),
            array('5555', '5555'),
            array('6666', '6666'),
            array('7777', '7777'),
            array('8888', '8888'),
        );

        $read_parameters    = array(
            StreamFilterConvertEncodingSpec::toUtf8()->fromSjisWin(),
            StreamFilterConvertLinefeedSpec::toCrLf(),
        );

        $stream_chunk_size  = 1024;
        $this->assertCsvInputStreamFilterSame($expected, $actual, $stream_chunk_size, $read_parameters);
    }
}
