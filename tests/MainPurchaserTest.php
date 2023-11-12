<?php

namespace Boatrace\Sakura\Tests;

use Boatrace\Sakura\MainPurchaser;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * @author shimomo
 */
class MainPurchaserTest extends PHPUnitTestCase
{
    /**
     * @var \Boatrace\Sakura\MainPurchaser
     */
    protected $purchaser;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->purchaser = new MainPurchaser;
    }

    /**
     * @doesNotPerformAssertions
     * @return void
     */
    public function testPurchaser(): void
    {
        $this->purchaser
            ->setDepositAmount(1000)
            ->setSubscriberNumber(getenv('SUBSCRIBER_NUMBER'))
            ->setPersonalIdentificationNumber(getenv('PERSONAL_IDENTIFICATION_NUMBER'))
            ->setAuthenticationPassword(getenv('AUTHENTICATION_PASSWORD'))
            ->setPurchasePassword(getenv('PURCHASE_PASSWORD'))
            ->purchase(24, 12, 6, [
                123 => 500,
                124 => 500,
            ]);
    }
}
