<?php
namespace Commands\Programs;

use Commands\AbstractCommand;
use Commands\Argument;
use Exception;
class DbWipe extends AbstractCommand {

    protected static ?string $alias = 'db-wipe';
    protected static bool $requiredCommandValue = false;

    public static function getArguments(): array {
        return [
            (new Argument('backup'))
                ->description('Create a backup before wiping the database')
                ->required(false)
                ->allowAsShort(true),
        ];
    }

    public function execute(): int {
        try {
            // バックアップが要求された場合
            if ($this->getArgumentValue('backup')) {
                $this->log("Creating database backup...");
                $backupFile = 'backup-' . date('YmdHis') . '.sql';
                exec("mysqldump -u recursion -p practice_db > $backupFile", $output, $returnVar);
                if ($returnVar !== 0) {
                    throw new Exception("Failed to create database backup.");
                }
                $this->log("Backup created: $backupFile");
            }

            // データベースのワイプを実行
            $this->log("Wiping the database...");
            exec("mysql -u recursion -p mysql -e 'DROP DATABASE IF EXISTS testdb2; CREATE DATABASE testdb2;'", $output, $returnVar);
            if ($returnVar !== 0) {
                throw new Exception("Failed to wipe the database.");
            }
            $this->log("Database wiped successfully.");

            return 0; // 成功
        } catch (Exception $e) {
            $this->log("Error: " . $e->getMessage());
            return 1; // 失敗
        }
    }

    // ログメッセージを出力
    protected function log(string $message): void {
        echo $message . PHP_EOL;
    }

}


?>
