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

namespace project_ickx\php_for_everyone\streams\filters\utilitys\specs\interfaces;

/**
 * ストリームフィルタ設定を扱うインターフェースです。
 */
interface StreamFilterSpecInterface
{
    /**
     * このインスタンスを複製し返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\specs\interfaces\StreamFilterSpecInterface  複製されたこのインスタンス
     */
    public function with();

    /**
     * チェーンフィルタ用文字列を構築して返します。
     *
     * @return  string  チェーンフィルタ用文字列
     */
    public function build();

    /**
     * フィルタストリーム設定文字列を構築し返します。
     *
     * @return  string  フィルタストリーム設定文字列を構築し返します。
     */
    public function __toString();

    /**
     * __invoke
     *
     * @return  string  フィルタストリーム設定文字列を構築し返します。
     */
    public function __invoke();
}
