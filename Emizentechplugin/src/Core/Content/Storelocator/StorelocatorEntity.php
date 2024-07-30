<?php
namespace Emizen\Core\Content\Storelocator;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;

class StorelocatorEntity extends Entity
{
protected string $city;
protected string $lat;
protected string $long;
public function getCity(): string
{
return $this->city;
}

public function setCity(string $city): void
{
$this->city = $city;
}

public function getLat(): string
{
return $this->lat;
}

public function setLat(string $lat): void
{
$this->lat = $lat;
}
public function getLong(): string
{
    return $this->long;
}

public function setLong(string $long): void
{
    $this->long = $long;
}
}
