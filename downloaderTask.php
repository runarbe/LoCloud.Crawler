<?php

include("inc/require.php");
//dl("php_curl.dll");

if (PHP_SAPI == "cli") {
    set_time_limit(1800);
} else {
    set_time_limit(300);
}

// Load 40 queued URLs
$m_enqueued_urls = url::getURLsByStatus(urlStatus::enqueued,
                40);

// Load 10 unreachable URLs
$m_unreachable_urls = url::getURLsByStatus(urlStatus::unreachable,
                10);

// Merge into one array and shuffle
$m_urls = array_merge($m_enqueued_urls,
        $m_unreachable_urls);
shuffle($m_urls);

// Set iteration counter to 1
$i = 1;

// Loop through the URLs
foreach ($m_urls as $c_url) {
    /* @var $c_url url */

    // If the URL is invalid, set the status of it
    if (util::is_url($c_url->url) == false) {
        $c_url->status = urlStatus::invalid_url;
        $c_url->update();
        echo "Invalid URL: " . $c_url->url . "\n";
    } else {
        $mData = util::downloadURL($c_url->url);
        if ($mData != false) {
            $c_url->html = db::escape($mData);
            $c_url->status = urlStatus::downloaded;
            $c_url->update();
            echo "Downloaded: " . $c_url->url . " : " . strlen($mData) . " characters\n";
        } else {
            $c_url->status = urlStatus::unreachable;
            $c_url->update();
            echo "Unreachable: " . $c_url->url . "\n";
        }
    }

    // Sleep for 2 seconds between each batch of five elements
    if ($i % 5 == 0) {
        sleep(2);
    }

    $i++;
}
