<?php

class Cart
{
    private int $id;
    private int $userId;
    private array $items;

    function __construct(int $id, int $userId, array $items = [])
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->items = $items;
    }

    // region Accesseurs
    public function GetId(): int
    {
        return $this->id;
    }

    public function GetUserId(): int
    {
        return $this->userId;
    }

    public function GetItems(): array
    {
        return $this->items;
    }
}
