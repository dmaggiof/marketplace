<?php
namespace ProductFixtures;

use Faker\Factory;
use Faker\Provider\Base;

class CustomProductNamesProvider extends Base
{
    protected static $productNames = [
        'Laptop',
        'Teléfono inteligente',
        'Tableta',
        'Cámara digital',
        'Auriculares inalámbricos',
        'Reloj inteligente',
        'Robot aspirador',
        'Altavoz Bluetooth',
        'Drone',
        'Impresora 3D',
        'Videojuego'
    ];

    public function productName()
    {
        return static::randomElement(static::$productNames);
    }
}
