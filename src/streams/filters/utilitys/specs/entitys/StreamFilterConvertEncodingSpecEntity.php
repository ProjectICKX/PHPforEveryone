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

namespace project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys;

use project_ickx\php_for_everyone\streams\filters\ConvertEncodingFilter;
use project_ickx\php_for_everyone\streams\filters\utilitys\specs\StreamFilterConvertEncodingSpec;
use project_ickx\php_for_everyone\streams\filters\utilitys\specs\interfaces\StreamFilterSpecInterface;

/**
 * ストリームフィルタスペックエンティティ：ConvertEncodingFilter
 */
class StreamFilterConvertEncodingSpecEntity implements StreamFilterSpecInterface
{
    //==============================================
    // const
    //==============================================
    // フィルタ名
    //----------------------------------------------
    /**
     * @var string  デフォルトフィルタ名
     */
    const DEFAULT_FILTER_NAME    = 'convert.encoding';

    //----------------------------------------------
    // フィルタパラメータ
    //----------------------------------------------
    /**
     * @var string  パラメータオプション間のセパレータ
     */
    const PARAMETER_OPTION_SEPARATOR = ':';

    //----------------------------------------------
    // Encoding
    //----------------------------------------------
    /**
     * @var string  変換元のエンコーディング：省略された場合のデフォルト値 （より精度の高い文字エンコーディング判定を行う）
     */
    const FROM_ENCODING_DEFAULT  = ConvertEncodingFilter::FROM_ENCODING_DEFAULT;

    /**
     * @var string  変換元のエンコーディング：auto
     */
    const FROM_ENCODING_AUTO     = ConvertEncodingFilter::FROM_ENCODING_AUTO;

    /**
     * @var array   変換元文字列に対してエンコーディング検出を行う変換元エンコーディングマップ
     */
    public static $DETECT_FROM_ENCODING_MAP   = array(
        ConvertEncodingFilter::FROM_ENCODING_DEFAULT    => ConvertEncodingFilter::FROM_ENCODING_DEFAULT,
        ConvertEncodingFilter::FROM_ENCODING_AUTO       => ConvertEncodingFilter::FROM_ENCODING_AUTO,
    );

    /**
     * @var string  日本語処理系で多用するエンコーディング：UTF-8
     */
    const ENCODING_NAME_UTF8         = ConvertEncodingFilter::ENCODING_NAME_UTF8;

    /**
     * @var string  日本語処理系で多用するエンコーディング：Shift_JIS（Windows-31J）
     */
    const ENCODING_NAME_SJIS_WIN     = ConvertEncodingFilter::ENCODING_NAME_SJIS_WIN;

    /**
     * @var string  日本語処理系で多用するエンコーディング：EUC-JP（Windows-31JのEUC-JP互換表現）
     */
    const ENCODING_NAME_EUCJP_WIN    = ConvertEncodingFilter::ENCODING_NAME_EUCJP_WIN;

    /**
     * @var string  デフォルトの変換後文字エンコーディング
     */
    const DEFAULT_TO_ENCODING    = self::ENCODING_NAME_UTF8;

    /**
     * @var string  デフォルトの変換前文字エンコーディング
     */
    const DEFAULT_FROM_ENCODING  = self::FROM_ENCODING_DEFAULT;

    //==============================================
    // property
    //==============================================
    // フィルタパラメータ
    //----------------------------------------------
    /**
     * @var array   変換後の文字エンコーディング
     */
    protected $toEncoding   = self::DEFAULT_TO_ENCODING;

    /**
     * @var string  変換前の文字エンコーディング
     */
    protected $fromEncoding = self::DEFAULT_FROM_ENCODING;

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
        $instance   = new static();

        if (!empty($spec)) {
            if (isset($spec['to_encoding']) || array_key_exists('to_encoding', $spec)) {
                $instance->to($spec['to_encoding']);
            }

            if (isset($spec['from_encoding']) || array_key_exists('from_encoding', $spec)) {
                $instance->from($spec['from_encoding']);
            }
        }

