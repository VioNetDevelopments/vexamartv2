<?php

namespace App\Helpers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeHelper
{
    public static function generate($data, $size = 150)
    {
        return QrCode::size($size)->generate($data);
    }
    
    public static function generateInvoice($invoiceCode)
    {
        $url = route('admin.transactions.show', ['transaction' => $invoiceCode]);
        return self::generate($url);
    }
}