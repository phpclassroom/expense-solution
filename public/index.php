<?php

declare(strict_types = 1);

$root = dirname(__DIR__) . DIRECTORY_SEPARATOR;

// defining constants for the directories within the project
define('APP_PATH', $root . 'app' . DIRECTORY_SEPARATOR);
define('FILES_PATH', $root . 'transaction_files' . DIRECTORY_SEPARATOR);
define('VIEWS_PATH', $root . 'views' . DIRECTORY_SEPARATOR);

require APP_PATH . 'App.php';

// helper methods for formatting amount and dates
require APP_PATH . 'helpers.php';

// get all file names from within the transaction_files dir
$files = getFiles(FILES_PATH);

$transactions = [];

foreach ($files as $file) {
    // for each file, get each row via extractTransaction() method
    // merge each row to the transactions array
    $transactions = array_merge($transactions, getTransactions($file, 'extractTransaction'));
}

$totals = calculateTotals($transactions);

require VIEWS_PATH . 'transactions.php';
