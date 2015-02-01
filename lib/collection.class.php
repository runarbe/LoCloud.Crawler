<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of collection
 *
 * @author runarbe
 */
class collection {

    /**
     * ID of collection
     * @var integer 
     */
    public $id = null;

    /**
     * Name of collection
     * @var string
     */
    public $name;

    /**
     * Common geo-property for all collection items
     * @var string 
     */
    public $geo;

    /**
     * Common type property for all collection items
     * @var string 
     */
    public $ctype;

    /**
     * Common license property for all collection items
     * @var string; 
     */
    public $license;

    public function __construct($pName,
            $pCType,
            $pLicense,
            $pGeo,
            $pID = null) {
        $this->id = $pID;
        $this->name = $pName;
        $this->ctype = $pCType;
        $this->license = $pLicense;
        $this->geo = $pGeo;
    }

    public function deleteUrls() {
        return url::deleteCollectionURLs($this->id);
    }

    public function delete() {

        $this->deleteUrls();
        acl::deleteByTypeAndID(objType::Collection,
                $this->id);

        $mRes = db::execute(sprintf("DELETE FROM collection WHERE id = %s",
                                $this->id));
        if ($mRes != false) {
            return true;
        }

        return false;
    }

    public function clear() {
        $this->id = null;
        $this->name = "";
        $this->geo = "";
        $this->license = "";
        $this->ctype = "";
    }

    public function update() {
        if (is_numeric($this->id)) {
            return db::execute(sprintf("UPDATE collection SET name='%s', ctype='%s', geo='%s', license='%s' WHERE id=%s",
                                    $this->name,
                                    $this->ctype,
                                    $this->geo,
                                    $this->license,
                                    $this->id));
        } else {
            return false;
        }
    }

    /**
     * Create a new collection object
     * @param string $pName
     * @param edmType $pCtype
     * @param string $pGeo
     * @param string $pLicense
     * @return boolean
     */
    public static function insert($pName,
            $pCtype,
            $pGeo = "",
            $pLicense = "") {
        if (is_numeric(usr::getAuthUsr()->id)) {
            $mRes = db::query(sprintf("INSERT INTO collection (name, ctype, geo, license) VALUES ('%s', '%s', '%s', '%s');",
                                    $pName,
                                    $pCtype,
                                    $pGeo,
                                    $pLicense));
            if (!$mRes == false) {
                $mCollID = db::getLastInsertID();
                acl::insert(
                        objType::User,
                        usr::getAuthUsr()->id,
                        objType::Collection,
                        $mCollID,
                        aclRight::readWrite
                );
                return collection::select($mCollID);
            }
        }
        return false;
    }

    /**
     * Select a collectoin by its ID
     * @param integer $pID
     * @return collection|boolean
     */
    public static function select($pID) {
        if (filter_var($pID,
                        FILTER_VALIDATE_INT)) {
            $mRes = db::query(sprintf("SELECT * FROM collection WHERE id = %s;",
                                    $pID));
            if ($mRes != false && mysqli_num_rows($mRes) == 1) {
                $mRow = mysqli_fetch_assoc($mRes);
                return new collection($mRow["name"],
                        $mRow["ctype"],
                        $mRow["license"],
                        $mRow["geo"],
                        $mRow["id"]);
            }
        }
        return false;
    }

    /**
     * Imports a sitemap into a collection
     * @param type $pFileName
     * @return integer Number of inserted URLs
     */
    public function importGoogleSitemap($pFileName) {
        $mURLCount = 0;
        $mSiteMap = simplexml_load_file($pFileName);
        foreach ($mSiteMap->url as $mUrl) {
            url::insert($mUrl->loc,
                    $this->id);
            $mURLCount++;
        }
        return $mURLCount;
    }

    /**
     * Returns a flag stating wether the collection object is complete
     * @return boolean
     */
    public function isComplete() {
        if (is_numeric($this->id) && !empty($this->name)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return the up to $pLimit number of uncrawled URLs
     * @param integer $pLimit
     * @param urlStatus $pStatus
     * @return crawlurl|boolean Collection if exists, otherwise false
     */
    public static function getCrawlURLs($pStatus = urlStatus::enqueued,
            $pLimit = -1) {
        if ($pLimit !== -1) {
            $pLimit = "LIMIT $pLimit";
        }
        $mSql = sprintf("SELECT * FROM crawlurl WHERE status='%s' ORDER BY collection_id, id %s", $pStatus, $pLimit);
        $mRes = db::query($mSql);
        $mCrawlURLs = array();
        if (db::hasRows($mRes)) {
            while ($mRow = db::getNextRow($mRes)) {
                $mCrawlURLs[] = util::createClassFromArray("crawlurl",
                                $mRow);
            }
            return $mCrawlURLs;
        } else {
            return false;
        }
    }

    /**
     * Output the collections belonging to the specified user as HTML <option>
     * elements
     * 
     * @param integer $pSelectedCollectionID
     * @param boolean $pIncludeBlankOption
     * @return string HTML
     */
    public static function getUsrCollectionsHTMLOptions($pSelectedCollectionID = "",
            $pIncludeBlankOption = false) {
        $mOptions = "";
        if ($pIncludeBlankOption) {
            $mOptions .= "<option value=\"\">-</option>";
        }

        foreach (collection::getUsrCollections() as $mCollection) {
            /* @var $mCollection collection */

            if ($mCollection->id == $pSelectedCollectionID) {
                $mSelected = "selected=\"true\"";
            } else {
                $mSelected = "";
            }
            $mOptions .= "<option value=" . $mCollection->id . " $mSelected>" . $mCollection->name . "</option>";
        }
        return $mOptions;
    }

    /**
     * Get all collections belonging to the currently authenticated user
     * @return collection[]|boolean
     */
    public static function getUsrCollections() {
        if (usr::isAuth()) {
            $mRes = db::query(sprintf("SELECT c.id, c.name, c.ctype, c.license, c.geo FROM collection c, acl a WHERE a.dst_type ='collection' AND a.src_type = 'usr' AND a.src_id = %s AND c.id = a.dst_id ORDER BY c.name ASC",
                                    usr::getAuthUsr()->id));
            $mCollections = array();
            if ($mRes != false && mysqli_num_rows($mRes) > 0) {
                while ($mRow = mysqli_fetch_array($mRes)) {
                    $mCollection = new collection($mRow["name"],
                            $mRow["ctype"],
                            $mRow["license"],
                            $mRow["geo"],
                            $mRow["id"]);
                    $mCollections[] = $mCollection;
                }
            }
            return $mCollections;
        }
        return false;
    }

    public static function importUrlFile($pCollectionID,
            $pFileName) {
        
    }

}
