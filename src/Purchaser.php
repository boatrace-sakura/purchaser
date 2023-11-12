<?php

namespace Boatrace\Sakura;

use DI\Container;
use DI\ContainerBuilder;

/**
 * @author shimomo
 */
class Purchaser
{
    /**
     * @var \Boatrace\Sakura\MainPurchaser
     */
    protected $purchaser;

    /**
     * @var \Boatrace\Sakura\Purchaser
     */
    protected static $instance;

    /**
     * @var \DI\Container
     */
    protected static $container;

    /**
     * @param  \Boatrace\Sakura\MainPurchaser  $purchaser
     * @return void
     */
    public function __construct(MainPurchaser $purchaser)
    {
        $this->purchaser = $purchaser;
    }

    /**
     * @param  string  $name
     * @param  array   $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->purchaser, $name], $arguments);
    }

    /**
     * @param  string  $name
     * @param  array   $arguments
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments)
    {
        return call_user_func_array([self::getInstance(), $name], $arguments);
    }

    /**
     * @return \Boatrace\Sakura\Purchaser
     */
    public static function getInstance(): Purchaser
    {
        return self::$instance ?? self::$instance = (
            self::$container ?? self::$container = self::getContainer()
        )->get('Purchaser');
    }

    /**
     * @return \DI\Container
     */
    public static function getContainer(): Container
    {
        $builder = new ContainerBuilder;
        $builder->addDefinitions(__DIR__ . '/../config/definitions.php');
        return $builder->build();
    }
}
