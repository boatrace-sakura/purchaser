<?php

namespace Boatrace\Sakura\Tests;

use Boatrace\Sakura\Purchaser;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * @author shimomo
 */
class PurchaserTest extends PHPUnitTestCase
{
    /**
     * @doesNotPerformAssertions
     * @return void
     */
    public function testPurchaser(): void
    {
        Purchaser::setDepositAmount(1000)
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
