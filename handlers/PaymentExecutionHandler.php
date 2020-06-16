<?php

namespace app\handlers;

use app\helpers\CsvHelper;

class PaymentExecutionHandler extends Handler
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

        while (false !== ($row = $helper->getRow($handle))) {
            $i++;
            if ($i < 4) {
                continue;
            }
            if (4 === $i) {
                $header = $row;
                if (false === $helper->writeHeaderToConvertedFile($this->refactorHeader($header), $newFileName)) {
                    return false;
                }
                continue;
            }
            $newRow = $this->formatRow($row);
            if (false === $helper->writeRowToConvertedFile(implode(',', $newRow) . "\n", $newFileName)) {
                return false;
            }
        }
        return true;
    }

    private function refactorHeader(array $header)
    {
        return "Date,Transaction ID,Description,Payee,Description1,Debit/Credit,Current balance\n";
    }

    private function formatRow(array $row)
    {
        $newDataRow = $row;
        $dateTime = $row[0];
        $date = explode(' - ', $dateTime)[0];
        $newDataRow[0] = $date;
        // format description
        $desc = $row[2];
        $newDataRow[2] = $this->formatDescription($desc);
        // format description
        $newDataRow[3] = $this->formatPayee($desc);
        $newDataRow[4] = trim($this->formatDescription1($desc));
        $newDataRow[5] = $this->formatDebitCredit($row[3]);
        $newDataRow[6] = $this->formatDebitCredit($row[4]);
        return $newDataRow;
    }

    private function formatDescription(string $desc)
    {
        if (false !== strpos($desc, 'Transfer FEE')) {
            return 'Transfer FEE';
        }
        if (false !== strpos($desc, 'Negative Interest Fee')) {
            return 'Negative Interest Fee';
        }
        if (false !== strpos($desc, 'SEPA deposit')) {
            $startPos = strpos($desc, 'Msg:') + 4;
            $endPos = strpos($desc, 'EndToEndId:') - $startPos - 1;
            return substr($desc, $startPos, $endPos);
        }
        $str = explode('/ ', $desc);
        return $str[1] ?? '';
    }

    private function formatPayee(string $payee)
    {
        if (false !== strpos($payee, 'Transfer FEE')) {
            return '';
        }
        if (false !== strpos($payee, 'Negative Interest Fee')) {
            return '';
        }
        if (false !== strpos($payee, 'The Luck Factory Europe Limited')) {
            return 'The Luck Factory Europe Limited';
        }
        if (false !== strpos($payee, 'Own transfer between accounts')) {
            return 'Own transfer between accounts';
        }
        if (false !== strpos($payee, 'CodeOK IT Development')) {
            return 'CodeOK IT Development';
        }
        return trim(preg_replace('/((.*[0-9])|(.*from ))(([a-z A-Z])*) (Invoice|BIC)(.*)/', '$4', $payee));
    }

    private function formatDescription1(string $desc)
    {
        if (false !== strpos($desc, 'Own transfer between accounts')) {
            return 'Own transfer between accounts';
        }
        if (false !== strpos($desc, 'OWT fee')) {
            return 'OWT fee';
        }
        if (false !== strpos($desc, 'Negative Interest Fee')) {
            return str_replace('Negative Interest Fee', '', $desc);
        }
        if (preg_match('/(.* \- )(TR [0-9]* \-.*)/', $desc) > 0) {
            return preg_replace('/(.* \- )(TR [0-9]* \-.*)/', '$2', $desc);
        }
        if (preg_match('/(.* )(Id:[O0-9]{0,})/', $desc) > 0) {
            return preg_replace('/(.* )(Id:[O0-9]{0,})/', '$2', $desc);
        }
        return '';
    }

    private function formatDebitCredit(string $amount)
    {
        return number_format((float)str_replace(',', '', $amount), 2, '.', '');
    }
}
