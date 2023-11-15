<?php

namespace Boatrace\Sakura;

use DI\Container;
use DI\ContainerBuilder;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use RuntimeException;

/**
 * @author shimomo
 */
class MainPurchaser
{
    /**
     * @var \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected $driver;

    /**
     * @var int
     */
    protected $depositAmount;

    /**
     * @var string
     */
    protected $subscriberNumber;

    /**
     * @var string
     */
    protected $personalIdentificationNumber;

    /**
     * @var string
     */
    protected $authenticationPassword;

    /**
     * @var string
     */
    protected $purchasePassword;

    /**
     * @return void
     */
    public function __construct()
    {
        $options = $this->getContainer()->get('ChromeOptions');
        $options->addArguments(['--headless']);
        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
        $this->driver = RemoteWebDriver::create('http://localhost:4444/wd/hub', $capabilities);
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        $this->driver->quit();
    }

    /**
     * @param  int  $depositAmount
     * @return \Boatrace\Sakura\MainPurchaser
     */
    public function setDepositAmount(int $depositAmount): MainPurchaser
    {
        $this->depositAmount = $depositAmount;

        return $this;
    }

    /**
     * @param  string  $subscriberNumber
     * @return \Boatrace\Sakura\MainPurchaser
     */
    public function setSubscriberNumber(string $subscriberNumber): MainPurchaser
    {
        $this->subscriberNumber = $subscriberNumber;

        return $this;
    }

    /**
     * @param  string  $personalIdentificationNumber
     * @return \Boatrace\Sakura\MainPurchaser
     */
    public function setPersonalIdentificationNumber(string $personalIdentificationNumber): MainPurchaser
    {
        $this->personalIdentificationNumber = $personalIdentificationNumber;

        return $this;
    }

    /**
     * @param  string  $authenticationPassword
     * @return \Boatrace\Sakura\MainPurchaser
     */
    public function setAuthenticationPassword(string $authenticationPassword): MainPurchaser
    {
        $this->authenticationPassword = $authenticationPassword;

        return $this;
    }

    /**
     * @param  string  $purchasePassword
     * @return \Boatrace\Sakura\MainPurchaser
     */
    public function setPurchasePassword(string $purchasePassword): MainPurchaser
    {
        $this->purchasePassword = $purchasePassword;

        return $this;
    }

