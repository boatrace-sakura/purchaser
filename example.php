<?php

require __DIR__ . '/vendor/autoload.php';

use Boatrace\Sakura\Purchaser;

// テレボートに1000円を入金して大村12Rの2連単(1-2345)を購入する場合は以下の通りです。
// なお、加入者番号、暗証番号、認証用パスワード、投票用パスワードの部分は
// 自分のテレボート会員情報に書き換えてください。
// 場コード: 桐生 => 1, 戸田 => 2, 江戸川 => 3, ..., 唐津 => 23, 大村 => 24
// 勝式コード: 単勝 => 1, 複勝 => 2, 2連単 => 3, 2連複 => 4, 拡連複 => 5, 3連単 => 6, 3連複 => 7
Purchaser::setDepositAmount(1000)             // 入金指示金額
    ->setSubscriberNumber('xxxxxxxx')         // 加入者番号
    ->setPersonalIdentificationNumber('xxxx') // 暗証番号
    ->setAuthenticationPassword('xxxxxx')     // 認証用パスワード
    ->setPurchasePassword('xxxxxx')           // 投票用パスワード
    ->purchase(24, 12, 3, [                   // 場コード, レース番号, 勝式コード
        12 => 300,                            // 組番 => 購入金額
        13 => 300,                            // 組番 => 購入金額
        14 => 200,                            // 組番 => 購入金額
        15 => 200,                            // 組番 => 購入金額
    ]);
