<?php

require_once "vendor/autoload.php";
use Helpers\Settings;
use Database\MySQLWrapper;
//自身で調査して実装↓
//|-------------------------------------------------------------------------
//| エラーが発生したときに、警告を発するのではなくmysqli_sql_exceptionをスローするよう設定
//| mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
//|
//|     $mysqli = new mysqli("127.0.0.1",Settings::env('DATABASE_USER'), Settings::env('DATABASE_USER_PASSWORD'), Settings::env('DATABASE_NAME'));
//|     $aaa= $mysqli -> get_charset();
//|     echo $mysqli->host_info . "\n";
//|     $result = $mysqli->query("CREATE TABLE test0302
//|         (worker_id    CHAR(5)       NOT NULL,
//|          name         VARCHAR(20)   NOT NULL,
//|          birthday     DATE          NOT NULL,
//|          gender       INTEGER       NOT NULL,
//|          prefecture   VARCHAR(50)   NOT NULL,
//|          hire_date    DATE          NOT NULL,
//|          department   INTEGER       NOT NULL,
//|          wage         INTEGER       NOT NULL,
//|          position     INTEGER       NOT NULL,
//|          PRIMARY KEY (worker_id));
//|     ");
//|------------------------------------------------------------------------    


//recursion ソース↓
/*
    接続の失敗時にエラーを報告し、例外をスローします。データベース接続を初期化する前にこの設定を行ってください。
    テストするには、.env設定で誤った情報を入力します。
*/
// getoptはCLIで渡された指定された引数のオプションです。値のペアの配列を返します。
// 値が渡されない場合 (例：--myArg=123、値は123) は、値はfalseになります。issetを使用してそれが存在するかどうかをチェックします。
// short_optionsは、短いオプションの文字の配列を表す文字列を取り入れます。例えばabcは -a -b -c のオプションをチェックします。ロングオプションはオプションの完全な名前です。

$opts = getopt('',['migrate']);
if(isset($opts['migrate'])){
    printf('Database migration enabled.');
    // includeはPHPファイルをインクルードして実行します(引数で指定した先のファイルを実行してくれる)
    include('Database/setup.php');//ここでsetup.phpが実行されて、SQL文が実行されて、DBが更新される。
    printf('Database migration ended.');
}


$mysqli = new MySQLWrapper();

$charset = $mysqli->get_charset();

if($charset === null) throw new Exception('Charset could be read');

// データベースの文字セット、照合順序、統計情報について取得します。
//PHP_EOLはPHPの定義済み定数であり、OSに応じて自動的に改行文字を設定してくれます。 
printf(
    "%s's charset: %s.%s",
    $mysqli->getDatabaseName(),
    $charset->charset,
    PHP_EOL
);

printf(
    "collation: %s.%s",
    $charset->collation,
    PHP_EOL
);







// 接続を閉じるには、closeメソッドを使用します。
$mysqli->close();

function echoArg($str = 'YYY') {
    echo $str."\n";
}

// YYYと出力される。
echoArg();
echoArg(null);

function makecoffee($type = "cappuccino")
{
    return "Making a cup of $type.\n";
}
echo makecoffee();
echo makecoffee(null);
echo makecoffee("espresso");


function makeyogurt($container, $flavour= "bowl")
{
    return "Making a $container of $flavour yogurt.\n";
}
 
echo makeyogurt("raspberry"); // $container に "raspberry" を指定します。$flavour ではありません。
?>