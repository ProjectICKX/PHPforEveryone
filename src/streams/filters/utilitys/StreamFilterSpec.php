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

namespace project_ickx\php_for_everyone\streams\filters\utilitys;

use project_ickx\php_for_everyone\streams\filters\utilitys\entitys\StreamFilterSpecEntity;
use project_ickx\php_for_everyone\streams\filters\utilitys\specs\StreamFilterConvertEncodingSpec;
use project_ickx\php_for_everyone\streams\filters\utilitys\specs\StreamFilterConvertLinefeedSpec;

/**
 * ストリームフィルタ設定を扱うクラスです。
 */
abstract class StreamFilterSpec
{
    //==============================================
    // const
    //==============================================
    // フィルタパラメータ
    //----------------------------------------------
    /**
     * @var string  フィルタパラメータ間のセパレータ
     */
    const PARAMETER_SEPARATOR        = StreamFilterSpecEntity::PARAMETER_SEPARATOR;

    /**
     * @var string  パラメータチェーン間のセパレータ
     */
    const PARAMETER_CHAIN_SEPARATOR  = StreamFilterSpecEntity::PARAMETER_CHAIN_SEPARATOR;

    /**
     * @var string  パラメータオプション間のセパレータ
     */
    const PARAMETER_OPTION_SEPARATOR = StreamFilterSpecEntity::PARAMETER_OPTION_SEPARATOR;

    //----------------------------------------------
    // resource
    //----------------------------------------------
    /**
     * @var string  リソース名：stdin
     */
    const RESOURCE_PHP_STDIN     = StreamFilterSpecEntity::RESOURCE_PHP_STDIN;

    /**
     * @var string  リソース名：strout
     */
    const RESOURCE_PHP_STDOUT    = StreamFilterSpecEntity::RESOURCE_PHP_STDOUT;

    /**
     * @var string  リソース名：strerr
     */
    const RESOURCE_PHP_STDERR    = StreamFilterSpecEntity::RESOURCE_PHP_STDERR;

    /**
     * @var string  リソース名：input
     */
    const RESOURCE_PHP_INPUT     = StreamFilterSpecEntity::RESOURCE_PHP_INPUT;

    /**
     * @var string  リソース名：output
     */
    const RESOURCE_PHP_OUTPUT    = StreamFilterSpecEntity::RESOURCE_PHP_OUTPUT;

    /**
     * @var string  リソース名：fd
     */
    const RESOURCE_PHP_FD        = StreamFilterSpecEntity::RESOURCE_PHP_FD;

    /**
     * @var string  リソース名：memory
     */
    const RESOURCE_PHP_MEMORY    = StreamFilterSpecEntity::RESOURCE_PHP_MEMORY;

    /**
     * @var string  リソース名：temp
     */
    const RESOURCE_PHP_TEMP      = StreamFilterSpecEntity::RESOURCE_PHP_TEMP;

    //==============================================
    // static method
    //==============================================
    /**
     * ストリームフィルタスペックエンティティを返します。
     *
     * @param   array   $spec   スペック
     *  [
     *      'resource'  => フィルタの対象となるストリーム
     *      'write'     => 書き込みチェーンに適用するフィルタのリスト
     *      'read'      => 読み込みチェーンに適用するフィルタのリスト
     *      'both'      => 書き込みチェーン、読み込みチェーン双方に適用するフィルタのリスト
     *  ]
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\entitys\StreamFilterSpecEntity  ストリームフィルタスペックエンティティ
     */
    public static function factory($spec = array())
    {
        return StreamFilterSpecEntity::factory($spec);
    }

    /**
     * エンコーディング変換ストリームフィルタセットアッパー
     *
     * @param   string  $filter_name    登録するフィルタ名
     */
    public static function registerConvertEncodingFilter($filter_name = StreamFilterConvertEncodingSpec::DEFAULT_FILTER_NAME)
    {
        StreamFilterConvertEncodingSpec::filterName($filter_name);
        \stream_filter_register(StreamFilterConvertEncodingSpec::registerFilterName(), '\project_ickx\php_for_everyone\streams\filters\ConvertEncodingFilter');
    }

