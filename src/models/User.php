<?php

class User
{
    private string $id;
    private string $prenom;
    private string $nom;
    private string $mail;
    private int $idRole;
    private DateTime $dateInscription;

    function __construct(int $id, string $prenom, string $nom, string $mail, int $idRole, DateTime $dateInscription = new DateTime())
    {
        $this->id = $id;
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->mail = $mail;
        $this->idRole = $idRole;
        $this->dateInscription = $dateInscription;
    }

    // region Accesseurs
    public function GetId(): string
    {
        return $this->id;
    }

    public function GetPrenom(): string
    {
        return $this->prenom;
    }

    public function GetNom(): string
    {
        return $this->nom;
    }

    public function GetMail(): string
    {
        return $this->mail;
    }

    public function GetIdRole(): int
    {
        return $this->idRole;
    }

    public function GetDateInscription(): DateTime
    {
        return $this->dateInscription;
    }

    public function SetDateInscription(DateTime $dateInscription)
    {
        $this->dateInscription = $dateInscription;
    }

    // endregion
}

?>