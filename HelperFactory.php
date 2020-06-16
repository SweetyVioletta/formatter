<?php

namespace app;
include 'helpers/Helper.php';
include "helpers/CsvHelper.php";
include "helpers/XlsHelper.php";
include "helpers/TxtHelper.php";


use app\helpers\CsvHelper;
use app\helpers\TxtHelper;
use app\helpers\XlsHelper;

class HelperFactory
{
    public function getHelper(string $extension)
    {
        if ('txt' === $extension) {
            return new TxtHelper();
        }
        if ('csv' === $extension) {
            return new CsvHelper();
        }
        if ('xls' === $extension) {
            return new XlsHelper();
        }
    }
}
