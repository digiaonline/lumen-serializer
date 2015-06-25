<?php namespace Nord\Lumen\Serializer\Facades;

use Illuminate\Support\Facades\Facade;

class Serializer extends Facade
{

    /**
     * @inheritdoc
     */
    protected static function getFacadeAccessor()
    {
        return 'JMS\Serializer\Serializer';
    }
}
