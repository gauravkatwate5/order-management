<?php

function log_failed_order($order_id, $reason) {
    $log_file = __DIR__ . '/log.txt';
    $date = date('Y-m-d H:i:s');
    $entry = "[$date] Order ID: $order_id | Reason: $reason\n";
    file_put_contents($log_file, $entry, FILE_APPEND);
}

