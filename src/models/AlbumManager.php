<?php

require_once ('Album.php');
require_once ('Manager.php');

class AlbumManager extends Manager
{
    private static ?\PDO $cnx = null;

    public static function getRandomAlbums(int $limit): array
    {
        self::$cnx = self::connect();
        $req = 'select album.id,album.nom,prix,uriImage,label.nom as nomLabel, artiste.nom as nomArtiste from album'
            . ' left join label on album.idLabel = label.id'
            . ' left join artiste on album.idArtiste = artiste.id'
            . ' order by rand() limit :limit';
        $result = self::$cnx->prepare($req);
        $result->bindParam(':limit', $limit, PDO::PARAM_INT);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);
        while ($album = $result->fetch(PDO::FETCH_ASSOC)) {
            $albums[] = new Album($album['id'], $album['nom'], $album['uriImage'], $album['prix'], $album['nomLabel'], $album['nomArtiste'], null, null, null);
        }
        return $albums;
    }

    public static function getPreoderAlbums(int $limit)
    {
        self::$cnx = self::connect();
        $req = 'select album.id,album.nom,prix,uriImage,label.nom as nomLabel, artiste.nom as nomArtiste from album'
            . ' left join label on album.idLabel = label.id'
            . ' left join artiste on album.idArtiste = artiste.id'
            . ' where album.dateSortie > now()'
            . ' order by rand() limit :limit';
        $result = self::$cnx->prepare($req);
        $result->bindParam(':limit', $limit, PDO::PARAM_INT);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);
        while ($album = $result->fetch(PDO::FETCH_ASSOC)) {
            $albums[] = new Album($album['id'], $album['nom'], $album['uriImage'], $album['prix'], $album['nomLabel'], $album['nomArtiste'], null, null, null);
        }
        return $albums;
    }
}
