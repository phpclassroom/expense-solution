<?php

declare(strict_types=1);

// retrieve all files from the given directory
function getFiles(string $dirPath): array
{
    $files = [];

    foreach (scandir($dirPath) as $file) {
        // store reference to
        if (!is_dir($file)) {
            $files[] = $dirPath . $file;
        }
    }

    return $files;
}

// method to get transactions frm a file
// takes in the filename, and a method to extract / format each row
function getTransactions(string $fileName, ?callable $transactionHandler = null): array
{
    $file = fopen($fileName, 'r');

    // skip the first line in the csv which contains the headers
    fgetcsv($file);

    $transactions = [];

    while (($transaction = fgetcsv($file)) !== false) {
        if ($transactionHandler !== null) {
            $transaction = $transactionHandler($transaction);
        }

        $transactions[] = $transaction;
    }

    return $transactions;
}

function extractTransaction(array $transactionRow): array
{
    // create date, checkNumber, desc, and amount variables via array destructuring
    [ $date, $checkNumber, $description, $amount ] = $transactionRow;

    // remove $ and commas
    $amount = (float) str_replace(['$', ',', 'RM'], '', $amount);

    // return the values for this transaction row
    return [
        'date' => $date,
        'checkNumber' => $checkNumber,
        'description' => $description,
        'amount' => $amount
    ];
}

function calculateTotals(array $transactions): array
{
    // initialise an array for totals
    $totals = [ 'netTotal' => 0, 'totalIncome' => 0, 'totalExpense' => 0];

    foreach ($transactions as $transaction) {
        $totals['netTotal'] += $transaction['amount'];

        if ($transaction['amount'] >=  0) {
            $totals['totalIncome'] += $transaction['amount'];
        } else {
            $totals['totalExpense'] += $transaction['amount'];
        }
    }

    return $totals;
}