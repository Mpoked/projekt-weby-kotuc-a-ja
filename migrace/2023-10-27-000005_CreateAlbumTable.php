<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateAlbumTable extends Migration {
    public function up() {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'auto_increment' => true],
            'artist_id'    => ['type' => 'INT'],
            'title'        => ['type' => 'VARCHAR', 'constraint' => 255],
            'release_date' => ['type' => 'DATE', 'null' => true],
            'cover_image'  => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'description'  => ['type' => 'LONGTEXT', 'null' => true],
            'label'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'lastfm_mbid'  => ['type' => 'CHAR', 'constraint' => 36, 'null' => true, 'unique' => true],
            'lastfm_url'   => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'created_at'   => ['type' => 'DATETIME'],
            'updated_at'   => ['type' => 'DATETIME'],
            'deleted_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('artist_id', 'artist', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('album');
    }
    public function down() { $this->forge->dropTable('album', true); }
}
