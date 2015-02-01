<?php

include("inc/require.php");

if (PHP_SAPI == "cli") {
    set_time_limit(1800);
} else {
    set_time_limit(300);
}

$m_crawlurls = collection::getCrawlURLs();

$c_collection_id = null;

if ($m_crawlurls != false) {

    foreach ($m_crawlurls as $c_crawlurl) {
        /* @var $c_crawlurl crawlurl */

        if (is_numeric($c_crawlurl->collection_id) && $c_crawlurl->collection_id != $c_collection_id) {
            $c_collection_id = $c_crawlurl->collection_id;
            $m_collection = collection::select($c_crawlurl->collection_id);
        }

        if ($m_collection != false && $c_crawlurl != false) {

            $m_crawler = new crawler();

            $m_crawler->enableResumption();

            if (is_numeric($c_crawlurl->resume_id)) {
                try {
                    $m_crawler->resume($c_crawlurl->resume_id);
                } catch (Exception $e) {
                    log::write($e);
                    $c_crawlurl->setResumeID($m_crawler->getCrawlerId());
                }
            } else {
                $c_crawlurl->setResumeID($m_crawler->getCrawlerId());
            }

            $m_crawler->setFollowMode(3); //Default = 2, all under front
            
            $m_crawler->crawl(
                    $c_crawlurl->collection_id,
                    $c_crawlurl->url,
                    crawler::CRAWL_MODE_ENQUEUE,
                    $c_crawlurl->url_follow_filter,
                    $c_crawlurl->url_index_filter,
                    $c_crawlurl->url_exclude_filter,
                    $c_crawlurl->url_remove_params);

            $c_crawlurl->setResumeID("null");
            $c_crawlurl->status = urlStatus::crawled;
            $c_crawlurl->update();
        } else {
            log::write("No collection could be loaded for the selected URLs");
            log::write($c_crawlurl);
        }
    }
}

