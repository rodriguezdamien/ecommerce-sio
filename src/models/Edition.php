<?php

class Edition
{
    private string $idEvent;
    private int $numEdition;
    private int $annee;

    public function __construct(string $idEvent, int $numEdition, int $annee)
    {
        $this->idEvent = $idEvent;
        $this->numEdition = $numEdition;
        $this->annee = $annee;
    }

    public function getIdEvent(): string
    {
        return $this->idEvent;
    }

    public function getNumEdition(): int
    {
        return $this->numEdition;
    }

    public function getAnnee(): int
    {
        return $this->annee;
    }
}
