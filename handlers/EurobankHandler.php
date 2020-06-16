<?php

namespace app\handlers;


use app\helpers\CsvHelper;

class EurobankHandler extends Handler
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
        $header = $row = $helper->getRow($handle);
        if (false === $helper->writeHeaderToConvertedFile($this->getFormattedHeader($header), $newFileName)) {
            return false;
        }
        while (false !== ($row = $helper->getRow($handle))) {
            array_walk($row, function (&$element) {
                $element = str_replace("-\n", '-', $element);
                $element = str_replace("\n-", '-', $element);
                $element = str_replace("\n", ' ', $element);
            });
            if (false === $helper->writeRowToConvertedFile($this->getFormattedRow($row), $newFileName)) {
                return false;
            }
        }
        return true;
    }

    private function getFormattedHeader(array $header)
    {
        return "Posting Date,Value Date,UTN,Description,Payee,Debit_Credit,Balance\n";
    }

    private function getFormattedRow(array $row)
    {
        $newRow = $row;
        $newRow[3] = $this->getFormattedDesc($row[3]);
        $newRow[4] = $this->getFormattedPayee($row[3]);
        $newRow[5] = $this->getDebitCredit($row[4], $row[5]);
        $newRow[6] = $row[6];
        return implode(',', $newRow) . "\n";
    }

    private function getDebitCredit($debit, $credit)
    {
        if ('' !== $debit && ($debit > 0)) {
            return str_replace('--', '-', '-' . str_replace(',', '', $debit));
        }
        return str_replace(',', '', $credit);
    }

    private function getFormattedPayee(string $desc)
    {
        if (false !== strpos($desc, 'SEPA SWIFT FEE')) {
            return '';
        }
        if (false !== strpos($desc, 'THE LUCK FACTORY EUROPE LIMITED')) {
            return 'THE LUCK FACTORY EUROPE LIMITED';
        }
        if (false !== strpos($desc, 'JetBrains Prague')) {
            return 'JetBrains Prague';
        }

        if (false !== strpos($desc, 'LUCIDCHART.COM/CHARGE WWW.GOLUCID.C')) {
            return 'LUCIDCHART.COM/CHARGE WWW.GOLUCID.C';
        }
        if (false !== strpos($desc, 'Social Insurance Services')) {
            return 'Social Insurance Services';
        }
        $descArr = explode(' - ', $desc);
        if (isset($descArr[1]) && 'Web Ref' === $descArr[1]) {
            $description = '';
        } else {
            $description = $descArr[1] ?? '';
        }
        $description = str_replace('Handling Fee  ', '', str_replace('Purchase  ', '', str_replace('F/O: ', '', $description)));
        $description = trim($description, '-');
        $description = preg_replace('/(.*)( for card .*)/', '$1', $description);

        return $description;
    }

    private function getFormattedDesc(string $desc)
    {
        return trim(explode(' - ', $desc)[0]);
    }
}