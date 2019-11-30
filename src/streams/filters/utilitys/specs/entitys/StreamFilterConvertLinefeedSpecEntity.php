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

use project_ickx\php_for_everyone\streams\filters\ConvertLienfeedFilter;
use project_ickx\php_for_everyone\streams\filters\utilitys\specs\StreamFilterConvertLinefeedSpec;
use project_ickx\php_for_everyone\streams\filters\utilitys\specs\interfaces\StreamFilterSpecInterface;

/**
 * ストリームフィルタ：ConvertLinefeedSpec
 */
class StreamFilterConvertLinefeedSpecEntity implements StreamFilterSpecInterface
{
    //==============================================
    // const
    //==============================================
    // フィルタ名
    //----------------------------------------------
    /**
     * @var string  デフォルトフィルタ名
     */
    const DEFAULT_FILTER_NAME    = 'convert.linefeed';

    //----------------------------------------------
    // フィルタパラメータ
    //----------------------------------------------
    /**
     * @var string  パラメータオプション間のセパレータ
     */
    const PARAMETER_OPTION_SEPARATOR = ':';

    //----------------------------------------------
    // 改行コード表現の文字列表現
    //----------------------------------------------
    /**
     * @var string  改行コード表現の文字列表現：CRLF
     */
    const CRLF   = ConvertLienfeedFilter::STR_CRLF;

    /**
     * @var string  改行コード表現の文字列表現：CR
     */
    const CR     = ConvertLienfeedFilter::STR_CR;

    /**
     * @var string  改行コード表現の文字列表現：LF
     */
    const LF     = ConvertLienfeedFilter::STR_LF;

    /**
     * @var string  改行コード表現の文字列表現：ALL (変換元用全種類受け入れ設定)
     */
    const ALL    = ConvertLienfeedFilter::STR_ALL;

    /**
     * @var string  改行コード表現の文字列表現：変換元改行コード表現のデフォルト
     */
    const FROM_LINEFEED_DEFAULT  = self::ALL;

    /**
     * @var string  デフォルトの変換後改行コード表現
     */
    const DEFAULT_TO_LINEFEED   = self::LF;

    /**
     * @var string  デフォルトの変換前改行コード表現
     */
    const DEFAULT_FROM_LINEFEED = self::ALL;

    /**
     * @var array   文字列表現の改行から改行コードへの変換マップ
     */
    public static $LINEFEED_MAP  = array(
        ConvertLienfeedFilter::STR_CR   => ConvertLienfeedFilter::CR,
        ConvertLienfeedFilter::STR_LF   => ConvertLienfeedFilter::LF,
        ConvertLienfeedFilter::STR_CRLF => ConvertLienfeedFilter::CRLF,
    );

    /**
     * @var array   許可する変換元改行コードの文字列リスト
     */
    public static $ALLOW_FROM_LINEFEED_STR_LIST    = array(
        ConvertLienfeedFilter::STR_CR   => ConvertLienfeedFilter::STR_CR,
        ConvertLienfeedFilter::STR_LF   => ConvertLienfeedFilter::STR_LF,
        ConvertLienfeedFilter::STR_CRLF => ConvertLienfeedFilter::STR_CRLF,
        ConvertLienfeedFilter::STR_ALL  => ConvertLienfeedFilter::STR_ALL,
    );

    //==============================================
    // property
    //==============================================
    // フィルタパラメータ
    //----------------------------------------------
    /**
     * @var array   変換後の改行コード表現
     */
    protected $toLinefeed   = self::DEFAULT_TO_LINEFEED;

    /**
     * @var string  変換前の改行コード表現
     */
    protected $fromLinefeed = self::DEFAULT_FROM_LINEFEED;

    //==============================================
    // static method
    //==============================================
    /**
     * ストリームフィルタスペックインスタンスを返します。
     *
     * @param   array   $spec   スペック
     *  [
     *      'to_linefeed'   => 変換後の改行コード表現
     *      'from_linefeed' => 変換元の改行コード表現
     *  ]
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\StreamFilterConvertLinefeedSpec   このインスタンス
     */
    public static function factory($spec = array())
    {
        $instance   = new static();

        if (!empty($spec)) {
            if (isset($spec['to_linefeed']) || array_key_exists('to_linefeed', $spec)) {
                $instance->toLinefeed($spec['to_linefeed']);
            }

            if (isset($spec['from_linefeed']) || array_key_exists('from_linefeed', $spec)) {
                $instance->fromLinefeed($spec['from_linefeed']);
            }
        }

        return $instance;
    }