    /**
     * 改行コード変換ストリームフィルタセットアッパー
     *
     * @param   string  $filter_name    登録するフィルタ名
     */
    public static function registerConvertLinefeedFilter($filter_name = StreamFilterConvertLinefeedSpec::DEFAULT_FILTER_NAME)
    {
        StreamFilterConvertLinefeedSpec::filterName($filter_name);
        \stream_filter_register(StreamFilterConvertLinefeedSpec::registerFilterName(), '\project_ickx\php_for_everyone\streams\filters\ConvertLienfeedFilter');
    }

    /**
     * フィルタの対象となるストリームを設定したストリームフィルタスペックエンティティを返します。
     *
     * @param   string|\SplFileInfo|\SplFileObject  $resource       フィルタの対象となるストリーム
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\entitys\StreamFilterSpecEntity  フィルタの対象となるストリームを設定したストリームフィルタスペックエンティティ
     */
    public static function resource($resource)
    {
        return static::factory()->resource($resource);
    }

    /**
     * フィルタの対象となるphp://stdinストリームを設定したストリームフィルタスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\entitys\StreamFilterSpecEntity  フィルタの対象となるphp://stdinストリームを設定したストリームフィルタスペックエンティティ
     */
    public static function resourceStdin()
    {
        return static::factory()->resourceStdin();
    }

    /**
     * フィルタの対象となるphp://stdoutストリームを設定したストリームフィルタスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\entitys\StreamFilterSpecEntity  フィルタの対象となるphp://stdoutストリームを設定したストリームフィルタスペックエンティティ
     */
    public static function resourceStdout()
    {
        return static::factory()->resourceStdout();
    }

    /**
     * フィルタの対象となるphp://inputストリームを設定したストリームフィルタスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\entitys\StreamFilterSpecEntity  フィルタの対象となるphp://inputストリームを設定したストリームフィルタスペックエンティティ
     */
    public static function resourceInput()
    {
        return static::factory()->resourceInput();
    }

    /**
     * フィルタの対象となるphp://outputストリームを設定したストリームフィルタスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\entitys\StreamFilterSpecEntity  フィルタの対象となるphp://outputストリームを設定したストリームフィルタスペックエンティティ
     */
    public static function resourceOutput()
    {
        return static::factory()->resourceOutput();
    }

    /**
     * フィルタの対象となるphp://fdストリームを設定したストリームフィルタスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\entitys\StreamFilterSpecEntity  フィルタの対象となるphp://fdストリームを設定したストリームフィルタスペックエンティティ
     */
    public static function resourceFd()
    {
        return static::factory()->resourceFd();
    }

    /**
     * フィルタの対象となるphp://memoryストリームを設定したストリームフィルタスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\entitys\StreamFilterSpecEntity  フィルタの対象となるphp://memoryストリームを設定したストリームフィルタスペックエンティティ
     */
    public static function resourceMemory()
    {
        return static::factory()->resourceMemory();
    }

    /**
     * フィルタの対象となるphp://tempストリームを設定したストリームフィルタスペックエンティティを返します。
     *
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\entitys\StreamFilterSpecEntity  フィルタの対象となるphp://tempストリームを設定したストリームフィルタスペックエンティティ
     */
    public static function resourceTemp()
    {
        return static::factory()->resourceTemp();
    }

    /**
     * 書き込みチェーンに適用するフィルタのリストを設定したストリームフィルタスペックエンティティを返します。
     *
     * @param   array   $write  書き込みチェーンに適用するフィルタのリスト
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\entitys\StreamFilterSpecEntity  書き込みチェーンに適用するフィルタのリストを設定したストリームフィルタスペックエンティティ
     */
    public static function write($write)
    {
        return static::factory()->write($write);
    }

