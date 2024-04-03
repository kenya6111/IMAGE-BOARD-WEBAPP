<?php
    
namespace Database\Migrations;
    
use Database\SchemaMigration;
    
class CreatePostTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return ["CREATE TABLE Post (
            post_id INT AUTO_INCREMENT PRIMARY KEY,
            reply_to_id INT NULL,
            subject VARCHAR(255) NULL,
            content TEXT NOT NULL,
            file_path VARCHAR(256),
            file_name VARCHAR(256),
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            FOREIGN KEY (reply_to_id) REFERENCES Post(post_id) ON DELETE SET NULL
        );
        "];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ["DROP TABLE Post"];
    }
}