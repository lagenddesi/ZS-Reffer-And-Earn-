<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

processDailyROI();

// Log execution
file_put_contents(__DIR__ . '/cron.log', date('Y-m-d H:i:s') . " - Daily ROI processed\n", FILE_APPEND);
