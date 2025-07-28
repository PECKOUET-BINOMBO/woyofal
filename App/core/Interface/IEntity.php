<?php
namespace App\Core\Interface;

interface IEntity
{

    public static function toObject(array $tableau): static;
    public function toArray(Object $object): array;

}
