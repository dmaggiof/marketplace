<?php
namespace ProductFixtures;
use Faker\Factory;
use Faker\Provider\Base;

class CustomProductDescriptionsProvider extends Base
{
    protected static $descriptions = [
        'El último gadget tecnológico que cambiará tu vida para siempre.',
        'Una experiencia única que no querrás perderte.',
        'Diseño elegante y funcionalidad incomparable.',
        'Ideal para los amantes de la aventura y la naturaleza.',
        'La solución perfecta para tu día a día.',
        'Calidad superior al mejor precio del mercado.',
        'Una obra maestra de la ingeniería moderna.',
        'Descubre un mundo de posibilidades con este producto.',
        'La combinación perfecta de estilo y rendimiento.'
    ];

    public function productDescription()
    {
        return static::randomElement(static::$descriptions);
    }
}
