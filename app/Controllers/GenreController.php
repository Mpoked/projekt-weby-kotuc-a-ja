<?php

namespace App\Controllers;

use App\Models\GenreModel;

/**
 * GenreController
 *
 * Stará se o zobrazení hudebních žánrů.
 * Veřejné stránky – žánry nelze přidávat přes web (spravují se přes Seeder / DB).
 */
class GenreController extends BaseController
{
    protected GenreModel $genreModel;

    public function __construct()
    {
        $this->genreModel = new GenreModel();
    }

    /**
     * Zobrazí seznam všech žánrů jako kartičky.
     */
    public function index(): string
    {
        $db = \Config\Database::connect();

        $genres = $db->table('genre')
            ->select('genre.*, COUNT(artist_genre.artist_id) AS artist_count')
            ->join('artist_genre', 'artist_genre.genre_id = genre.id', 'left')
            ->where('genre.deleted_at IS NULL')
            ->groupBy('genre.id')
            ->orderBy('genre.name', 'ASC')
            ->get()->getResultObject();

        return view('genre/index', [
            'title'  => 'Žánry',
            'genres' => $genres,
        ]);
    }

    /**
     * Zobrazí detail jednoho žánru včetně umělců, kteří do něj patří.
     *
     * @param int $id  ID žánru
     */
    public function show(int $id): string
    {
        $genre = $this->genreModel->find($id);
        if (! $genre) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Žánr #$id nenalezen.");
        }

        $db = \Config\Database::connect();
        $artists = $db->table('artist')
            ->select('artist.*')
            ->join('artist_genre', 'artist_genre.artist_id = artist.id')
            ->where('artist_genre.genre_id', $id)
            ->where('artist.deleted_at IS NULL')
            ->orderBy('artist.name', 'ASC')
            ->get()->getResultObject();

        return view('genre/show', [
            'title'   => $genre->name,
            'genre'   => $genre,
            'artists' => $artists,
        ]);
    }
}
