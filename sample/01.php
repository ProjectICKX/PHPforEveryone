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
 * リスト1 標準出力の例
 */
// \fopenでの例
$fp = \fopen('php://output', 'wb');
\fwrite($fp, 'ストリームフィルタを用いたcsv入出力'); // \print('ストリームフィルタを用いたcsv入出力');やecho 'ストリームフィルタを用いたcsv入出力';と同じ出力を行います。

// \SplFileObjectでの例
$output = new \SplFileObject('php://output', 'wb');
$output->fwrite('ストリームフィルタを用いたcsv入出力'); // \fopenでの例と同じ出力を行います。
