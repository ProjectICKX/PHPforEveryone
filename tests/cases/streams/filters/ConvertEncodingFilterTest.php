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
use project_ickx\php_for_everyone\streams\filters\utilitys\StreamFilterSpec;
use project_ickx\php_for_everyone\streams\filters\utilitys\specs\StreamFilterConvertEncodingSpec;
use project_ickx\php_for_everyone\tests\streams\tester\AbstractTest;

/**
 * エンコーディングを変換するストリームフィルタクラスのテスト
 */
class ConvertEncodingFilterTest extends AbstractTest
{
    /**
     * @var string  実行環境吸収用ラベル：Windows向け
     */
    const LOCALE_FOR_WINDOWS    = 'locale_for_windows';

    /**
     * @var string  実行環境吸収用ラベル：Windows以外向け
     */
    const LOCALE_FOR_OTHER      = 'locale_for_other';

    /**
     * @var array   実行環境吸収用ロカールマップ
     */
    public static $LOCALE_MAP    = array(
        self::LOCALE_FOR_WINDOWS    => array(
            'Japanese_Japan.20127',
            'C',
            'Japanese_Japan.20932',
        ),
        self::LOCALE_FOR_OTHER      => array(
            'ja_JP.ujis',
            'C',
            'ja_JP.eucjp',
        ),
    );

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
     * @var string  テストデータ：代替文字
     */
    const TEST_DATA_SIMPLE_TEXT4            = '1艗1鎽1𩸽1';

    /**
     * @var string  テストデータ：代替文字：消去
     */
    const TEST_DATA_SIMPLE_TEXT4_NONE       = '1111';

    /**
     * @var string  テストデータ：代替文字：unicode
     */
    const TEST_DATA_SIMPLE_TEXT4_LONG       = '1U+82571U+93BD1U+29E3D1';

    /**
     * @var string  テストデータ：代替文字：untity
     */
    const TEST_DATA_SIMPLE_TEXT4_ENTITY     = '1&#x8257;1&#x93BD;1&#x29E3D;1';

    /**
     * @var string  テストデータ：代替文字：任意文字
     */
    const TEST_DATA_SIMPLE_TEXT4_WORD       = '1a1a1a1';

    /**
     * @var string  テストデータ：代替文字：任意文字用コードポイント：a
     */
    const TEST_DATA_SIMPLE_TEXT4_CODE_POINT = 0x0061;

    /**
     * @var string  システムバックアップ：ロケール
     */
    protected $systemLocale                 = '';

    /**
     * @var string  システムバックアップ：代替文字
     */
    protected $systemSubstituteCharacter    = '';

    /**
     * @var string  スタック検証用ロカールリスト
     */
    protected $localeList                   = null;

    /**
     * Setup
     */
    protected function setUp()
    {
        $this->systemLocale                 = ConvertEncodingFilter::startChangeLocale();
        $this->systemSubstituteCharacter    = ConvertEncodingFilter::startChangeSubstituteCharacter();

        $this->localeList                   = static::$LOCALE_MAP[\substr(\PHP_OS, 0, 3) !== 'WIN' ? static::LOCALE_FOR_OTHER : static::LOCALE_FOR_WINDOWS];

        ConvertEncodingFilter::detectOrder(ConvertEncodingFilter::$DETECT_ORDER_DEFAULT);

        StreamFilterSpec::registerConvertEncodingFilter();
    }

    /**
     * フィルタ名テスト
     */
    public function testFilterName()
    {
        $expected_test_data_simple_text1    = \mb_convert_encoding(static::TEST_DATA_SIMPLE_TEXT1, 'SJIS-win', 'UTF-8');

        StreamFilterSpec::registerConvertEncodingFilter('aaa');
        $this->assertWriteStreamFilterSame($expected_test_data_simple_text1, static::TEST_DATA_SIMPLE_TEXT1, array(StreamFilterConvertEncodingSpec::toSjisWin()));

        StreamFilterSpec::registerConvertEncodingFilter('aaa.bbb.ccc');
        $this->assertWriteStreamFilterSame($expected_test_data_simple_text1, static::TEST_DATA_SIMPLE_TEXT1, array(StreamFilterConvertEncodingSpec::toSjisWin()));

        StreamFilterSpec::registerConvertEncodingFilter();
    }

