<?php
/**  ___ _  _ ___  __         ___
 *  | _ | || | _ \/ _|___ _ _| ____ _____ _ _ _  _ ___ _ _  ___
 *  |  _| __ |  _|  _/ _ | '_| _|\ V / -_| '_| || / _ | ' \/ -_)
 *  |_| |_||_|_| |_| \___|_| |___|\_/\___|_|  \_, \___|_||_\___|
 *                                            |__/
 *
 * PHPforEveryone: sample
 *
 * @category    stream filter
 * @package     sample
 * @author      akira wakaba <wakabadou@gmail.com>
 * @copyright   Copyright (c) @2019  Wakabadou (http://www.wakabadou.net/) / Project ICKX (https://ickx.jp/). All rights reserved.
 * @license     http://opensource.org/licenses/MIT The MIT License.
 *              This software is released under the MIT License.
 * @varsion     1.0.0
 */

namespace project_ickx\php_for_everyone\streams\filters\utilitys\specs;

use project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity;

/**
 * ストリームフィルタ：ConvertEncodingFilterSpec
 */
abstract class StreamFilterConvertEncodingSpec
{
    //==============================================
    // const
    //==============================================
    // フィルタ名
    //----------------------------------------------
    /**
     * @var string  デフォルトフィルタ名
     */
    const DEFAULT_FILTER_NAME    = StreamFilterConvertEncodingSpecEntity::DEFAULT_FILTER_NAME;

    //----------------------------------------------
    // フィルタパラメータ
    //----------------------------------------------
    /**
     * @var string  パラメータオプション間のセパレータ
     */
    const PARAMETER_OPTION_SEPARATOR = StreamFilterConvertEncodingSpecEntity::PARAMETER_OPTION_SEPARATOR;

    //----------------------------------------------
    // Encoding
    //----------------------------------------------
    /**
     * @var string  変換元のエンコーディング：省略された場合のデフォルト値 （より精度の高い文字エンコーディング判定を行う）
     */
    const FROM_ENCODING_DEFAULT  = StreamFilterConvertEncodingSpecEntity::FROM_ENCODING_DEFAULT;

    /**
     * @var string  変換元のエンコーディング：auto
     */
    const FROM_ENCODING_AUTO     = StreamFilterConvertEncodingSpecEntity::FROM_ENCODING_AUTO;

    /**
     * @var array   変換元文字列に対してエンコーディング検出を行う変換元エンコーディングマップ
     */
    public static $DETECT_FROM_ENCODING_MAP   = array(
        StreamFilterConvertEncodingSpecEntity::FROM_ENCODING_DEFAULT    => StreamFilterConvertEncodingSpecEntity::FROM_ENCODING_DEFAULT,
        StreamFilterConvertEncodingSpecEntity::FROM_ENCODING_AUTO       => StreamFilterConvertEncodingSpecEntity::FROM_ENCODING_AUTO,
    );

    /**
     * @var string  日本語処理系で多用するエンコーディング：UTF-8
     */
    const ENCODING_NAME_UTF8         = StreamFilterConvertEncodingSpecEntity::ENCODING_NAME_UTF8;

    /**
     * @var string  日本語処理系で多用するエンコーディング：Shift_JIS（Windows-31J）
     */
    const ENCODING_NAME_SJIS_WIN     = StreamFilterConvertEncodingSpecEntity::ENCODING_NAME_SJIS_WIN;

    /**
     * @var string  日本語処理系で多用するエンコーディング：EUC-JP（Windows-31JのEUC-JP互換表現）
     */
    const ENCODING_NAME_EUCJP_WIN    = StreamFilterConvertEncodingSpecEntity::ENCODING_NAME_EUCJP_WIN;

    /**
     * @var string  デフォルトの変換後文字エンコーディング
     */
    const DEFAULT_TO_ENCODING    = StreamFilterConvertEncodingSpecEntity::ENCODING_NAME_UTF8;

    /**
     * @var string  デフォルトの変換前文字エンコーディング
     */
    const DEFAULT_FROM_ENCODING  = StreamFilterConvertEncodingSpecEntity::FROM_ENCODING_DEFAULT;

    //==============================================
    // static property
    //==============================================
    // フィルタ名
    //----------------------------------------------
    /**
     * @var string  フィルタ名
     * @staticvar
     */
    protected static $filterName    = StreamFilterConvertEncodingSpecEntity::DEFAULT_FILTER_NAME;

    //==============================================
    // static method
    //==============================================
    /**
     * ストリームフィルタスペックインスタンスを返します。
     *
     * @param   array   $spec   スペック
     *  [
     *      'to_encoding'   => 変換後のエンコーディング
     *      'from_encoding' => 変換元のエンコーディング
     *  ]
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity このインスタンス
     */
    public static function factory($spec = array())
    {
        return StreamFilterConvertEncodingSpecEntity::factory($spec);
    }

