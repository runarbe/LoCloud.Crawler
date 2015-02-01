<?php

include("inc/require.php");

if (PHP_SAPI == "cli") {
    set_time_limit(1800);
} else {
    set_time_limit(30);
}

$m_urls = url::getURLsByStatus(urlStatus::downloaded,
                100);

indexer::indexURLList($m_urls);