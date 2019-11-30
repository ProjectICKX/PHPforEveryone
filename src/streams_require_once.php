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
 * Stream関連ファイル一括読み込み。
 */
require_once sprintf('%s/streams/filters/utilitys/specs/interfaces/StreamFilterSpecInterface.php', __DIR__);
require_once sprintf('%s/streams/filters/utilitys/specs/entitys/StreamFilterConvertLinefeedSpecEntity.php', __DIR__);
require_once sprintf('%s/streams/filters/utilitys/specs/entitys/StreamFilterConvertEncodingSpecEntity.php', __DIR__);
require_once sprintf('%s/streams/filters/utilitys/specs/StreamFilterConvertLinefeedSpec.php', __DIR__);
require_once sprintf('%s/streams/filters/utilitys/specs/StreamFilterConvertEncodingSpec.php', __DIR__);
require_once sprintf('%s/streams/filters/utilitys/entitys/StreamFilterSpecEntity.php', __DIR__);
require_once sprintf('%s/streams/filters/utilitys/StreamFilterSpec.php', __DIR__);
require_once sprintf('%s/streams/filters/ConvertLienFeedFilter.php', __DIR__);
require_once sprintf('%s/streams/filters/ConvertEncodingFilter.php', __DIR__);
