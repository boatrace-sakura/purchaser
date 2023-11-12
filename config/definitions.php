<?php

return [
    'Purchaser' => \DI\create('\Boatrace\Sakura\Purchaser')->constructor(
        \DI\get('MainPurchaser')
    ),
    'MainPurchaser' => function ($container) {
        return $container->get('\Boatrace\Sakura\MainPurchaser');
    },
    'ChromeOptions' => function ($container) {
        return $container->get('\Facebook\WebDriver\Chrome\ChromeOptions');
    },
];
