<?php
class User
{
    private string $id;
    private string $prenom;
    private string $nom;
    private string $mail;
    private ?string $phone;
    private ?DateTime $dateNaissance;

    function __construct(int $id, string $prenom, string $nom, string $mail, ?DateTime $dateNaissance, ?string $phone)
    {
        $this->id = $id;
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->mail = $mail;
        $this->dateNaissance = $dateNaissance;
        $this->phone = $phone;
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

    public function GetPhone(): ?string
    {
        return $this->phone;
    }

    public function GetDateNaissance(): ?DateTime
    {
        return $this->dateNaissance;
    }

    public function SetPrenom(string $prenom)
    {
        $this->prenom = $prenom;
    }

    public function SetNom(string $nom)
    {
        $this->nom = $nom;
    }

    public function SetMail(string $mail)
    {
        $this->mail = $mail;
    }

    public function SetPhone(string $phone)
    {
        $this->phone = $phone;
    }

    public function SetDateNaissance(DateTime $dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;
    }

    // endregion
}

?>