<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateArtistGenreTable extends Migration {
    public function up() {
        $this->forge->addField([
            'artist_id' => ['type' => 'INT'],
            'genre_id'  => ['type' => 'INT'],
        ]);
        $this->forge->addPrimaryKey(['artist_id', 'genre_id']);
        $this->forge->addForeignKey('artist_id', 'artist', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('genre_id', 'genre', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('artist_genre');
    }
    public function down() { $this->forge->dropTable('artist_genre', true); }
}
