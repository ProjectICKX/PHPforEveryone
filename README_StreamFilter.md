# 実用向けストリームフィルタ

[fw3/streams: Flywheel3 stream library](https://github.com/fw3/streams)で公開しているStreamFilterをPHP5.3.3以降でも動作するようにした、実用向けのストリームフィルタです。

## 対象バージョンおよび動作確認バージョン

対象バージョン：PHP5.3.3以降

### 動作確認バージョン

- **5.3.3**
- 5.3.4
- 5.3.5
- 5.3.6
- 5.3.7
- 5.3.8
- **5.3.9**
- 5.4.16
- 5.4.39
- **5.4.45**
- **5.5.38**
- **5.6.40**
- **7.0.33**
- **7.1.33**
- **7.2.25**
- **7.3.12**
- **7.4.0**

5.3.3以降の各マイナーバージョンの最新バージョンとロカールの取り扱いが変わるタイミングでのバージョンに対して動作確認を行っています。

### 設定などの注意点

#### Windows (php7.2.0未満)

php.iniの次の行のコメントを除去してください。

```diff
- ; extension_dir = "ext"
+ extension_dir = "ext"
```

```diff
- ;extension=php_mbstring.dll
+ extension=php_mbstring.dll
```

#### Windows (php7.2.0以上)

php.iniの次の行のコメントを除去してください。

```diff
- ; extension_dir = "ext"
+ extension_dir = "ext"
```

```diff
- ;extension=mbstring
+ extension=mbstring
```

#### Linux系 (パッケージマネージャ使用)

各種パッケージマネージャで`php-mbstring`またはそれに類するものをインストールしてください。

#### Linux系 (phpenv使用)

`default_configure_options`または各definitionに次の一つを追加してください。

```
--enable-mbstring
```

#### Linux系 (ソースコードからビルド)

configureオプションに次の一つを追加してください。
詳細は[PHP マニュアル 関数リファレンス 自然言語および文字エンコーディング マルチバイト文字列 インストール/設定](https://www.php.net/manual/ja/mbstring.installation.php)を参照してください。

```
--enable-mbstring
```

## 使い方

### 1 . ファイルをインストールしてください

#### composerを使用できる環境の場合

次のコマンドを実行し、インストールしてください。

`composer require project_ickx/php_for_everyone`

#### composerを使用できない環境の場合

[Download ZIP](https://github.com/ProjectICKX/PHPforEveryone/archive/master.zip)よりzipファイルをダウンロードし、任意のディレクトリにコピーしてください。

使用対象となる処理より前に`require_once sprintf('%s/src/streams_require_once.php', $path_to_copy_dir);`として`src/streams_require_once.php`を読み込むようにしてください。

### 2. ストリームフィルタへの登録を行います

```php
//----------------------------------------------
// フィルタ登録
//----------------------------------------------
// 引数を使用することでお好きなフィルタ名を設定することができます。
//
// StreamFilterSpec::registerConvertEncodingFilter(StreamFilterConvertEncodingSpec::DEFAULT_FILTER_NAME);
// StreamFilterSpec::registerConvertLinefeedFilter(StreamFilterConvertLinefeedSpec::DEFAULT_FILTER_NAME);
//----------------------------------------------
StreamFilterSpec::registerConvertEncodingFilter();
StreamFilterSpec::registerConvertLinefeedFilter();
```

### 3. CSV出力

実行前にロカールの設定と、実行後にロカールの設定を戻すことを**必ず**行ってください。

```php
<?php
// 実行時のロカールと代替文字設定を先行して設定します
\ConvertEncodingFilter::startChangeLocale();
\ConvertEncodingFilter::startChangeSubstituteCharacter();

// フィルタ設定の構築：書き込み用として UTF-8 => SJIS-win、任意の行末改行コード => CRLF に変換するストリームフィルタ設定を構築する。
$spec   = StreamFilterSpec::resource($path_to_csv)->write([
    StreamFilterConvertEncodingSpec::toSjisWin()->fromUtf8(),
    StreamFilterConvertLinefeedSpec::toCrLf()->fromAll(),
]);

// CSVファイルの出力
$fp     = \fopen($spec->build(), 'wb');
foreach ($rows as $row) {
    \fputcsv($fp, $row);
}
\fclose($fp);

// 実行時のロカールと代替文字設定を元に戻します
\ConvertEncodingFilter::endChangeSubstituteCharacter();
\ConvertEncodingFilter::endChangeLocale();
```

### 4. CSV入力

```php
<?php
// 実行時のロカールと代替文字設定を先行して設定します
\ConvertEncodingFilter::startChangeLocale();
\ConvertEncodingFilter::startChangeSubstituteCharacter();

// フィルタ設定の構築：読み込み用として 任意のエンコーディング => UTF-8 に変換するストリームフィルタ設定を構築する。
$spec   = StreamFilterSpec::resource($path_to_csv)->read([
    StreamFilterConvertEncodingSpec::toUtf8()->fromDefault(),
]);

$fp     = \fopen($spec->build(), 'rb');
$rows   = [];
while ($row = \fgetcsv($fp)) {
    $rows[] = $row;
}
\fclose($fp);

// 実行時のロカールと代替文字設定を元に戻します
\ConvertEncodingFilter::endChangeSubstituteCharacter();
\ConvertEncodingFilter::endChangeLocale();
```

## ユニットテスト

次の形で`tests/test.php`を実行します。

```php
php tests/test.php
```
