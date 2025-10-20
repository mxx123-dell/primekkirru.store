<?php
// Ping giữ cho host không ngủ - by PrimeKkirru.Store
header("Content-Type: text/plain");

echo "PING OK - " . date("H:i:s d/m/Y");

// Nếu muốn kiểm tra log ping hoạt động thì bật đoạn này:
// $logFile = __DIR__ . "/ping_log.txt";
// file_put_contents($logFile, "Ping at " . date("H:i:s d/m/Y") . "\n", FILE_APPEND);