    /**
     * フィルタ名を取得・設定します。
     *
     * @param   string  $filter_name    フィルタ名
     * @return  string  フィルタ名またはこのクラスパス
     */
    public static function filterName($filter_name = null)
    {
        if (\func_num_args() === 0) {
            return static::$filterName;
        }

        static::$filterName = $filter_name;
        return get_called_class();
    }

    /**
     * \stream_filter_register設定用フィルタ名を返します。
     *
     * @return  string  \stream_filter_register設定用フィルタ名
     */
    public static function registerFilterName()
    {
        return \sprintf('%s.*', static::filterName());
    }

    //==============================================
    // method
    //==============================================
    /**
     * 変換後の文字エンコーディングを設定したスペックエンティティを返します。
     *
     * @param   string  $to_encoding    変換後の文字エンコーディング
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity 変換後の文字エンコーディングを設定したスペックエンティティ
     */
    public static function to($to_encoding)
    {
        return static::factory()->to($to_encoding);
    }

    /**
     * 変換後の文字エンコーディングをUTF8として設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity 変換後の文字エンコーディングをUTF8として設定したスペックエンティティ
     */
    public static function toUtf8()
    {
        return static::factory()->toUtf8();
    }

    /**
     * 変換後の文字エンコーディングをSJIS-winとして設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity 変換後の文字エンコーディングをSJIS-winとして設定したスペックエンティティ
     */
    public static function toSjisWin()
    {
        return static::factory()->toSjisWin();
    }

    /**
     * 変換後の文字エンコーディングをeucJP-winとして設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity 変換後の文字エンコーディングをeucJP-winとして設定したスペックエンティティ
     */
    public static function toEucJpWin()
    {
        return static::factory()->toEucJpWin();
    }

    /**
     * 変換前の文字エンコーディングを設定したスペックエンティティを返します。
     *
     * @param   string  $from_encoding  変換前の文字エンコーディング
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity 変換前の文字エンコーディングを設定したスペックエンティティ
     */
    public static function from($from_encoding)
    {
        return static::factory()->from($from_encoding);
    }

    /**
     * 変換前の文字エンコーディングをUTF8として設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity 変換前の文字エンコーディングをUTF8として設定したスペックエンティティ
     */
    public static function fromUtf8()
    {
        return static::factory()->fromUtf8();
    }

    /**
     * 変換前の文字エンコーディングをSJIS-winとして設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity 変換前の文字エンコーディングをSJIS-winとして設定したスペックエンティティ
     */
    public static function fromSjisWin()
    {
        return static::factory()->fromSjisWin();
    }

    /**
     * 変換前の文字エンコーディングをeucJP-winとして設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity 変換前の文字エンコーディングをeucJP-winとして設定したスペックエンティティ
     */
    public static function fromEucjpWin()
    {
        return static::factory()->fromEucjpWin();
    }

    /**
     * 変換前の文字エンコーディングをdefaultとして設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity 変換前の文字エンコーディングをdefaultとして設定したスペックエンティティ
     */
    public static function fromDefault()
    {
        return static::factory()->fromDefault();
    }

    /**
     * 変換前の文字エンコーディングをautoとして設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity 変換前の文字エンコーディングをautoとして設定したスペックエンティティ
     */
    public static function fromAuto()
    {
        return static::factory()->fromAuto();
    }

    /**
     * Shift_JIS出力用の設定を行ったスペックエンティティを返します。
     *
     * @param   string  $from_encoding  変換前文字列エンコーディング
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity Shift_JIS出力用の設定を行ったスペックエンティティ
     */
    public static function setupForSjisOut($from_encoding = self::DEFAULT_FROM_ENCODING)
    {
        return static::factory()->setupForSjisOut($from_encoding);
    }

    /**
     * EUC-JP出力用の設定を行ったスペックエンティティを返します。
     *
     * @param   string  $from_encoding  変換前文字列エンコーディング
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity EUC-JP出力用の設定を行ったスペックエンティティ
     */
    public static function setupForEucjpOut($from_encoding = self::DEFAULT_FROM_ENCODING)
    {
        return static::factory()->setupForEucjpOut($from_encoding);
    }

    /**
     * UTF-8出力用の設定を行ったスペックエンティティを返します。
     *
     * @param   string  $from_encoding  変換前文字列エンコーディング
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity UTF-8出力用の設定を行ったスペックエンティティ
     */
    public static function setupForUtf8Out($from_encoding = self::DEFAULT_FROM_ENCODING)
    {
        return static::factory()->setupForUtf8Out($from_encoding);
    }
}