    /**
     * 読み込みチェーンに適用するフィルタのリストを設定したストリームフィルタスペックエンティティを返します。
     *
     * @param   array   $read   読み込みチェーンに適用するフィルタのリスト
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\entitys\StreamFilterSpecEntity  読み込みチェーンに適用するフィルタのリストを設定したストリームフィルタスペックエンティティ
     */
    public static function read($read)
    {
        return static::factory()->read($read);
    }

    /**
     * 書き込みチェーン、読み込みチェーン双方に適用するフィルタのリストを設定したストリームフィルタスペックエンティティを返します。
     *
     * @param   array   $both   書き込みチェーン、読み込みチェーン双方に適用するフィルタのリスト
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\entitys\StreamFilterSpecEntity  書き込みチェーン、読み込みチェーン双方に適用するフィルタのリストを設定したストリームフィルタスペックエンティティ
     */
    public static function both($both)
    {
        return static::factory()->both($both);
    }

    /**
     * 書き込みチェーンに適用するフィルタを追加したストリームフィルタスペックエンティティを返します。
     *
     * @param   \project_ickx\php_for_everyone\streams\filters\utilitys\specs\interfaces\StreamFilterSpecInterface|string   $filter 書き込みストリームフィルタ名
     * @param   array   $filter_parameters          ストリームフィルタに追加するパラメータ
     * @param   string  $filter_parameter_separator ストリームフィルタに追加するパラメータオプションのセパレータ
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\entitys\StreamFilterSpecEntity  書き込みチェーンに適用するフィルタを追加したストリームフィルタスペックエンティティ
     */
    public static function appendWriteChain($filter, $filter_parameters = array(), $filter_parameter_separator = self::PARAMETER_OPTION_SEPARATOR)
    {
        return static::factory()->appendWriteChain($filter, $filter_parameters, $filter_parameter_separator);
    }

    /**
     * 読み込みチェーンに適用するフィルタを追加したストリームフィルタスペックエンティティを返します。
     *
     * @param   \project_ickx\php_for_everyone\streams\filters\utilitys\specs\interfaces\StreamFilterSpecInterface|string   $filter 読み込みストリームフィルタ名
     * @param   array   $filter_parameters          ストリームフィルタに追加するパラメータ
     * @param   string  $filter_parameter_separator ストリームフィルタに追加するパラメータオプションのセパレータ
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\entitys\StreamFilterSpecEntity  読み込みチェーンに適用するフィルタを追加したストリームフィルタスペックエンティティ
     */
    public static function appendReadChain($filter, $filter_parameters = array(), $filter_parameter_separator = self::PARAMETER_OPTION_SEPARATOR)
    {
        return static::factory()->appendReadChain($filter, $filter_parameters, $filter_parameter_separator);
    }

    /**
     * 書き込みチェーン、読み込みチェーン双方に適用するフィルタを追加したストリームフィルタスペックエンティティを返します。
     *
     * @param   \project_ickx\php_for_everyone\streams\filters\utilitys\specs\interfaces\StreamFilterSpecInterface|string   $filter 書き込みチェーン、読み込みチェーン双方に適用するフィルタ名。
     * @param   array   $filter_parameters          ストリームフィルタに追加するパラメータ
     * @param   string  $filter_parameter_separator ストリームフィルタに追加するパラメータオプションのセパレータ
     * @return  \project_ickx\php_for_everyone\streams\filters\utilitys\entitys\StreamFilterSpecEntity  書き込みチェーン、読み込みチェーン双方に適用するフィルタを追加したストリームフィルタスペックエンティティ
     */
    public static function appendBothChain($filter, $filter_parameters = array(), $filter_parameter_separator = self::PARAMETER_OPTION_SEPARATOR)
    {
        return static::factory()->appendBothChain($filter, $filter_parameters, $filter_parameter_separator);
    }
}
