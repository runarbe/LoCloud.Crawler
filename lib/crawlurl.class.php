<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of crawl
 *
 * @author runarbe
 */
class crawlurl {

    /**
     * The ID of the crawl URL
     * @var integer
     */
    public $id;

    /**
     * The URL to use as a base for crawling
     * @var string 
     */
    public $url;

    /**
     * A filter for which URLs to follow
     * @var string 
     */
    public $url_follow_filter;

    /**
     * A filter for which URLs to index
     * @var string 
     */
    public $url_index_filter;

    /**
     * A filter for URLs to exclude from the search results
     * @var string 
     */
    public $url_exclude_filter;

    /**
     * A comma separated list of HttpGet parameters to strip from the URL prior
     * to determining its uniqueness
     * @var string 
     */
    public $url_remove_params;

    /**
     * The status of the url, whether reachable, indexed etc.
     * @var urlStatus 
     */
    public $status;

    /**
     * The last update of the url
     * @var type 
     */
    public $tstamp;

    /**
     * The ID of the collection the crawlurl belongs to
     * @var integer 
     */
    public $collection_id;

    /**
     * The resumption ID of the crawlurl
     * @var integer 
     */
    public $resume_id;

    /**
     * Default constructor
     */
    public function __construct() {
        
    }

    /**
     * Insert a new URL into the crawler queue
     * @param type $pCollectionID
     * @param type $pUrl
     * @param type $pUrlFollowFilter
     * @param type $pUrlIndexFilter
     * @param type $pUrlExcludeFilter
     * @param type $pUrlRemoveParams
     * @param type $pStatus
     * @return boolean
     */
    public static function insert(
    $pCollectionID,
            $pUrl,
            $pUrlFollowFilter = null,
            $pUrlIndexFilter = null,
            $pUrlExcludeFilter = null,
            $pUrlRemoveParams = null,
            $pStatus = urlStatus::enqueued) {
        $mSql = sprintf("INSERT INTO crawlurl (url, url_follow_filter, url_index_filter, url_exclude_filter, url_remove_params, status, collection_id) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', %s)",
                $pUrl,
                $pUrlFollowFilter,
                $pUrlIndexFilter,
                $pUrlExcludeFilter,
                $pUrlRemoveParams,
                $pStatus,
                $pCollectionID
        );
        return db::execute($mSql);
    }

    /**
     * Select a URL from the crawler queue
     * @param type $pID
     * @return crawlurl|boolean
     */
    public static function select($pID) {
        $mSql = sprintf("SELECT * FROM crawlurl WHERE id = %s",
                $pID);
        $mRes = db::query($mSql);
        if (db::hasRows($mRes)) {
            $mRow = mysqli_fetch_array($mRes);
            return util::createClassFromArray("crawlurl",
                            $mRow);
        }
        return false;
    }

    /**
     * Delete an URL from the crawler queue
     * @return boolean
     */
    public function delete() {
        if (usr::isAuth()) {
            $mSql = sprintf("DELETE FROM crawlurl WHERE id = %s",
                    $this->id);
            $mRes = db::execute($mSql);
        } else {
            return false;
        }
    }

    /**
     * Update the status of a URL in the crawler queue
     * @param integer $pID
     * @param urlStatus $pStatus
     */
    public function setStatus($pID,
            $pStatus) {
        $pCrawlURL = crawlurl::select($pID);
        $pCrawlURL->status = $pStatus;
    }

    public function update() {
        if (usr::isAuth()) {
            $mSql = sprintf("UPDATE crawlurl SET url='%s', url_follow_filter='%s', url_index_filter='%s', url_exclude_filter='%s', url_remove_params='%s', status='%s', collection_id=%s, resume_id=%s WHERE id = %s",
                    $this->url,
                    $this->url_follow_filter,
                    $this->url_index_filter,
                    $this->url_exclude_filter,
                    $this->url_remove_params,
                    $this->status,
                    $this->collection_id,
                    $this->resume_id,
                    $this->id);
            $mRes = db::execute($mSql);
            if (db::affectedRows() == 1) {
                return true;
            }
        }
        return false;
    }

    /**
     * Set the resume ID of the crawlurl
     * @param integer|null $pCrawlURLID
     * @param integer $pResumeID
     * @return boolean
     */
    public function setResumeID($pResumeID) {
        $this->resume_id = $pResumeID;
        $this->update();
        if (db::affectedRows() == 1) {
            return true;
        } else {
            log::write("Could not update resume_id of crawlurl with id: " . $this->id);
        }
        return false;
    }

    /**
     * Retrieve the crawl URLs belonging to the specified collection
     * @param integer $pCollectionID
     * @return boolean
     */
    public static function getCollectionCrawlURLs($pCollectionID) {
        if (is_numeric($pCollectionID)) {

            $mUrls = array();
            /* @var $mUrls collection[] */

            $mRes = db::query(sprintf("SELECT * FROM crawlurl WHERE collection_id=%s",
                                    $pCollectionID));
            if ($mRes != false && mysqli_num_rows($mRes) > 0) {
                while ($mRow = mysqli_fetch_array($mRes)) {
                    $mUrls[] = util::createClassFromArray("crawlurl",
                                    $mRow);
                }
            } else {
                log::write("There were no URLs for the collection.");
            }
            return $mUrls;
        } else {
            return false;
        }
    }

}