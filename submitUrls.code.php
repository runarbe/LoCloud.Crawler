<?php

$pageTitle = "Submit URLs for Crawling";
//print_r($_POST);

$m_collections = collection::getUsrCollections();

$m_action = util::getPost("action");

$m_collection_id = util::getPostInt("collection_id");

$m_crawler_base_url = util::getPostURL("crawler_base_url");

$m_url_follow_filter = util::getPost("url_follow_filter",
                "");

$m_url_index_filter = util::getPost("url_index_filter",
                "");

$m_url_exclude_filter = util::getPost("url_exclude_filter",
                "");

$m_url_remove_params = util::getPost("url_remove_params",
                "");

$m_delete_existing = util::getPostBoolean("delete_existing");

$m_id = util::getPostInt("id");

switch ($m_action) {
    case "insert":
        if ($m_collection_id && $m_crawler_base_url) {

            if ($m_delete_existing) {
                url::deleteCollectionURLs($m_collection_id);
            }

            crawlurl::insert($m_collection_id,
                    $m_crawler_base_url,
                    $m_url_follow_filter,
                    $m_url_index_filter,
                    $m_url_exclude_filter,
                    $m_url_remove_params);
        }
        break;
    case "update":
        if ($m_id != null && $m_collection_id != null && $m_crawler_base_url != null) {
            $m_crawlurl = crawlurl::select($m_id);
        }
        break;
    case "delete":
        if ($m_id) {
            $m_crawlurl = crawlurl::select($m_id);
            $m_crawlurl->delete();
        }
        break;
}

if ($m_collection_id) {
    $m_urls = crawlurl::getCollectionCrawlURLs($m_collection_id);
}