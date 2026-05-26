<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class CreateUserTable extends Migration {
    public function up() {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'auto_increment' => true],
            'username'      => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'email'         => ['type' => 'VARCHAR', 'constraint' => 150, 'unique' => true],
            'password_hash' => ['type' => 'VARCHAR', 'constraint' => 255],
            'role'          => ['type' => 'ENUM', 'constraint' => ['user', 'admin'], 'default' => 'user'],
            'created_at'    => ['type' => 'DATETIME'],
            'updated_at'    => ['type' => 'DATETIME'],
            'deleted_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('user');
    }
    public function down() { $this->forge->dropTable('user', true); }
}