        return $instance;
    }

    //==============================================
    // method
    //==============================================
    /**
     * 変換後の文字エンコーディングを取得・設定します。
     *
     * @param   null|string $to_encoding    変換後の文字エンコーディング
     * @return  string|\project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity  変換後の文字エンコーディングまたはこのインスタンス
     */
    public function to($to_encoding = null)
    {
        if (\func_num_args() === 0) {
            return $this->toEncoding;
        }

        if (!\in_array($to_encoding, \mb_list_encodings(), true)) {
            throw new \Exception(\sprintf('未知の文字エンコーディングを指定されました。encoding:%s', $to_encoding));
        }

        $this->toEncoding = $to_encoding;
        return $this;
    }

    /**
     * 変換後の文字エンコーディングをUTF8として設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity    変換後の文字エンコーディングをUTF8として設定したスペックエンティティ
     */
    public function toUtf8()
    {
        return $this->to(static::ENCODING_NAME_UTF8);
    }

    /**
     * 変換後の文字エンコーディングをSJIS-winとして設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity    変換後の文字エンコーディングをSJIS-winとして設定したスペックエンティティ
     */
    public function toSjisWin()
    {
        return $this->to(static::ENCODING_NAME_SJIS_WIN);
    }

    /**
     * 変換後の文字エンコーディングをeucJP-winとして設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity    変換後の文字エンコーディングをeucJP-winとして設定したスペックエンティティ
     */
    public function toEucJpWin()
    {
        return $this->to(static::ENCODING_NAME_EUCJP_WIN);
    }

    /**
     * 変換前の文字エンコーディングを取得・設定します。
     *
     * @param   null|string $from_encoding  変換前の文字エンコーディング
     * @return  string|\project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity     変換前の文字エンコーディングまたはこのインスタンス
     */
    public function from($from_encoding = null)
    {
        if (\func_num_args() === 0) {
            return $this->fromEncoding;
        }

        if (!\in_array($from_encoding, static::$DETECT_FROM_ENCODING_MAP, true) && !\in_array($from_encoding, \mb_list_encodings(), true)) {
            throw new \Exception(\sprintf('未知の文字エンコーディングを指定されました。encoding:%s', $from_encoding));
        }

        $this->fromEncoding = $from_encoding;
        return $this;
    }

    /**
     * 変換前の文字エンコーディングをUTF8として設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity 変換前の文字エンコーディングをUTF8として設定したスペックエンティティ
     */
    public function fromUtf8()
    {
        return $this->from(static::ENCODING_NAME_UTF8);
    }

    /**
     * 変換前の文字エンコーディングをSJIS-winとして設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity 変換前の文字エンコーディングをSJIS-winとして設定したスペックエンティティ
     */
    public function fromSjisWin()
    {
        return $this->from(static::ENCODING_NAME_SJIS_WIN);
    }

    /**
     * 変換前の文字エンコーディングをeucJP-winとして設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity 変換前の文字エンコーディングをeucJP-winとして設定したスペックエンティティ
     */
    public function fromEucjpWin()
    {
        return $this->from(static::ENCODING_NAME_EUCJP_WIN);
    }

    /**
     * 変換前の文字エンコーディングをdefaultとして設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity 変換前の文字エンコーディングをdefaultとして設定したスペックエンティティ
     */
    public function fromDefault()
    {
        return $this->from(static::FROM_ENCODING_DEFAULT);
    }

    /**
     * 変換前の文字エンコーディングをautoとして設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity 変換前の文字エンコーディングをautoとして設定したスペックエンティティ
     */
    public function fromAuto()
    {
        return $this->from(static::FROM_ENCODING_AUTO);
    }

    /**
     * チェーンフィルタ用文字列を構築して返します。
     *
     * @return  string  チェーンフィルタ用文字列
     */
    public function build()
    {
        return sprintf('%s.%s%s%s', StreamFilterConvertEncodingSpec::filterName(), $this->toEncoding, static::PARAMETER_OPTION_SEPARATOR, $this->fromEncoding);
    }

    /**
     * Shift_JIS出力用の設定を行います。
     *
     * @param   string  $from_encoding  変換前文字列エンコーディング
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity このインスタンス
     */
    public function setupForSjisOut($from_encoding = self::DEFAULT_FROM_ENCODING)
    {
        return $this->to(static::ENCODING_NAME_SJIS_WIN)->from($from_encoding);
    }

    /**
     * EUC-JP出力用の設定を行います。
     *
     * @param   string  $from_encoding  変換前文字列エンコーディング
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity このインスタンス
     */
    public function setupForEucjpOut($from_encoding = self::DEFAULT_FROM_ENCODING)
    {
        return $this->to(static::ENCODING_NAME_EUCJP_WIN)->from($from_encoding);
    }

    /**
     * UTF-8出力用の設定を行います。
     *
     * @param   string  $from_encoding  変換前文字列エンコーディング
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertEncodingSpecEntity このインスタンス
     */
    public function setupForUtf8Out($from_encoding = self::DEFAULT_FROM_ENCODING)
    {
        return $this->to(static::ENCODING_NAME_UTF8)->from($from_encoding);
    }

    //==============================================
    // StreamFilterSpecInterface
    //==============================================
    /**
     * constructor
     */
    protected function __construct()
    {
    }

    /**
     * このインスタンスを複製し返します。
     *
     * @return  StreamFilterConvertEncodingSpecEntity   複製されたこのインスタンス
     */
    public function with()
    {
        return clone $this;
    }

    /**
     * フィルタストリーム設定文字列を構築し返します。
     *
     * @return  string  フィルタストリーム設定文字列を構築し返します。
     */
    public function __toString()
    {
        return $this->build();
    }

    /**
     * __invoke
     *
     * @return  string  フィルタストリーム設定文字列を構築し返します。
     */
    public function __invoke()
    {
        return $this->build();
    }
}
