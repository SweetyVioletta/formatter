<?php

namespace app;

include "handlers/EcommBXHandler.php";
include "handlers/EurobankHandler.php";
include "handlers/HellenicHandler.php";
include "handlers/FIBankHandler.php";
include "handlers/PaymentExecutionHandler.php";

use app\handlers\EcommBXHandler;
use app\handlers\EurobankHandler;
use app\handlers\HellenicHandler;
use app\handlers\FIBankHandler;
use app\handlers\PaymentExecutionHandler;

class FileFactory
{
    public function createHandler(string $fileName)
    {
        if (false !== stripos($fileName, 'FIBank')) {
            return new FIBankHandler($fileName);
        }
        if (false !== stripos($fileName, 'Hellenic')) {
            return new HellenicHandler($fileName);
        }
        if (false !== stripos($fileName, 'Eurobank')) {
            return new EurobankHandler($fileName);
        }
        if (false !== stripos($fileName, 'EcommBX')) {
            return new EcommBXHandler($fileName);
        }
        if (false !== stripos($fileName, 'Payment Execution')) {
            return new PaymentExecutionHandler($fileName);
        }
        return null;
    }
}
