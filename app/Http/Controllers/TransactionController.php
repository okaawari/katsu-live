<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function test()
    {
        $transactions = [
            [
                "tranDate" => "2025-05-08T00:00:00Z",
                "time" => "10:29",
                "amount" => 3000,
                "description" => "100007",
                "relatedAccount" => "5073092582",
                "currency" => "MNT",
                "code" => "4045",
                "refId" => "12345782",
                "balance" => 413711.9
            ],
            [
                "tranDate" => "2025-05-08T00:00:00Z",
                "time" => "10:04",
                "amount" => 6000,
                "description" => "ХААНААС: 040000 УЧРАЛ БАТГЭРЭЛ EB -100008",
                "relatedAccount" => "5303006004",
                "currency" => "MNT",
                "code" => "4045",
                "refId" => "1234568",
                "balance" => 410711.9
            ]
        ];

        return response()->json($transactions);
    }
}