    /**
     * 例外テスト
     */
    public function testException()
    {
        try {
            $this->assertWriteStreamFilterSame('あ', 'あ', array(StreamFilterConvertEncodingSpec::toUtf8()->fromUtf8()));
            throw new \Exception();
        } catch (\Exception $e) {
            $this->assertSame('変換前後のエンコーディング名が同じです。to_encoding:UTF-8, from_encoding:UTF-8', $e->getMessage());
        }

        try {
            $stream_wrapper = 'php://filter/write=convert.encoding.aaa:UTF-8/resource=php://temp';
            $this->assertWriteStreamFilterSame('あ', 'あ', $stream_wrapper);
            throw new \Exception();
        } catch (\Exception $e) {
            $this->assertSame('変換先のエンコーディング名が無効です。to_encoding:aaa', $e->getMessage());
        }

        try {
            $stream_wrapper = 'php://filter/write=convert.encoding.UTF-8:aaa/resource=php://temp';
            $this->assertWriteStreamFilterSame('あ', 'あ', $stream_wrapper);
            throw new \Exception();
        } catch (\Exception $e) {
            $this->assertSame('変換元のエンコーディング名が無効です。from_encoding:aaa', $e->getMessage());
        }
    }

    /**
     * ロカール変更のテスト
     */
    public function testLocale()
    {
        try {
            ConvertEncodingFilter::startChangeLocale('asdfqwer');
            throw new \Exception();
        } catch (\Exception $e) {
            $this->assertSame('システムで使用できないロカールを指定されました。locale:asdfqwer', $e->getMessage());
        }

        $this->assertSame(array($this->systemLocale), ConvertEncodingFilter::getLocaleStack());
        $this->assertSame(ConvertEncodingFilter::getSafeLocale(), ConvertEncodingFilter::currentLocale());

        $this->assertSame(ConvertEncodingFilter::getSafeLocale(), ConvertEncodingFilter::endChangeLocale());
        $this->assertSame(array(), ConvertEncodingFilter::getLocaleStack());
        $this->assertSame($this->systemLocale, ConvertEncodingFilter::currentLocale());

        $this->assertSame($this->systemLocale, ConvertEncodingFilter::startChangeLocale());
        $this->assertSame(array($this->systemLocale), ConvertEncodingFilter::getLocaleStack());
        $this->assertSame(ConvertEncodingFilter::getSafeLocale(), ConvertEncodingFilter::currentLocale());

        $locale_list = $this->localeList;
        $start_locale_stack     = array();
        foreach ($locale_list as $locale) {
            $start_locale_stack[]   = ConvertEncodingFilter::startChangeLocale($locale);
        }

        \end($locale_list);
        $this->assertSame(array_merge(array($this->systemLocale, ConvertEncodingFilter::getSafeLocale()), array_slice($locale_list, 0, 2)), ConvertEncodingFilter::getLocaleStack());
        $this->assertSame(\current($locale_list), ConvertEncodingFilter::currentLocale());

        $this->assertSame(\current($locale_list), ConvertEncodingFilter::endChangeLocale(true));
        $this->assertSame(array(), ConvertEncodingFilter::getLocaleStack());
        $this->assertSame($this->systemLocale, ConvertEncodingFilter::currentLocale());

        $start_locale_stack     = array();
        foreach ($locale_list as $locale) {
            $start_locale_stack[]   = ConvertEncodingFilter::startChangeLocale($locale);
        }
        $start_locale_stack[]   = ConvertEncodingFilter::currentLocale();

        $end_locale_stack   = array(
            ConvertEncodingFilter::endChangeLocale(),
            ConvertEncodingFilter::endChangeLocale(),
            ConvertEncodingFilter::endChangeLocale(),
            ConvertEncodingFilter::currentLocale(),
        );

        try {
            ConvertEncodingFilter::endChangeLocale();
            throw new \Exception();
        } catch (\Exception $e) {
            $this->assertSame('ロカールスタックが空です。', $e->getMessage());
        }

        krsort($end_locale_stack);
        $this->assertSame($start_locale_stack, array_values($end_locale_stack));

        ConvertEncodingFilter::startChangeLocale();
    }

