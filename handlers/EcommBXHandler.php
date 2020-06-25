<?php

namespace app\handlers;

use app\helpers\CsvHelper;

include "Handler.php";
class EcommBXHandler extends Handler
{
    /**
     * @param CsvHelper $helper
     *
     * @return bool
     */
    public function parseAndWrite($helper): bool
    {
        $newFileName = $helper->getConvertedFileName($this->fileName);
        $handle = $helper->initReading($this->fileName);
        $i = 0;
        $rowsForDelete = [
            ',,,,,THE LUCK FACTORY EUROPE LTD,,,',
            'Period,,01/05/2020–03/06/2020,,,,,,',
            'Account number,,CY30905000010010182040010001,,,,,,',
            'Date Issued,,03/06/2020,,,,,,',
            'Currency,,EUR,,,,,,',
            'Execution Date,Value Date,,ID,Details,,Amount,,Balance',
            ',,,,,,,Page 1 of 2,',
            ',,,,,THE LUCK FACTORY EUROPE LTD,,,',
            'Period,,01/05/2020–03/06/2020,,,,,,',
            'Account number,,CY30905000010010182040010001,,,,,,',
            'Date Issued,,03/06/2020,,,,,,',
            'Currency,,EUR,,,,,,',

            'Maximum can be 10 pages',
            ',,,,,,,Page 2 of 2,',

            'Execution,Value Date,,ID,Details,,Amount,,Balance',
            'Starting Balance,,0,00,,,,,,',
            'Debit Turnover,,10.001,00,,,,,,',
            'Credit Turnover,,10.001,00,,,,,,',
            'Debit Turnover,,29.991,00,,,,,,',
            'Credit Turnover,,36.730,00,,,,,,',
        ];
        $helper->writeHeaderToConvertedFile($this->refactorHeader(), $newFileName);
        while (false !== ($row = $helper->getRow($handle))) {
            array_walk($row, function (&$element) {
                $element = str_replace("-\n", '-', $element);
                $element = str_replace("\n-", '-', $element);
                $element = str_replace("\n", ' ', $element);
            });

            if (in_array(implode(',', $row), $rowsForDelete, true)) {
                continue;
            }
            if (in_array($row[0], [
                'Period',
                'Date',
                'Debit',
                'Account number',
                'Date Issued',
                'Currency',
                'Starting Balance',
                'Debit Turnover',
                'Credit Turnover',
            ])
            ) {
                continue;
            }
            $newRow = $this->formatRow($row);
            if (false === $helper->writeRowToConvertedFile(implode(',', $newRow) . "\n", $newFileName)) {
                return false;
            }
        }
        return true;
    }

    private function refactorHeader()
    {
        return "Execution Date,Value Date,,ID,Details,,Amount,,Balance\n";
    }

    private function formatRow($row)
    {
        $row[0] = explode(' ', $row[0])[0];
        $row[6] = str_replace('"', '', str_replace(',','.', str_replace('.', '', $row[6])));
        $row[8] = str_replace(',', '.', str_replace('.', '', $row[8]));
        return $row;
    }
}