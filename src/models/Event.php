<?php

class Event
{
    private string $id;
    private string $nom;
    private string $description;

    public function __construct(string $id, string $nom, string $description)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
