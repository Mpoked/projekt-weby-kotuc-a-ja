<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateReviewTable extends Migration {
    public function up() {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'album_id'   => ['type' => 'INT'],
            'user_id'    => ['type' => 'INT'],
            'rating'     => ['type' => 'TINYINT'],
            'body'       => ['type' => 'LONGTEXT'],
            'created_at' => ['type' => 'DATETIME'],
            'updated_at' => ['type' => 'DATETIME'],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['user_id', 'album_id']);
        $this->forge->addForeignKey('album_id', 'album', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('review');
    }
    public function down() { $this->forge->dropTable('review', true); }
}
