<?php
return [
    //::classでそのクラスファイルの完全修飾名（省略なしのパス）を返す（文字列）
    Commands\Programs\Migrate::class,
    Commands\Programs\CodeGeneration::class,
    Commands\Programs\Dbwipe::class,
    Commands\Programs\BookSearch::class,
    Commands\Programs\StateMigrate::class,
    Commands\Programs\Seed::class
];
?>