    /**
     * @param  int    $stadiumId
     * @param  int    $raceNumber
     * @param  int    $purchaseType
     * @param  array  $forecasts
     * @return void
     */
    public function purchase(int $stadiumId, int $raceNumber, int $purchaseType, array $forecasts): void
    {
        $this->driver->get('https://ib.mbrace.or.jp/');

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('memberNo')));
        $this->driver->findElement(WebDriverBy::id('memberNo'))->sendKeys($this->subscriberNumber);

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('pin')));
        $this->driver->findElement(WebDriverBy::id('pin'))->sendKeys($this->personalIdentificationNumber);

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('authPassword')));
        $this->driver->findElement(WebDriverBy::id('authPassword'))->sendKeys($this->authenticationPassword);

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('loginButton')));
        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('loginButton')));
        $this->driver->findElement(WebDriverBy::id('loginButton'))->click();

        $handles = $this->driver->getWindowHandles();
        $this->driver->switchTo()->window($handles[array_key_last($handles)]);

        try {
            $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('newsoverviewdispCloseButton')));
            $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('newsoverviewdispCloseButton')));
            $this->driver->findElement(WebDriverBy::id('newsoverviewdispCloseButton'))->click();
        } catch (NoSuchElementException $exception) {}

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('gnavi01')));
        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('gnavi01')));
        $this->driver->findElement(WebDriverBy::id('gnavi01'))->click();

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('charge')));
        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('charge')));
        $this->driver->findElement(WebDriverBy::id('charge'))->click();

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('chargeInstructAmt')));
        $this->driver->findElement(WebDriverBy::id('chargeInstructAmt'))->sendKeys($this->depositAmount / 1000);

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('chargeBetPassword')));
        $this->driver->findElement(WebDriverBy::id('chargeBetPassword'))->sendKeys($this->purchasePassword);

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('executeCharge')));
        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('executeCharge')));
        $this->driver->findElement(WebDriverBy::id('executeCharge'))->click();

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('ok')));
        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('ok')));
        $this->driver->findElement(WebDriverBy::linkText('OK'))->click();

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('closeChargecomp')));
        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('closeChargecomp')));
        $this->driver->findElement(WebDriverBy::id('closeChargecomp'))->click();

        for ($depositCounter = 1; $depositCounter <= 10; ++$depositCounter) {
            $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('gnavi02')));
            $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('gnavi02')));
            $this->driver->findElement(WebDriverBy::id('gnavi02'))->click();

            $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('balref')));
            $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('balref')));
            $this->driver->findElement(WebDriverBy::id('balref'))->click();

            $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('.gray > .col3')));
            $depositAmount = (int) preg_replace('/[^0-9]/', '', $this->driver->findElement(WebDriverBy::cssSelector('.gray > .col3'))->getText());

            $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('closeBalref')));
            $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('closeBalref')));
            $this->driver->findElement(WebDriverBy::id('closeBalref'))->click();

            if ($depositAmount >= $this->depositAmount) {
                break;
            }

            if ($depositCounter === 10) {
                throw new RuntimeException('入金に失敗しました。');
            }
        }

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('jyo' . sprintf('%02d', $stadiumId))));
        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('jyo' . sprintf('%02d', $stadiumId))));
        $this->driver->findElement(WebDriverBy::id('jyo' . sprintf('%02d', $stadiumId)))->click();

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('selRaceNo' . sprintf('%02d', $raceNumber))));
        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('selRaceNo' . sprintf('%02d', $raceNumber))));
        $this->driver->findElement(WebDriverBy::id('selRaceNo' . sprintf('%02d', $raceNumber)))->click();

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::textToBePresentInElement(WebDriverBy::xpath('descendant-or-self::body/div[1]/main/div/div[1]/section[2]/div[2]/dl/dt/strong'), $raceNumber . 'R'));

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('betkati' . $purchaseType)));
        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('betkati' . $purchaseType)));
        $this->driver->findElement(WebDriverBy::id('betkati' . $purchaseType))->click();

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('betway1')));
        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('betway1')));
        $this->driver->findElement(WebDriverBy::id('betway1'))->click();

        $totalAmount = 0;

        foreach ($forecasts as $forecast => $amount) {
            foreach (range(1, iconv_strlen(strval($forecast))) as $i) {
                $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('regbtn_' . substr($forecast, $i - 1, 1) . '_' . $i)));
                $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('regbtn_' . substr($forecast, $i - 1, 1) . '_' . $i)));
                $this->driver->findElement(WebDriverBy::id('regbtn_' . substr($forecast, $i - 1, 1) . '_' . $i))->click();
            }

            $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('amount')));
            $this->driver->findElement(WebDriverBy::id('amount'))->clear();
            $this->driver->findElement(WebDriverBy::id('amount'))->sendKeys($amount / 100);

            $totalAmount += $amount;

            $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('regAmountBtn')));
            $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('regAmountBtn')));
            $this->driver->findElement(WebDriverBy::id('regAmountBtn'))->click();
        }

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('.btnSubmit')));
        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::cssSelector('.btnSubmit')));
        $this->driver->findElement(WebDriverBy::cssSelector('.btnSubmit'))->click();

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::name('betAmount')));
        $this->driver->findElement(WebDriverBy::name('betAmount'))->sendKeys($totalAmount);

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::name('betPassword')));
        $this->driver->findElement(WebDriverBy::name('betPassword'))->sendKeys($this->purchasePassword);

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('submitBet')));
        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('submitBet')));
        $this->driver->findElement(WebDriverBy::id('submitBet'))->click();

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('ok')));
        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::id('ok')));
        $this->driver->findElement(WebDriverBy::id('ok'))->click();

        $this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('thanksArea')));

        foreach ($handles as $handle) {
            $this->driver->switchTo()->window($handle);
            $this->driver->close();
        }
    }

    /**
     * @return \DI\Container
     */
    public function getContainer(): Container
    {
        $builder = new ContainerBuilder;
        $builder->addDefinitions(__DIR__ . '/../config/definitions.php');
        return $builder->build();
    }
}
