<?php

require_once ('Album.php');
require_once ('Manager.php');
require_once ('Song.php');

class AlbumManager extends Manager
{
    private static ?\PDO $cnx = null;

    public static function getRandomAlbums(int $limit): array
    {
        self::$cnx = self::connect();
        $req = 'select album.id,album.nom,prix,uriImage,dateSortie,label.nom as nomLabel, artiste.nom as nomArtiste from album'
            . ' left join label on album.idLabel = label.id'
            . ' left join artiste on album.idArtiste = artiste.id'
            . ' order by rand() limit :limit';
        $result = self::$cnx->prepare($req);
        $result->bindParam(':limit', $limit, PDO::PARAM_INT);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);
        while ($album = $result->fetch(PDO::FETCH_ASSOC)) {
            $albums[] = new Album($album['id'], $album['nom'], $album['uriImage'], $album['prix'], $album['nomLabel'], $album['nomArtiste'], null, null, null, new DateTime($album['dateSortie']));
        }
        return $albums;
    }

    public static function getPreoderAlbums(int $limit)
    {
        self::$cnx = self::connect();
        $req = 'select album.id,album.nom,prix,uriImage,dateSortie,label.nom as nomLabel, artiste.nom as nomArtiste from album'
            . ' left join label on album.idLabel = label.id'
            . ' left join artiste on album.idArtiste = artiste.id'
            . ' where album.dateSortie > now()'
            . ' order by rand() limit :limit';
        $result = self::$cnx->prepare($req);
        $result->bindParam(':limit', $limit, PDO::PARAM_INT);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);
        while ($album = $result->fetch(PDO::FETCH_ASSOC)) {
            $albums[] = new Album($album['id'], $album['nom'], $album['uriImage'], $album['prix'], $album['nomLabel'], $album['nomArtiste'], null, null, null, new DateTime($album['dateSortie']));
        }
        return $albums;
    }

    public static function SearchAlbumsByQuery(string $query = '', ?string $event, ?string $edition)
    {
        $albums = [];
        self::$cnx = self::connect();
        $req = 'select album.id,album.nom,prix,dateSortie,uriImage,label.nom as nomLabel, artiste.nom as nomArtiste from album'
            . ' left join label on album.idLabel = label.id'
            . ' left join artiste on album.idArtiste = artiste.id'
            . ' left join provenir on album.id = provenir.idAlbum'
            . ' where'
            . ' (album.nom like :query'
            . ' or label.nom like :query'
            . ' or artiste.nom like :query'
            . ' or provenir.idEvent like :query'
            . ' or provenir.numEdition like :query)';
        if ($event != null && $event != 'all') {
            $req .= ' and provenir.idEvent = :event';
            if ($edition != null && $edition != 'all') {
                $req .= ' and provenir.numEdition = :edition';
            }
            $req .= ' limit 100';
        }

        $result = self::$cnx->prepare($req);
        $queryFormat = '%' . $query . '%';
        $result->bindParam(':query', $queryFormat, PDO::PARAM_STR);
        if ($event != null) {
            $result->bindParam(':event', $event, PDO::PARAM_STR);
        }
        if ($edition != null) {
            $result->bindParam(':edition', $edition, PDO::PARAM_INT);
        }
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);
        while ($album = $result->fetch(PDO::FETCH_ASSOC)) {
            $albums[] = new Album($album['id'], $album['nom'], $album['uriImage'], $album['prix'], $album['nomLabel'], $album['nomArtiste'], null, null, null, new DateTime($album['dateSortie']));
        }
        return $albums;
    }

    public static function getAlbumInfo(int $id): Album
    {
        self::$cnx = self::connect();
        $req = 'select album.id, album.nom, description, lienXFD, prix, uriImage, qte, dateSortie, label.nom as nomLabel, artiste.nom as nomArtiste from album'
            . ' left join label on album.idLabel = label.id'
            . ' left join artiste on album.idArtiste = artiste.id'
            . ' where album.id = :id';
        $result = self::$cnx->prepare($req);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $album = $result->fetch();
        return new Album($album['id'], $album['nom'], $album['uriImage'], $album['prix'], $album['nomLabel'], $album['nomArtiste'], $album['description'], $album['lienXFD'], $album['qte'], new DateTime($album['dateSortie']));
    }

    public static function getAlbumSongs(int $id): array
    {
        $songs = [];
        self::$cnx = self::connect();
        $req = 'select song.id, song.nom, artiste.nom as nomArtiste'
            . ' from song'
            . ' join contenir on song.id = contenir.idSong'
            . ' join composer on song.id = composer.idSong'
            . ' join artiste on composer.idArtiste = artiste.id'
            . ' where contenir.idAlbum = :idAlbum';
        $result = self::$cnx->prepare($req);
        $result->bindParam(':idAlbum', $id, PDO::PARAM_INT);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);
        while ($song = $result->fetch()) {
            $songs[] = new Song($song['id'], $song['nom'], $song['nomArtiste']);
        }
        return $songs;
    }
}