    /**
     * 文字コードが無効または存在しない場合の代替文字のテスト
     */
    public function testSubstituteCharacter()
    {
        try {
            ConvertEncodingFilter::startChangeSubstituteCharacter(ConvertEncodingFilter::SUBSTITUTE_CHARACTER_MAX_CODE_POINT + 1);
            throw new \Exception();
        } catch (\Exception $e) {
            $this->assertSame('使用できない代替文字を与えられました。substitute_character:U+FFFF', $e->getMessage());
        }

        try {
            ConvertEncodingFilter::startChangeSubstituteCharacter('asdf');
            throw new \Exception();
        } catch (\Exception $e) {
            $this->assertSame('使用できない代替文字設定を与えられました。substitute_character:asdf', $e->getMessage());
        }

        $this->assertSame(array($this->systemSubstituteCharacter), ConvertEncodingFilter::getSubstituteCharacterStack());
        $this->assertSame(ConvertEncodingFilter::SUBSTITUTE_CHARACTER_DEFAULT, ConvertEncodingFilter::currentSubstituteCharacter());

        $this->assertSame(ConvertEncodingFilter::SUBSTITUTE_CHARACTER_DEFAULT, ConvertEncodingFilter::endChangeSubstituteCharacter());
        $this->assertSame(array(), ConvertEncodingFilter::getSubstituteCharacterStack());
        $this->assertSame($this->systemSubstituteCharacter, ConvertEncodingFilter::currentSubstituteCharacter());

        $this->assertSame($this->systemSubstituteCharacter, ConvertEncodingFilter::startChangeSubstituteCharacter());
        $this->assertSame(array($this->systemSubstituteCharacter), ConvertEncodingFilter::getSubstituteCharacterStack());
        $this->assertSame(ConvertEncodingFilter::SUBSTITUTE_CHARACTER_DEFAULT, ConvertEncodingFilter::currentSubstituteCharacter());

        $substitute_character_list      = array_values(ConvertEncodingFilter::$SUBSTITUTE_CHARACTER_MAP);
        $substitute_character_list[]    = ConvertEncodingFilter::SUBSTITUTE_CHARACTER_MAX_CODE_POINT;
        $substitute_character_list[]    = 1;

        $start_substitute_character_stack     = array();
        foreach ($substitute_character_list as $substitute_character) {
            $start_substitute_character_stack[]   = ConvertEncodingFilter::startChangeSubstituteCharacter($substitute_character);
        }

        \end($substitute_character_list);
        $this->assertSame(array_merge(array($this->systemSubstituteCharacter, ConvertEncodingFilter::SUBSTITUTE_CHARACTER_DEFAULT), array_slice($substitute_character_list, 0, 4)), ConvertEncodingFilter::getSubstituteCharacterStack());
        $this->assertSame(\current($substitute_character_list), ConvertEncodingFilter::currentSubstituteCharacter());

        $this->assertSame(\current($substitute_character_list), ConvertEncodingFilter::endChangeSubstituteCharacter(true));
        $this->assertSame(array(), ConvertEncodingFilter::getSubstituteCharacterStack());
        $this->assertSame($this->systemSubstituteCharacter, ConvertEncodingFilter::currentSubstituteCharacter());

        $start_substitute_character_stack     = array();
        foreach ($substitute_character_list as $substitute_character) {
            $start_substitute_character_stack[]   = ConvertEncodingFilter::startChangeSubstituteCharacter($substitute_character);
        }
        $start_substitute_character_stack[]   = ConvertEncodingFilter::currentSubstituteCharacter();

        $end_substitute_character_stack   = array(
            ConvertEncodingFilter::endChangeSubstituteCharacter(),
            ConvertEncodingFilter::endChangeSubstituteCharacter(),
            ConvertEncodingFilter::endChangeSubstituteCharacter(),
            ConvertEncodingFilter::endChangeSubstituteCharacter(),
            ConvertEncodingFilter::endChangeSubstituteCharacter(),
            ConvertEncodingFilter::currentSubstituteCharacter(),
        );

        try {
            ConvertEncodingFilter::endChangeSubstituteCharacter();
            throw new \Exception();
        } catch (\Exception $e) {
            $this->assertSame('代替文字コードスタックが空です。', $e->getMessage());
        }

        krsort($end_substitute_character_stack);
        $this->assertSame($start_substitute_character_stack, array_values($end_substitute_character_stack));

        $stream_wrapper = array(StreamFilterConvertEncodingSpec::toSjisWin()->fromUtf8());
        switch ($this->systemSubstituteCharacter) {
            case ConvertEncodingFilter::SUBSTITUTE_CHARACTER_NONE:
                $system_substitute_character_text = static::TEST_DATA_SIMPLE_TEXT4_NONE;
                break;
            case ConvertEncodingFilter::SUBSTITUTE_CHARACTER_LONG:
                $system_substitute_character_text = static::TEST_DATA_SIMPLE_TEXT4_LONG;
                break;
            case ConvertEncodingFilter::SUBSTITUTE_CHARACTER_ENTITY:
                $system_substitute_character_text = static::TEST_DATA_SIMPLE_TEXT4_ENTITY;
                break;
            default:
                $system_substitute_character    = $this->systemSubstituteCharacter;
                if (\is_int($system_substitute_character)) {
                    $system_substitute_character    = $this->int2utf8($system_substitute_character);
                }
                $system_substitute_character_text = implode($system_substitute_character, array(1, 1, 1, 1));
                break;
        }

        $this->assertWriteStreamFilterSame($system_substitute_character_text, static::TEST_DATA_SIMPLE_TEXT4, $stream_wrapper);

        ConvertEncodingFilter::startChangeSubstituteCharacter(ConvertEncodingFilter::SUBSTITUTE_CHARACTER_NONE);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_SIMPLE_TEXT4_NONE, static::TEST_DATA_SIMPLE_TEXT4, $stream_wrapper);
        ConvertEncodingFilter::endChangeSubstituteCharacter();

