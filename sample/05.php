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

/**
 * リスト5 簡易的なエンコード変換フィルタ＋改行フィルタをかけsample.csvとして出力
 */

/**
 * 簡易的な改行コード変換フィルタです。
 */
class LienFeedFilter extends \php_user_filter
{
    /**
     * @var string  改行コード文字表現：CRLF (\r\n)
     */
    const CRLF  = 'crlf';

    /**
     * @var string  改行コード文字表現：CR (\r)
     */
    const CR    = 'cr';

    /**
     * @var string  改行コード文字表現：LF (\n)
     */
    const LF    = 'lf';

    /**
     * @var string  デフォルトの変換後の改行コード文字表現
     */
    const DEFAULT_TO_LINEFEED   = self::CRLF;

    /**
     * @var array   改行コード文字表現の改行コード変換マップ
     */
    protected static $LINEFEED_MAP  = array(
        self::CRLF  => "\r\n",
        self::CR    => "\r",
        self::LF    => "\n",
    );

    /**
     * @var string  変換後の改行コード文字表現
     */
    protected $toLinefeed   = null;

    /**
     * フィルタを作成するときにコールされる。
     *
     * {@inheritDoc}
     * @see php_user_filter::onCreate()
     */
    public function onCreate()
    {
        if (false === \strpos($this->filtername, '.')) {
            return false;
        }

        $filter_name    = \explode('.', $this->filtername);
        $to_line_feed   = isset($filter_name[1]) ? $filter_name[1] : static::DEFAULT_TO_LINEFEED;

        $this->toLinefeed   = static::$LINEFEED_MAP[$to_line_feed];

        return true;
    }

    /**
     * フィルタを適用するときにコールされる。
     *
     * {@inheritDoc}
     * @see php_user_filter::filter()
     */
    public function filter($in, $out, &$consumed, $closing) {
        while ($bucket = \stream_bucket_make_writeable($in)) {
            $bucket->data = \preg_replace("/[\r\n]+$/", $this->toLinefeed, $bucket->data);
            $consumed     += $bucket->datalen + \strlen($bucket->data) - $bucket->datalen;
            \stream_bucket_append($out, $bucket);
        }
        return \PSFS_PASS_ON;
    }
}

/**
 * 簡易的なエンコード変換フィルタ＋改行フィルタをかけsample.csvとして出力
 */
\stream_filter_register('line_feed.*', 'LienFeedFilter');

$fp = \fopen('php://filter/write=encode.SJIS-win:UTF-8|line_feed.crlf/resource=sample.csv', 'wb');
\fputcsv($fp, array('1行目', 'ストリームフィルタを用いた変換性の髙いCSV入出力'));
\fputcsv($fp, array('2行目', '①㈱ソ'));
