<?php
require_once 'src/models/CartManager.php';
require_once 'src/models/Cart.php';

class User
{
    private string $id;
    private string $prenom;
    private string $nom;
    private string $mail;
    private ?string $phone;
    private ?DateTime $dateNaissance;
    private Cart $cart;

    function __construct(int $id, string $prenom, string $nom, string $mail, ?DateTime $dateNaissance, ?string $phone)
    {
        $this->id = $id;
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->mail = $mail;
        $this->dateNaissance = $dateNaissance;
        $this->phone = $phone;
        $this->cart = CartManager::GetCart($id);
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

    public function GetCart(): Cart
    {
        return $this->cart;
    }

    // endregion
}

?>