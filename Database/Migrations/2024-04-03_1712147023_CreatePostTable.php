<?php
    
namespace Database\Migrations;
    
use Database\SchemaMigration;
    
class CreatePostTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return ["CREATE TABLE Posts (
            post_id INT AUTO_INCREMENT PRIMARY KEY,
            reply_to_id INT NULL,
            subject VARCHAR(255) NULL,
            content TEXT NOT NULL,
            file_path VARCHAR(256),
            file_name VARCHAR(256),
            mime_type VARCHAR(256),
            size INT,
            url VARCHAR(256),
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (reply_to_id) REFERENCES Posts(post_id) ON DELETE SET NULL
        );
        "];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してくださｓい
        return ["DROP TABLE Posts"];
    }
}