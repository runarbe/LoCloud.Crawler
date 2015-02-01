<?php

/**
 * Description of url
 *
 * @author runarbe
 */
class url {

    public $id;
    public $url;
    public $status;
    public $tstamp;
    public $collection_id;
    public $html;

    public function __construct() {
        
    }

    public function update() {
        $mSql = sprintf("UPDATE url SET url='%s', status='%s', html='%s', collection_id=%s WHERE id = %s",
                $this->url,
                $this->status,
                db::escape($this->html),
                $this->collection_id,
                $this->id
        );
        $mRes = db::execute($mSql);

        if (db::affectedRows() == 1) {
            return true;
        } else {
            log::write("For some reason, the update did not happen: ". $mSql);
            return false;
        }
    }

    /**
     * Create a new URL in a collection
     * @param type $pURL
     * @param urlStatus $pStatus
     * @param integer $pCollectionID
     * @return boolean
     */
    public static function insert($pURL,
            $pCollectionID,
            $pHTML = "",
            $pStatus = urlStatus::enqueued) {
        if (PHP_SAPI == "cli" || is_numeric(usr::getAuthUsr()->id)) {
            if (util::is_url($pURL) != false) {
                $mRes = db::execute(sprintf("INSERT INTO url (url, status, html, collection_id) VALUES ('%s', '%s', '%s', '%s')",
                                        $pURL,
                                        $pStatus,
                                        $pHTML,
                                        $pCollectionID));
                if ($mRes != false) {
                    return url::select(db::getLastInsertID());
                }
            }
        }
        return false;
    }

    /**
     * Insert a new URL in a collection
     * @param integer $pID
     * @return boolean|\url url object on success, false on error
     */
    public static function select($pID) {
        $mRes = db::query(sprintf("SELECT * FROM url WHERE id = %s",
                                $pID));
        if (db::hasRows($mRes)) {
            return util::createClassFromArray("url",
                            db::getNextRow($mRes));
        }
        return false;
    }

    /**
     * Delete all URLs for a specific collection
     * @param type $pCollectionID
     * @return boolean
     */
    public static function deleteCollectionURLs($pCollectionID) {
        if (is_numeric($pCollectionID)) {
            return db::execute(sprintf("DELETE FROM url WHERE collection_id=%s",
                                    $pCollectionID));
        }
        return false;
    }

    /**
     * Return URLs by status
     * @param urlStatus $pStatus
     * @param integer $pLimit = -1
     * @return type
     */
    public static function getURLsByStatus($pStatus,
            $pLimit = -1) {

        if ($pLimit != -1) {
            $pLimit = " LIMIT $pLimit";
        } else {
            $pLimit = "";
        }

        $mSql = sprintf("SELECT * FROM url WHERE status = '%s' ORDER BY collection_id, id %s",
                $pStatus,
                $pLimit);

        $mRes = db::query($mSql);
        $mURLs = array();
        if (db::hasRows($mRes)) {
            while ($mRow = db::getNextRow($mRes)) {
                $mURLs[] = util::createClassFromArray("url",
                                $mRow);
            }
        }
        return $mURLs;
    }

    /**
     * Return all urls for a collection
     * @param integer $pCollectionID
     * @return url[]|boolean
     */
    public static function getURLsForCollection($pCollectionID) {
        if (is_numeric($pCollectionID)) {
            $mUrls = array();
            $mRes = db::query(sprintf("SELECT * FROM url WHERE collection_id=%s",
                                    $pCollectionID));
            if ($mRes != false && mysqli_num_rows($mRes) > 0) {
                while ($mRow = mysqli_fetch_array($mRes)) {
                    $mUrls[] = util::createClassFromArray("url",
                                    $mRow);
                }
            } else {
                log::write("There were no urls for the collection.");
            }
            return $mUrls;
        } else {
            return false;
        }
    }

}
