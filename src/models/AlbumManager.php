<?php

require_once ('Album.php');
require_once ('Manager.php');
require_once ('Song.php');

class AlbumManager extends Manager
{
    private static ?\PDO $cnx = null;

    /*
     * FONCTION POUR BACK-OFFICE
     * Récupère la liste des albums de la base de données (système de pagination pour limiter le nombre d'albums affichés par page)
     * @param int $limit
     * @param int $page
     * @return Album[]
     */
    public static function GetAlbums(int $limit, int $page)
    {
        $albums = [];
        self::$cnx = self::connect();
        $req = 'select album.id,album.nom,prix,uriImage,dateSortie,GetAlbumStock(album.id) as qte,label.nom as nomLabel, artiste.nom as nomArtiste from album'
            . ' left join label on album.idLabel = label.id'
            . ' left join artiste on album.idArtiste = artiste.id'
            . ' limit :limit';
        if ($page > 1) {
            $req .= ' offset :offset';
        }
        $result = self::$cnx->prepare($req);
        $result->bindParam(':limit', $limit, PDO::PARAM_INT);
        if ($page > 1) {
            $offset = ($page - 1) * $limit;
            $result->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);
        while ($album = $result->fetch(PDO::FETCH_ASSOC)) {
            $albums[] = new Album($album['id'], $album['nom'], $album['uriImage'], $album['prix'], $album['nomLabel'], $album['nomArtiste'], null, null, $album['qte'], new DateTime($album['dateSortie']));
        }
        return $albums;
    }

    /*
     * FONCTION POUR BACK-OFFICE
     * Récupère le nombre total d'albums dans la base de données
     * @return int
     */
    public static function GetAlbumsCount()
    {
        self::$cnx = self::connect();
        $req = 'select count(id) as count from album';
        $result = self::$cnx->prepare($req);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $total = $result->fetch();
        return $total['count'];
    }

    public static function GetQteInCart($idAlbum)
    {
        self::$cnx = self::connect();
        $req = 'select sum(qte) as qte from cart where idAlbum = :idAlbum';
        $result = self::$cnx->prepare($req);
        $result->bindParam(':idAlbum', $idAlbum, PDO::PARAM_INT);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $qte = $result->fetch();
        echo $qte['qte'];
        return $qte['qte'];
    }

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

    public static function GetAlbumInfo(int $id): Album
    {
        self::$cnx = self::connect();
        $req = 'select album.id, album.nom, description, lienXFD, prix, uriImage, (Select GetAlbumStock(album.id)) as qte, dateSortie, label.nom as nomLabel, artiste.nom as nomArtiste from album'
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

    public static function GetCurrentStock(int $id): int
    {
        self::$cnx = self::connect();
        $req = 'select GetAlbumStock(:id) as qte';
        $result = self::$cnx->prepare($req);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $stock = $result->fetch();
        return $stock['qte'];
    }
}
