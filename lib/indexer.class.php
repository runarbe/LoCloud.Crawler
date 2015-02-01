<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of indexer
 *
 * @author runarbe
 */
class indexer {

    public function __construct() {
        
    }

    /**
     * Index an array of url_objects
     * @param url[] $pURLs An array of url objects
     */
    public static function indexURLList($pURLs) {
        foreach ($pURLs as $cURL) {
            /* @var $cURL url */

            $mMeta = new meta();

            if (!empty($cURL->html)) {

                $mHTMLDoc = str_get_html($cURL->html);
                
                foreach ($mHTMLDoc->find("head title") as $m_title) {
                    echo $m_title->plaintext . "\n";
                    $mMeta->dc_title = $m_title->plaintext;
                }

                foreach ($mHTMLDoc->find("div[class='contentbar2'] div[class='textbound'] h2") as $m_desc) {
                    echo $m_desc->plaintext . "\n";
                    $mMeta->dc_title .= $m_desc->plaintext;
                }

                foreach ($mHTMLDoc->find("div[class='contentbar2'] div[class='textbound']") as $m_desc) {
                    echo $m_desc->plaintext . "\n";
                    $mMeta->dc_description = substr($m_desc->plaintext,
                            0,
                            250);
                }

                foreach ($mHTMLDoc->find("div[class='contentbar2'] img") as $m_img) {
                    echo $m_img->alt . "\n";
                    $mMeta->edm_isShownAt = $m_img->src;
                }

                $mPlainText = db::escape($mHTMLDoc->plaintext);
                $mPlainText = ereg_replace("[ \t\n\r]+",
                        " ",
                        $mPlainText);

                ftindex::insert($cURL->url,
                        $cURL->collection_id,
                        $mPlainText,
                        $mMeta);

                $cURL->status = urlStatus::indexed;
                $cURL->update();
            }
        }
    }

    /**
     * Index a single URL
     * @param string $pUrl
     */
    public static function index($pUrl) {

        $mHtml = file_get_html($pUrl);
        $mEdm = new edm();
        $mProvidedCHO = new edmProvidedCHO();

        foreach ($mHtml->find("title") as $mTitle) {
            $mProvidedCHO->dc_title .= $mTitle->plaintext;
        }

        $mEdm->addProvidedCHO($mProvidedCHO);

        $mO2X = new ObjectAndXML();

        var_dump($mEdm);

        echo $mXml = $mO2X->objToXML($mEdm);
    }

}