        ConvertEncodingFilter::startChangeSubstituteCharacter(ConvertEncodingFilter::SUBSTITUTE_CHARACTER_LONG);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_SIMPLE_TEXT4_LONG, static::TEST_DATA_SIMPLE_TEXT4, $stream_wrapper);
        ConvertEncodingFilter::endChangeSubstituteCharacter();

        ConvertEncodingFilter::startChangeSubstituteCharacter(ConvertEncodingFilter::SUBSTITUTE_CHARACTER_ENTITY);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_SIMPLE_TEXT4_ENTITY, static::TEST_DATA_SIMPLE_TEXT4, $stream_wrapper);
        ConvertEncodingFilter::endChangeSubstituteCharacter();

        ConvertEncodingFilter::startChangeSubstituteCharacter(static::TEST_DATA_SIMPLE_TEXT4_CODE_POINT);
        $this->assertWriteStreamFilterSame(static::TEST_DATA_SIMPLE_TEXT4_WORD, static::TEST_DATA_SIMPLE_TEXT4, $stream_wrapper);
        ConvertEncodingFilter::endChangeSubstituteCharacter();

        ConvertEncodingFilter::startChangeSubstituteCharacter();
    }

    /**
     * システムデフォルトの文字エンコーディング検出順キャッシュを取得するテスト
     */
    public function testDefaultDetectEncodingListCache()
    {
        $mb_list_encodings  = \mb_list_encodings();
        $this->assertSame(\array_combine($mb_list_encodings, $mb_list_encodings), ConvertEncodingFilter::getDefaultDetectEncodingListCache());
    }

    /**
     * memory_limitの単位をintに変換するテスト
     */
    public function testAdjustMemoryLimitUnit()
    {
        $this->assertSame(1, ConvertEncodingFilter::adjustMemoryLimitUnit(1));
        $this->assertSame(1000, ConvertEncodingFilter::adjustMemoryLimitUnit(1000));

        $this->assertSame(1024, ConvertEncodingFilter::adjustMemoryLimitUnit('1K'));
        $this->assertSame(1024, ConvertEncodingFilter::adjustMemoryLimitUnit('1k'));
        $this->assertSame(1024 * 1024, ConvertEncodingFilter::adjustMemoryLimitUnit('1M'));
        $this->assertSame(1024 * 1024, ConvertEncodingFilter::adjustMemoryLimitUnit('1m'));
        $this->assertSame(1024 * 1024 * 1024, ConvertEncodingFilter::adjustMemoryLimitUnit('1G'));
        $this->assertSame(1024 * 1024 * 1024, ConvertEncodingFilter::adjustMemoryLimitUnit('1g'));

        $this->assertSame(1000, ConvertEncodingFilter::adjustMemoryLimitUnit(1000));
        $this->assertSame(123 * 1024, ConvertEncodingFilter::adjustMemoryLimitUnit('123K'));
        $this->assertSame(456 * 1024 * 1024, ConvertEncodingFilter::adjustMemoryLimitUnit('456M'));
        $this->assertSame(789 * 1024 * 1024 * 1024, ConvertEncodingFilter::adjustMemoryLimitUnit('789G'));
    }

    /**
     * Shift_JIS遅延判定文字列バッファサイズを変更するテスト
     */
    public function testSjisSeparationPositionBufferSize()
    {
        $this->assertSame(ConvertEncodingFilter::SJIS_CHECK_DEFERRED_BUFFER_SIZE_DEFAULT, ConvertEncodingFilter::sjisSeparationPositionBufferSize());
        $this->assertSame(ConvertEncodingFilter::SJIS_CHECK_DEFERRED_BUFFER_SIZE_DEFAULT, ConvertEncodingFilter::sjisSeparationPositionBufferSize(1024));
        $this->assertSame(1024, ConvertEncodingFilter::sjisSeparationPositionBufferSize(ConvertEncodingFilter::SJIS_CHECK_DEFERRED_BUFFER_SIZE_DEFAULT));

        $memory_limit                       = ConvertEncodingFilter::adjustMemoryLimitUnit(\ini_get('memory_limit'));

        if ($memory_limit !== -1) {
            $sjis_check_deferred_buffer_size    = $memory_limit + 1;
            try {
                ConvertEncodingFilter::sjisSeparationPositionBufferSize($sjis_check_deferred_buffer_size);
                throw new \Exception();
            } catch (\Exception $e) {
                $this->assertSame(\sprintf('現在の設定で利用できるメモリ量を超過しています。%s / %s', $sjis_check_deferred_buffer_size, $memory_limit), $e->getMessage());
            }
        } else {
            $sjis_check_deferred_buffer_size    = ConvertEncodingFilter::sjisSeparationPositionBufferSize(\PHP_INT_MAX);
            $this->assertSame(\PHP_INT_MAX, ConvertEncodingFilter::sjisSeparationPositionBufferSize($sjis_check_deferred_buffer_size));
        }

        $this->assertSame(ConvertEncodingFilter::SJIS_CHECK_DEFERRED_BUFFER_SIZE_DEFAULT, ConvertEncodingFilter::sjisSeparationPositionBufferSize());
    }

    /**
     * デフォルト時の変換元エンコーディングの自動検出順を変更するテスト
     */
    public function testDetectOrder()
    {
        $this->assertSame(ConvertEncodingFilter::$DETECT_ORDER_DEFAULT, ConvertEncodingFilter::detectOrder());
        $this->assertSame(ConvertEncodingFilter::$DETECT_ORDER_DEFAULT, ConvertEncodingFilter::detectOrder(\mb_detect_order()));
        $this->assertSame(\mb_detect_order(), ConvertEncodingFilter::detectOrder());

        try {
            ConvertEncodingFilter::detectOrder(array('aaa'));
            throw new \Exception();
        } catch (\Exception $e) {
            $this->assertSame('システムで使用できないエンコーディングを指定されました。encoding:aaa', $e->getMessage());
        }

        $this->assertSame(\mb_detect_order(), ConvertEncodingFilter::detectOrder(ConvertEncodingFilter::$DETECT_ORDER_DEFAULT));
        $this->assertSame(ConvertEncodingFilter::$DETECT_ORDER_DEFAULT, ConvertEncodingFilter::detectOrder());
    }

    /**
     * Shift_JISへの変換テスト
     */
    public function testConvert2Sjis()
    {
        $expected_test_data_simple_text1    = \mb_convert_encoding(static::TEST_DATA_SIMPLE_TEXT1, 'SJIS-win', 'UTF-8');

        $stream_wrapper = array(StreamFilterConvertEncodingSpec::toSjisWin());
        $this->assertWriteStreamFilterSame($expected_test_data_simple_text1, static::TEST_DATA_SIMPLE_TEXT1, $stream_wrapper);
        $this->assertWriteStreamFilterSame($expected_test_data_simple_text1, \mb_convert_encoding(static::TEST_DATA_SIMPLE_TEXT1, 'eucJP-win', 'UTF-8'), $stream_wrapper);

        $stream_wrapper = array(StreamFilterConvertEncodingSpec::toSjisWin()->fromUtf8());
        $this->assertWriteStreamFilterSame($expected_test_data_simple_text1, static::TEST_DATA_SIMPLE_TEXT1, $stream_wrapper);
        $this->assertWriteStreamFilterNotSame($expected_test_data_simple_text1, \mb_convert_encoding(static::TEST_DATA_SIMPLE_TEXT1, 'eucJP-win', 'UTF-8'), $stream_wrapper);

        $stream_wrapper = array(StreamFilterConvertEncodingSpec::toSjisWin()->fromEucJpWin());
        $this->assertWriteStreamFilterNotSame($expected_test_data_simple_text1, static::TEST_DATA_SIMPLE_TEXT1, $stream_wrapper);
        $this->assertWriteStreamFilterSame($expected_test_data_simple_text1, \mb_convert_encoding(static::TEST_DATA_SIMPLE_TEXT1, 'eucJP-win', 'UTF-8'), $stream_wrapper);
    }

    /**
     * EUC-JPへの変換テスト
     */
    public function testConvert2Euc()
    {
        $expected_test_data_simple_text1    = \mb_convert_encoding(static::TEST_DATA_SIMPLE_TEXT1, 'eucJP-win', 'UTF-8');

        $stream_wrapper = array(StreamFilterConvertEncodingSpec::toEucJpWin());
        $this->assertWriteStreamFilterSame($expected_test_data_simple_text1, static::TEST_DATA_SIMPLE_TEXT1, $stream_wrapper);
        $this->assertWriteStreamFilterSame($expected_test_data_simple_text1, \mb_convert_encoding(static::TEST_DATA_SIMPLE_TEXT1, 'SJIS-win', 'UTF-8'), $stream_wrapper);

        $stream_wrapper = array(StreamFilterConvertEncodingSpec::toEucJpWin()->fromUtf8());
        $this->assertWriteStreamFilterSame($expected_test_data_simple_text1, static::TEST_DATA_SIMPLE_TEXT1, $stream_wrapper);
        $this->assertWriteStreamFilterNotSame($expected_test_data_simple_text1, \mb_convert_encoding(static::TEST_DATA_SIMPLE_TEXT1, 'SJIS-win', 'UTF-8'), $stream_wrapper);

        $stream_wrapper = array(StreamFilterConvertEncodingSpec::toEucJpWin()->fromSjisWin());
        $this->assertWriteStreamFilterNotSame($expected_test_data_simple_text1, static::TEST_DATA_SIMPLE_TEXT1, $stream_wrapper);
        $this->assertWriteStreamFilterSame($expected_test_data_simple_text1, \mb_convert_encoding(static::TEST_DATA_SIMPLE_TEXT1, 'SJIS-win', 'UTF-8'), $stream_wrapper);
    }

    /**
     * UTF-8への変換テスト
     */
    public function testConvert2UTF8()
    {
        $expected_test_data_simple_text1    = static::TEST_DATA_SIMPLE_TEXT1;

        $stream_wrapper = array(StreamFilterConvertEncodingSpec::toUtf8());
        $this->assertWriteStreamFilterSame($expected_test_data_simple_text1, \mb_convert_encoding(static::TEST_DATA_SIMPLE_TEXT1, 'SJIS-win', 'UTF-8'), $stream_wrapper);
        $this->assertWriteStreamFilterSame($expected_test_data_simple_text1, \mb_convert_encoding(static::TEST_DATA_SIMPLE_TEXT1, 'eucJP-win', 'UTF-8'), $stream_wrapper);

        $stream_wrapper = array(StreamFilterConvertEncodingSpec::toUtf8()->fromSjisWin());
        $this->assertWriteStreamFilterSame($expected_test_data_simple_text1, \mb_convert_encoding(static::TEST_DATA_SIMPLE_TEXT1, 'SJIS-win', 'UTF-8'), $stream_wrapper);
        $this->assertWriteStreamFilterNotSame($expected_test_data_simple_text1, \mb_convert_encoding(static::TEST_DATA_SIMPLE_TEXT1, 'eucJP-win', 'UTF-8'), $stream_wrapper);

        $stream_wrapper = array(StreamFilterConvertEncodingSpec::toUtf8()->fromEucjpWin());
        $this->assertWriteStreamFilterNotSame($expected_test_data_simple_text1, \mb_convert_encoding(static::TEST_DATA_SIMPLE_TEXT1, 'SJIS-win', 'UTF-8'), $stream_wrapper);
        $this->assertWriteStreamFilterSame($expected_test_data_simple_text1, \mb_convert_encoding(static::TEST_DATA_SIMPLE_TEXT1, 'eucJP-win', 'UTF-8'), $stream_wrapper);
    }

    /**
     * 複雑なケース1のテスト
     */
    public function testComplex1()
    {
        $stream_wrapper = array(StreamFilterConvertEncodingSpec::toUtf8()->fromSjisWin());

        $expected   = array(
            array('あかさた', 'なはまや'),
        );

        $csv_text   = 'あかさた,なはまや';
        \mb_convert_variables('SJIS-win', 'UTF-8', $csv_text);

        $stream_chunk_size  = 3;

        $this->assertCsvInputStreamFilterSame($expected, $csv_text, $stream_chunk_size, $stream_wrapper);
    }

    /**
     * 複雑なケース2のテスト
     */
    public function testComplex2()
    {
        $stream_wrapper = array(StreamFilterConvertEncodingSpec::toUtf8()->fromSjisWin());

        $expected   = array(
            array(self::TEST_DATA_SIMPLE_TEXT1, self::TEST_DATA_SIMPLE_TEXT1),
        );

        $csv_text   = \sprintf('%s,%s', self::TEST_DATA_SIMPLE_TEXT1, self::TEST_DATA_SIMPLE_TEXT1);
        \mb_convert_variables('SJIS-win', 'UTF-8', $csv_text);

        $stream_chunk_size  = 3;

        $this->assertCsvInputStreamFilterSame($expected, $csv_text, $stream_chunk_size, $stream_wrapper);
    }

    /**
     * 複雑なケース3のテスト
     */
    public function testComplex3()
    {
        $stream_wrapper = array(StreamFilterConvertEncodingSpec::toUtf8()->fromSjisWin());

        $expected   = array(
            array(static::TEST_DATA_SIMPLE_TEXT2, static::TEST_DATA_SIMPLE_TEXT2),
        );

        $csv_text   = \sprintf('%s,%s', self::TEST_DATA_SIMPLE_TEXT2, self::TEST_DATA_SIMPLE_TEXT2);
        \mb_convert_variables('SJIS-win', 'UTF-8', $csv_text);

        $stream_chunk_size  = 2;

        ConvertEncodingFilter::sjisSeparationPositionBufferSize(1);

        $this->assertCsvInputStreamFilterSame($expected, $csv_text, $stream_chunk_size, $stream_wrapper);
    }

    /**
     * 複雑なケース4のテスト
     */
    public function testComplex4()
    {
        $stream_wrapper = array(StreamFilterConvertEncodingSpec::toUtf8()->fromSjisWin());

        $expected   = array(
            array(self::TEST_DATA_SIMPLE_TEXT3, self::TEST_DATA_SIMPLE_TEXT3),
        );

        $csv_text   = \sprintf('%s,%s', self::TEST_DATA_SIMPLE_TEXT3, self::TEST_DATA_SIMPLE_TEXT3);
        \mb_convert_variables('SJIS-win', 'UTF-8', $csv_text);

        $stream_chunk_size  = 3;

        $this->assertCsvInputStreamFilterSame($expected, $csv_text, $stream_chunk_size, $stream_wrapper);
    }

    /**
     * Teardown
     */
    protected function tearDown()
    {
        ConvertEncodingFilter::endChangeSubstituteCharacter();
        ConvertEncodingFilter::endChangeLocale();
    }
}
