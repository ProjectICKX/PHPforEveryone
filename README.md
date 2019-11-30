# みんなのPHP 現場で役立つ最新ノウハウ！  サポートリポジトリ

ここは2019/12/06に技術評論社より発売された、「[みんなのPHP 現場で役立つ最新ノウハウ！：書籍案内｜技術評論社](https://gihyo.jp/book/2019/978-4-297-11055-0)  第7章　厳選！　PHP活用テクニック紹介」<br>
「**7.3 ストリームフィルタを用いた透過的なCSV入出力**」のサポートリポジトリです。

## サンプルコード

**7.3 ストリームフィルタを用いた透過的なCSV入出力**で紹介したサンプルコードにコメントを付与したソースコードです。

- [リスト1 標準出力の例](https://github.com/ProjectICKX/PHPforEveryone/blob/master/sample/01.php)
- [リスト2 メモリ経由での入出力の例](https://github.com/ProjectICKX/PHPforEveryone/blob/master/sample/02.php)
- [リスト3 入力した内容を大文字にするフィルタ](https://github.com/ProjectICKX/PHPforEveryone/blob/master/sample/03.php)
- [リスト4 簡易的なエンコード変換フィルタをかけsample.csvとして出力](https://github.com/ProjectICKX/PHPforEveryone/blob/master/sample/04.php)
- [リスト5 簡易的なエンコード変換フィルタ＋改行フィルタをかけsample.csvとして出力](https://github.com/ProjectICKX/PHPforEveryone/blob/master/sample/05.php)
- [リスト6 簡易的なエンコード変換フィルタかけ先ほど出力したsample.csvを読み込む](https://github.com/ProjectICKX/PHPforEveryone/blob/master/sample/06.php)
- [リスト7 フィルタの後付け追加](https://github.com/ProjectICKX/PHPforEveryone/blob/master/sample/07.php)
- [リスト8 HTTPプロトコルでの読み込み](https://github.com/ProjectICKX/PHPforEveryone/blob/master/sample/08.php)

## 実用向けストリームフィルタ

PHP5.3.3以降で使用できる、**安全に日本語を扱えるエンコーディング変換**およびや**安全に行末の改行コードを変換**できるストリームフィルタを扱っています。

詳細は[README_StreamFilter.md](README_StreamFilter.md)を参照してください。
