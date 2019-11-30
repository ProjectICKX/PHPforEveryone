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
 * リスト4 簡易的なエンコード変換フィルタをかけsample.csvとして出力
 */

/**
 * 簡易的なエンコード変換フィルタです。
 *
 * \stream_filter_registerにフィルタ変換クラスを登録するには\php_user_filterを継承する必要があります。
 * @see https://www.php.net/manual/ja/class.php-user-filter.php
 * @see https://www.php.net/manual/ja/function.stream-filter-register.php
 */
class EncodeFilter extends \php_user_filter
{
    /**
     * @var string  変換後エンコーディング
     */
    protected $toEncoding   = '';

    /**
     * @var string  変換前エンコーディング
     */
    protected $fromEncoding = '';

    /**
     * フィルタクラスのオブジェクトインスタンスが生成されるときにコールされるメソッドです。
     *
     * @return  bool    instance生成に成功した場合はtrue、そうでなければfalse (falseを返した場合、フィルタの登録が失敗したものと見なされる)
     *
     * {@inheritDoc}
     * @see \php_user_filter::onCreate()
     */
    public function onCreate()
    {
        if (false === \strpos($this->filtername, '.')) {
            return false;
        }
        $filter_name    = \explode('.', $this->filtername);
        $options        = \explode(':', isset($filter_name[1]) ? $filter_name[1] : ':');
        list($this->toEncoding, $this->fromEncoding) = array(isset($options[0]) ? $options[0] : 'UTF-8', isset($options[1]) ? $options[1] : 'SJIS-win');
        return true;
    }

    /**
     * ストリームとの間でデータの読み書き（\fread()や\fwrite()など）を行ったときにコールされるメソッドです。
     *
     * @param   resource    $in         元のバケットオブジェクト
     * @param   resource    $out        変更内容を適用するためのバケットオブジェクト
     * @param   int         $consumed   変更したデータ長
     * @param   bool        $closing    フィルタチェインの最後の処理かどうか
     * @return  int         処理を終えたときの状態
     *     \PSFS_PASS_ON                 ：フィルタの処理が成功し、データがoutバケット群に保存された
     *     \PSFS_FEED_ME                 ：フィルタの処理は成功したが、返すデータがない。ストリームあるいは一つ前のフィルタから、追加のデータが必要
     *     \PSFS_ERR_FATAL (デフォルト)  ：フィルタで対処不能なエラーが発生し、処理を続行できない
     *
     * {@inheritDoc}
     * @see php_user_filter::filter()
     */
    public function filter($in, $out, &$consumed, $closing)
    {
        while ($bucket = \stream_bucket_make_writeable($in)) {
            $bucket->data   = \mb_convert_encoding($bucket->data, $this->toEncoding, $this->fromEncoding);
            $consumed       += $bucket->datalen + \strlen($bucket->data) - $bucket->datalen;
            \stream_bucket_append($out, $bucket);
        }
        return \PSFS_PASS_ON;
    }
}

/**
 * 簡易的なエンコード変換フィルタをかけsample.csvとして出力
 */
\stream_filter_register('encode.*', 'EncodeFilter');
$fp = \fopen('php://filter/write=encode.SJIS-win:UTF-8/resource=sample.csv', 'wb');
\fputcsv($fp, array('1行目', 'ストリームフィルタを用いた変換性の髙いCSV入出力'));
\fputcsv($fp, array('2行目', '①㈱ソ'));
