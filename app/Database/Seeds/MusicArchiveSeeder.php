<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MusicArchiveSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');

        // --- Users ---
        $this->db->table('user')->insert([
            'username'      => 'admin',
            'email'         => 'admin@example.com',
            'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
            'role'          => 'admin',
            'created_at'    => $now,
            'updated_at'    => $now,
        ]);
        $adminId = $this->db->insertID();

        $this->db->table('user')->insert([
            'username'      => 'user1',
            'email'         => 'user1@example.com',
            'password_hash' => password_hash('heslo123', PASSWORD_DEFAULT),
            'role'          => 'user',
            'created_at'    => $now,
            'updated_at'    => $now,
        ]);
        $userId = $this->db->insertID();

        // --- Genres ---
        $genres = ['Rock', 'Metal', 'Pop', 'Jazz', 'Electronic', 'Hip-Hop'];
        $genreIds = [];
        foreach ($genres as $name) {
            $this->db->table('genre')->insert([
                'name'       => $name,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $genreIds[$name] = $this->db->insertID();
        }

        // --- Artists ---
        $this->db->table('artist')->insert([
            'name'         => 'Metallica',
            'bio'          => '<p>Americká thrash metalová kapela, jedna z nejúspěšnějších kapel v historii hard rocku a heavy metalu.</p>',
            'country'      => 'USA',
            'formed_year'  => 1981,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $metallicaId = $this->db->insertID();
        $this->db->table('artist_genre')->insertBatch([
            ['artist_id' => $metallicaId, 'genre_id' => $genreIds['Rock']],
            ['artist_id' => $metallicaId, 'genre_id' => $genreIds['Metal']],
        ]);

        $this->db->table('artist')->insert([
            'name'         => 'Daft Punk',
            'bio'          => '<p>Francouzské elektronické duo, průkopníci house music a elektronické taneční hudby.</p>',
            'country'      => 'Francie',
            'formed_year'  => 1993,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $daftPunkId = $this->db->insertID();
        $this->db->table('artist_genre')->insert([
            'artist_id' => $daftPunkId,
            'genre_id'  => $genreIds['Electronic'],
        ]);

        $this->db->table('artist')->insert([
            'name'         => 'Miles Davis',
            'bio'          => '<p>Americký jazzový trumpetista a skladatel, jeden z nejvlivnějších hudebníků 20. století.</p>',
            'country'      => 'USA',
            'formed_year'  => 1944,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $milesId = $this->db->insertID();
        $this->db->table('artist_genre')->insert([
            'artist_id' => $milesId,
            'genre_id'  => $genreIds['Jazz'],
        ]);

        // --- Albums ---
        $this->db->table('album')->insert([
            'artist_id'    => $metallicaId,
            'title'        => 'Master of Puppets',
            'release_date' => '1986-03-03',
            'label'        => 'Elektra Records',
            'description'  => '<p>Třetí studiové album kapely Metallica, považované za jedno z nejlepších metalových alb všech dob.</p>',
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $masterAlbumId = $this->db->insertID();

        $this->db->table('album')->insert([
            'artist_id'    => $metallicaId,
            'title'        => 'The Black Album',
            'release_date' => '1991-08-12',
            'label'        => 'Elektra Records',
            'description'  => '<p>Páté studiové album, které přineslo kapele masový úspěch a prodalo se přes 30 milionů kopií.</p>',
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $blackAlbumId = $this->db->insertID();

        $this->db->table('album')->insert([
            'artist_id'    => $daftPunkId,
            'title'        => 'Random Access Memories',
            'release_date' => '2013-05-17',
            'label'        => 'Columbia Records',
            'description'  => '<p>Čtvrté studiové album, které získalo Grammy za Album roku. Návrat k živým nástrojům a disco zvuku.</p>',
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $ramAlbumId = $this->db->insertID();

        $this->db->table('album')->insert([
            'artist_id'    => $milesId,
            'title'        => 'Kind of Blue',
            'release_date' => '1959-08-17',
            'label'        => 'Columbia Records',
            'description'  => '<p>Jedno z nejprodávanějších jazzových alb všech dob. Průkopnické album modálního jazzu.</p>',
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);
        $kindOfBlueId = $this->db->insertID();

        // --- Tracks ---
        $masterTracks = [
            [1, 'Battery', 313],
            [2, 'Master of Puppets', 515],
            [3, 'The Thing That Should Not Be', 396],
            [4, 'Welcome Home (Sanitarium)', 387],
            [5, 'Disposable Heroes', 497],
            [6, 'Leper Messiah', 341],
            [7, 'Orion', 508],
            [8, 'Damage, Inc.', 330],
        ];
        foreach ($masterTracks as [$num, $title, $dur]) {
            $this->db->table('track')->insert([
                'album_id'     => $masterAlbumId,
                'track_number' => $num,
                'title'        => $title,
                'duration'     => $dur,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        $ramTracks = [
            [1, 'Give Life Back to Music', 274],
            [2, 'The Game of Love', 229],
            [3, 'Giorgio by Moroder', 549],
            [4, 'Within', 229],
            [5, 'Instant Crush', 337],
            [6, 'Lose Yourself to Dance', 353],
            [7, 'Touch', 498],
            [8, 'Get Lucky', 369],
            [9, 'Beyond', 279],
            [10, 'Motherboard', 343],
            [11, 'Fragments of Time', 291],
            [12, 'Doin\' It Right', 256],
            [13, 'Contact', 395],
        ];
        foreach ($ramTracks as [$num, $title, $dur]) {
            $this->db->table('track')->insert([
                'album_id'     => $ramAlbumId,
                'track_number' => $num,
                'title'        => $title,
                'duration'     => $dur,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        $kindTracks = [
            [1, 'So What', 562],
            [2, 'Freddie Freeloader', 588],
            [3, 'Blue in Green', 337],
            [4, 'All Blues', 692],
            [5, 'Flamenco Sketches', 566],
        ];
        foreach ($kindTracks as [$num, $title, $dur]) {
            $this->db->table('track')->insert([
                'album_id'     => $kindOfBlueId,
                'track_number' => $num,
                'title'        => $title,
                'duration'     => $dur,
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        // --- Reviews ---
        $this->db->table('review')->insert([
            'album_id'   => $masterAlbumId,
            'user_id'    => $adminId,
            'rating'     => 10,
            'body'       => 'Absolutní klasika thrash metalu. Každá skladba je mistrovské dílo.',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->db->table('review')->insert([
            'album_id'   => $ramAlbumId,
            'user_id'    => $adminId,
            'rating'     => 9,
            'body'       => 'Skvělý návrat Daft Punk. Get Lucky je bezesporu hit dekády.',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $this->db->table('review')->insert([
            'album_id'   => $kindOfBlueId,
            'user_id'    => $userId,
            'rating'     => 10,
            'body'       => 'Nejlepší jazzové album, které kdy bylo nahrané. Nadčasové.',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        echo "Seeder dokončen.\n";
        echo "Admin: admin@example.com / admin123\n";
        echo "User:  user1@example.com / heslo123\n";
    }
}
