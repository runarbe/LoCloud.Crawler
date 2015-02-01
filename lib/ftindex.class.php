<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ftindex
 *
 * @author runarbe
 */
class ftindex {

    public $id;
    public $url;
    public $dc_coverage;
    public $dc_description;
    public $dc_subject;
    public $dc_title;
    public $dc_type;
    public $dcterms_spatial;
    public $edm_type;
    public $edm_aggregatedCHO;
    public $edm_dataProvider;
    public $edm_isShownAt;
    public $edm_isShownBy;
    public $edm_provider;
    public $edm_rights;
    public $collection_id;
    public $idxfld;
    public $ranking;

    public static function deleteCollectionFTIndex($pCollectionID) {
        if (is_numeric($pCollectionID)) {
            $mSql = sprintf("DELETE FROM ftindex WHERE collection_id = %s",
                    $pCollectionID);
            db::execute($mSql);
            $mSql = sprintf("UPDATE url SET status='%s' WHERE collection_id=%s",
                    urlStatus::downloaded,
                    $pCollectionID);
            echo $mSql;
            db::execute($mSql);

            return true;
        }
        return false;
    }

    /**
     * 
     * @param string $pURL
     * @param integer $pCollectionID
     * @param string $pPlainText
     * @param meta $pMetaRecord
     */
    public static function insert($pURL,
            $pCollectionID,
            $pPlainText,
            $pMetaRecord
    ) {

        $mSql = sprintf("INSERT INTO ftindex (url, dc_coverage, dc_description, dc_title, dc_subject, dc_type, dcterms_spatial, edm_type, edm_isShownAt, edm_isShownBy, edm_provider, edm_rights, collection_id, idxfld) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',%s,'%s');",
                $pURL,
                $pMetaRecord->dc_coverage,
                $pMetaRecord->dc_description,
                $pMetaRecord->dc_title,
                $pMetaRecord->dc_subject,
                $pMetaRecord->dc_type,
                $pMetaRecord->dcterms_spatial,
                $pMetaRecord->edm_type,
                $pMetaRecord->edm_isShownAt,
                $pMetaRecord->edm_isShownBy,
                $pMetaRecord->edm_provider,
                $pMetaRecord->edm_rights,
                $pCollectionID,
                $pPlainText
        );

        $mRes = db::execute($mSql);
        if (db::affectedRows() == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Search the full-text index
     * @param string $pQueryTerm
     * @param integer $pOffset = 0
     * @param integer $pLimit = 10
     * @return searchResult|boolean A searchResult object or false on error
     */
    public static function search($pQueryTerm,
            $pOffset = 0,
            $pLimit = 10) {

        $mSql = sprintf("SELECT DISTINCT url, dc_title, dc_description, MATCH (idxfld) AGAINST ('%s') as ranking FROM ftindex WHERE MATCH (idxfld) AGAINST ('%s') ORDER BY ranking DESC, dc_title ASC LIMIT %s OFFSET %s",
                $pQueryTerm,
                $pQueryTerm,
                $pLimit,
                $pOffset);
        $mRes = db::query($mSql);
        if (db::isSuccess($mSql)) {
            $mSearchResult = new searchResult();
            if (db::hasRows($mRes)) {
                while ($mRow = db::getNextRow($mRes)) {
                    $mSearchResult->addItem(util::createClassFromArray("ftindex",
                                    $mRow));
                }
            }
            return $mSearchResult;
        } else {
            return false;
        }
    }

}
