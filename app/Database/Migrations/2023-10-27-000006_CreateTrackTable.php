<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateTrackTable extends Migration {
    public function up() {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'auto_increment' => true],
            'album_id'     => ['type' => 'INT'],
            'title'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'duration'     => ['type' => 'INT', 'null' => true],
            'track_number' => ['type' => 'TINYINT', 'null' => true],
            'lastfm_mbid'  => ['type' => 'CHAR', 'constraint' => 36, 'null' => true, 'unique' => true],
            'lastfm_url'   => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'created_at'   => ['type' => 'DATETIME'],
            'updated_at'   => ['type' => 'DATETIME'],
            'deleted_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('album_id', 'album', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('track');
    }
    public function down() { $this->forge->dropTable('track', true); }
}
