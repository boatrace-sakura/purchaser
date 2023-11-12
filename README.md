# Boatrace Sakura Purchaser

[![Latest Stable Version](https://poser.pugx.org/boatrace-sakura/purchaser/v/stable)](https://packagist.org/packages/boatrace-sakura/purchaser)
[![Latest Unstable Version](https://poser.pugx.org/boatrace-sakura/purchaser/v/unstable)](https://packagist.org/packages/boatrace-sakura/purchaser)
[![License](https://poser.pugx.org/boatrace-sakura/purchaser/license)](https://packagist.org/packages/boatrace-sakura/purchaser)

Boatrace Sakura Purchaserはブラウザを操作して舟券を自動購入するためのライブラリです。

## Installation
```bash
composer require boatrace-sakura/purchaser
```

## Usage
```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Boatrace\Sakura\Purchaser;

// テレボートに5000円を入金して大村12Rの3連単(1-23-234)を購入する場合は以下の通りです。
// なお、加入者番号、暗証番号、認証用パスワード、投票用パスワードの部分は
// 自分のテレボート会員情報に書き換えてください。
// 場コード: 桐生 => 1, 戸田 => 2, 江戸川 => 3, ..., 唐津 => 23, 大村 => 24
// 勝式コード: 単勝 => 1, 複勝 => 2, 2連単 => 3, 2連複 => 4, 拡連複 => 5, 3連単 => 6, 3連複 => 7
Purchaser::setDepositAmount(5000)             // 入金指示金額
    ->setSubscriberNumber('xxxxxxxx')         // 加入者番号
    ->setPersonalIdentificationNumber('xxxx') // 暗証番号
    ->setAuthenticationPassword('xxxxxx')     // 認証用パスワード
    ->setPurchasePassword('xxxxxx')           // 投票用パスワード
    ->purchase(24, 12, 6, [                   // 場コード, レース番号, 勝式コード
        123 => 1500,                          // 組番 => 購入金額
        124 => 1500,                          // 組番 => 購入金額
        132 => 1000,                          // 組番 => 購入金額
        134 => 1000,                          // 組番 => 購入金額
    ]);
```

## Quick Start

### Step 1
このリポジトリをクローンします。
```bash
git clone git@github.com:boatrace-sakura/purchaser.git
```

### Step 2
必要なライブラリをインストールします。
```bash
cd purchaser && composer update
```

### Step 3
加入者番号、暗証番号、認証用パスワード、投票用パスワード、買い目をそれぞれ書き換えます。
```bash
code example.php
```

### Step 4
Google Chromeの[Selenium Grid Server](https://github.com/SeleniumHQ/docker-selenium)を起動します。
```bash
docker run -d -p 4444:4444 --shm-size="2g" --name selenium-standalone-chrome selenium/standalone-chrome:4.2.2-20220622
```

### Step 5
購入プログラムを実行します。
```bash
php example.php
```

## Testing
テレボート会員情報を環境変数に設定します。
```bash
$env:SUBSCRIBER_NUMBER = "加入者番号"
$env:PERSONAL_IDENTIFICATION_NUMBER = "暗証番号"
$env:AUTHENTICATION_PASSWORD = "認証用パスワード"
$env:PURCHASE_PASSWORD = "投票用パスワード"
```

Selenium Serverを起動します。
```bash
npm install selenium-standalone --save-dev
npx selenium-standalone install
npx selenium-standalone start
```

購入テストを実行します。
```bash
vendor/bin/phpunit
```

## License
The Boatrace Sakura Purchaser is open source software licensed under the [MIT license](LICENSE).
