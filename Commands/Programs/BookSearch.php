<?php

namespace Commands\Programs;

use Commands\AbstractCommand;
use Commands\Argument;
use Database\MySQLWrapper;
use Exception;

class BookSearch extends AbstractCommand {
    protected static ?string $alias = 'book-search';
    protected static bool $requiredCommandValue = false;

    // Open Library APIのURL
    private const BASE_URL = 'https://openlibrary.org';

    public static function getArguments(): array {
        return [
            (new Argument('title'))
                ->description('Search a book by title')
                ->required(false)
                ->allowAsShort(true),
            (new Argument('isbn'))
                ->description('Search a book by ISBN')
                ->required(false)
                ->allowAsShort(true),
        ];
    }

    public function execute(): int {
        $title = $this->getArgumentValue('title');
        $isbn = $this->getArgumentValue('isbn');

        if (!$title && !$isbn) {
            $this->log("Please provide a title or an ISBN for the book search.");
            return 1;
        }

        $searchKey = $isbn ? "ISBN:{$isbn}" : "TITLE:\"{$title}\"";
        $cacheKey = $isbn ? "book-search-isbn-{$isbn}" : "book-search-title-" . urlencode($title);
        $mysql = new MySQLWrapper();

        // Check cache first
        $cachedData = $this->getCache($mysql, $cacheKey);
        if ($cachedData) {
            $this->log("Found in cache: $cachedData");
            return 0;
        }

        // Not in cache, so search Open Library
        $queryUrl = $isbn ? self::BASE_URL . "/isbn/$isbn.json" : self::BASE_URL . "/search.json?q=" . urlencode($title);
        $this->log("Querying Open Library: $queryUrl");

        $result = file_get_contents($queryUrl);
        if (!$result) {
            $this->log("No results found for $searchKey.");
            return 1;
        }

        // Cache the result
        $this->cacheData($mysql, $cacheKey, $result);
        $this->log("Result cached for $searchKey.");

        // Output result
        $this->log("Result: $result");
        return 0;
    }
 
    public function getCache(MySQLWrapper $mysql, string $cacheKey): ?string {
        $stmt = $mysql->prepare("SELECT value FROM cache WHERE 'key' = ? AND DATE(updated_at) > DATE_SUB(NOW(), INTERVAL 30 DAY)");
        $stmt->bind_param('s', $cacheKey);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['value'];
        }
        return null;
    }

    private function cacheData(MySQLWrapper $mysql, string $cacheKey, string $data): void {
        // クエリの準備
        $stmt = $mysql->prepare("INSERT INTO cache (`key`, `value`, `created_at`, `updated_at`) VALUES (?, ?, NOW(), NOW()) ON DUPLICATE KEY UPDATE `value` = ?, `updated_at` = NOW();");
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $this->$mysql->error);
        }
    
        // パラメータのバインド
        $stmt->bind_param('sss', $cacheKey, $data, $data);
        
        // クエリの実行
        $stmt->execute();
    
        // ステートメントのクローズ
        $stmt->close();
    }

    // ログメッセージを出力
    protected function log(string $message): void {
        echo $message . PHP_EOL;
    }
}