    //==============================================
    // method
    //==============================================
    /**
     * 変換後の改行コード表現を取得・設定します。
     *
     * @param   null|string $to_linefeed     変換後の改行コード表現
     * @return  string|\project_ickx\php_for_everyone\streams\filters\utilitys\specs\StreamFilterConvertLinefeedSpec    変換後の改行コード表現またはこのインスタンス
     */
    public function to($to_line_feed = null)
    {
        if (\func_num_args() === 0) {
            return $this->toLinefeed;
        }

        if (!isset(static::$LINEFEED_MAP[$to_line_feed])) {
            throw new \Exception(\sprintf('未知の改行コード表現を指定されました。encoding:%s', $to_line_feed));
        }

        $this->toLinefeed = $to_line_feed;
        return $this;
    }

    /**
     * 変換後の改行コード表現としてCRを設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertLinefeedSpecEntity 変換後の改行コード表現としてCRを設定したスペックエンティティ
     */
    public function toCr()
    {
        return $this->to(static::CR);
    }

    /**
     * 変換後の改行コード表現としてLFを設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertLinefeedSpecEntity 変換後の改行コード表現としてLFを設定したスペックエンティティ
     */
    public function toLf()
    {
        return $this->to(static::LF);
    }

    /**
     * 変換後の改行コード表現としてCRLFを設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertLinefeedSpecEntity 変換後の改行コード表現としてCRLFを設定したスペックエンティティ
     */
    public function toCrLf()
    {
        return $this->to(static::CRLF);
    }

    /**
     * 変換前の改行コード表現を取得・設定します。
     *
     * @param   null|string $from_linefeed   変換前の改行コード表現
     * @return  string|\project_ickx\php_for_everyone\streams\filters\utilitys\specs\StreamFilterConvertLinefeedSpec    変換前の改行コード表現またはこのインスタンス
     */
    public function from($from_line_feed = null)
    {
        if (\func_num_args() === 0) {
            return $this->fromLinefeed;
        }

        if (!isset(static::$ALLOW_FROM_LINEFEED_STR_LIST[$from_line_feed])) {
            throw new \Exception(\sprintf('未知の改行コード表現を指定されました。encoding:%s', $from_line_feed));
        }

        $this->fromLinefeed = $from_line_feed;
        return $this;
    }

    /**
     * 変換前の改行コード表現としてCRを設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertLinefeedSpecEntity 変換前の改行コード表現としてCRを設定したスペックエンティティ
     */
    public function fromCr()
    {
        return $this->from(static::CR);
    }

    /**
     * 変換前の改行コード表現としてLFを設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertLinefeedSpecEntity 変換前の改行コード表現としてLFを設定したスペックエンティティ
     */
    public function fromLf()
    {
        return $this->from(static::LF);
    }

    /**
     * 変換前の改行コード表現としてCRLFを設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertLinefeedSpecEntity 変換前の改行コード表現としてCRLFを設定したスペックエンティティ
     */
    public function fromCrLf()
    {
        return $this->from(static::CRLF);
    }

    /**
     * 変換前の改行コード表現としてALLを設定したスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\entitys\StreamFilterConvertLinefeedSpecEntity 変換前の改行コード表現としてALLを設定したスペックエンティティ
     */
    public function fromAll()
    {
        return $this->from(static::ALL);
    }

    /**
     * チェーンフィルタ用文字列を構築して返します。
     *
     * @return  string  チェーンフィルタ用文字列
     */
    public function build()
    {
        return sprintf('%s.%s%s%s', StreamFilterConvertLinefeedSpec::filterName(), $this->toLinefeed, static::PARAMETER_OPTION_SEPARATOR, $this->fromLinefeed);
    }

    /**
     * Windows用の設定を行います。
     *
     * @param   string  $from_linefeed   変換前改行コード表現文字
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\StreamFilterConvertLinefeedSpec   このインスタンス
     */
    public function setupForWindows($from_line_feed = self::DEFAULT_FROM_LINEFEED)
    {
        return $this->toCrLf()->from($from_line_feed);
    }

    /**
     * Unix系用の設定を行います。
     *
     * @param   string  $from_linefeed   変換前改行コード表現文字
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\StreamFilterConvertLinefeedSpec   このインスタンス
     */
    public function setupForUnix($from_line_feed = self::DEFAULT_FROM_LINEFEED)
    {
        return $this->toLf()->from($from_line_feed);
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
     * @return  StreamFilterConvertLinefeedSpec 複製されたこのインスタンス
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
