<?php
class Song
{
    private string $id;
    private string $title;
    private string $artist;

    function __construct(string $id, string $title, string $artist)
    {
        $this->id = $id;
        $this->title = $title;
        $this->artist = $artist;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getArtist(): string
    {
        return $this->artist;
    }
}
