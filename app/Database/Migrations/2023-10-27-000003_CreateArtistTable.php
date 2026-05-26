<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateArtistTable extends Migration {
    public function up() {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'photo'       => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'bio'         => ['type' => 'LONGTEXT', 'null' => true],
            'country'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'formed_year' => ['type' => 'YEAR', 'null' => true],
            'lastfm_mbid' => ['type' => 'CHAR', 'constraint' => 36, 'null' => true, 'unique' => true],
            'created_at'  => ['type' => 'DATETIME'],
            'updated_at'  => ['type' => 'DATETIME'],
            'deleted_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('artist');
    }
    public function down() { $this->forge->dropTable('artist', true); }
}
