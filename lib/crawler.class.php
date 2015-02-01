<?php

// Extend the class and override the handleDocumentInfo()-method 
class crawler extends PHPCrawler {

    /**
     * Crawl to sitemap
     */
    const CRAWL_MODE_SITEMAP = 1;

    /**
     * Crawl to index
     */
    const CRAWL_MODE_INDEX = 2;

    /**
     * Crawl to queue
     */
    const CRAWL_MODE_ENQUEUE = 3;

    /**
     * An array of unique URL stems that have been indexed
     * @var string[]
     */
    private $_URLCache = array();

    /**
     * Whether to crawl the resources and index them - or to create a Google Sitemap
     * @var integer 
     */
    private $_crawlMode = crawler::CRAWL_MODE_ENQUEUE;

    /**
     * An expression that must be present in the URL for it to be added to the sitemap
     * @var string 
     */
    private $_URLIndexFilter = null;

    /**
     * An expression that must not present in the URL for it to be added to the sitemap
     * @var string 
     */
    private $_URLExcludeFilter = null;

    /**
     * An array of paramter names to be removed from URL before determining its uniqueness
     * @var string[]
     */
    private $_URLRemoveParams = array();

    /**
     * The ID of the collection that the URLs shall be added to
     * @var integer 
     */
    private $_collectionID = null;

    /**
     * The number of URLs added to queue
     * @var integer 
     */
    private $_indexedURLCount = 0;

    /**
     * The total number of downloaded documents
     * @var integer
     */
    private $_totalURLCount = 0;

    /**
     * Check if URL has been indexed
     * @param string $pURL
     * @return boolean True if in cache, false if not...
     */
    public function is_indexed($pURL) {
        return in_array($pURL,
                $this->_URLCache);
    }

    /**
     * Process each document
     * @param DocInfo $pDocInfo
     */
    function handleDocumentInfo($pDocInfo) {

        $this->_totalURLCount++;

        if ($this->_crawlMode == crawler::CRAWL_MODE_INDEX) {
            // Not implemented
        } else if ($this->_crawlMode == crawler::CRAWL_MODE_SITEMAP) {

            // Output a Google Sitemap XML file
            if ($this->_URLIndexFilter == null || strpos($pDocInfo->url,
                            $this->_URLIndexFilter) !== false) {
                $this->_indexedURLCount++;
                if ($pDocInfo->received == true) {
                    url::insert($pDocInfo->url,
                            $this->_collectionID,
                            db::escape($pDocInfo->source),
                            urlStatus::downloaded);
                } else {
                    url::insert($pDocInfo->url,
                            $this->_collectionID,
                            db::escape($pDocInfo->source),
                            urlStatus::enqueued);
                }
                echo "\t<url>\n\t\t<loc>" . htmlspecialchars($pDocInfo->url) . "</loc>\n\t</url>\n";
            }
        } else if ($this->_crawlMode == crawler::CRAWL_MODE_ENQUEUE) {

            if (($this->_URLIndexFilter == false || strpos($pDocInfo->url,
                            $this->_URLIndexFilter) !== false) && strpos($pDocInfo->url,
                            $this->_URLExcludeFilter) == false) {

                $mUniqueURL = $pDocInfo->url;
                foreach ($this->_URLRemoveParams as $c_param) {
                    $mUniqueURL = util::removeQueryStringParam($mUniqueURL,
                                    $c_param);
                }
                
                if ($this->addUniqueURL($mUniqueURL)) {
                    $this->_indexedURLCount++;
                    if ($pDocInfo->received == true) {
                        url::insert($pDocInfo->url,
                                $this->_collectionID,
                                db::escape($pDocInfo->source),
                                urlStatus::downloaded);
                    } else {
                        url::insert($pDocInfo->url,
                                $this->_collectionID,
                                db::escape($pDocInfo->source),
                                urlStatus::enqueued);
                    }
                } else {
                    log::write($pDocInfo->url." is already in the cache as " . $mUniqueURL);
                }
            } else {
                echo str_repeat("\x08",
                        100) . sprintf("Total: %04s / Indexed: %04s",
                        $this->_totalURLCount,
                        $this->_indexedURLCount);
            }
        }
        flush();
    }

    /**
     * Set the expression that must *not* be present to add a URL
     * @param type $pURLExcludeFilter
     * @return type
     */
    public function addURLExcludeFilter($pURLExcludeFilter) {
        $this->_URLExcludeFilter = $pURLExcludeFilter;
        return;
    }

    /**
     * Add URL to the $this->_urlCache array if it is unique
     * @param string $pURL
     */
    public function addUniqueURL($pURL) {
        if (!$this->is_indexed($pURL)) {
            $this->_URLCache[] = $pURL;
            return true;
        }
        return false;
    }

    /**
     * Set the expression that must be present in the URL to add it
     * @param type $pURLIndexFilter
     * @return type11
     */
    public function addURLIndexFilter($pURLIndexFilter) {
        $this->_URLIndexFilter = $pURLIndexFilter;
        return;
    }

    /**
     * Set the URL parameters to be removed prior to determining uniqueness
     * @param string $pURLRemoveParams
     * @return boolean True on success, false if string could not be
     * successfully split
     */
    public function addURLRemoveParams($pURLRemoveParams) {
        $mParams = split(",",
                $pURLRemoveParams);

        if ($mParams != false && is_array($mParams)) {
            $this->_URLRemoveParams = $mParams;
            return true;
        }
        return false;
    }

    /**
     * Set the ID of the collection that the URLs are to be added to
     * @param collection $pCollectionID
     */
    public function setCollection($pCollectionID) {
        $this->_collectionID = $pCollectionID;
    }

    /**
     * Set the mode of the crawler
     * @param int $pCrawlMode
     */
    public function setCrawlMode($pCrawlMode) {
        $this->_crawlMode = $pCrawlMode;
    }

    /**
     * Crawl the specified URL
     * @param integer $pCollectionID The identifier of the collection the URLs are to be inserted into
     * @param string $pUrl The base URL to start crawling at
     * @param crawler::CRAWL_MODE_* $pCrawlMode One of the CRAWL_MODE_... constants defined on the crawler class
     * @param string $pURLFollowRule A RegEx expression that must be in the URL for it to be followed
     * @param string $pURLIndexFilter A string expression that must be in the URL for it to be indexed
     * @param string $pURLRemoveParams A string with comma separated parameter names to be removed from
     * URL prior to determining its uniqueness
     * @return void
     */
    public function crawl($pCollectionID,
            $pUrl,
            $pCrawlMode = null,
            $pURLFollowRule = null,
            $pURLIndexFilter = null,
            $pURLExcludeFilter = null,
            $pURLRemoveParams = null) {

        if (is_numeric($pCollectionID)) {
            $this->setCollection($pCollectionID);
        } else {
            log::write("No valid collection ID specified.");
        }

        if ($pCrawlMode != null) {
            $this->setCrawlMode($pCrawlMode);
        } else {
            log::write("Using default crawl mode");
        }

        $this->setURL($pUrl);

        $this->addContentTypeReceiveRule("#text/html#");

        $this->addURLFilterRule("#\.(jpg|pdf|jpeg|gif|png|js|css|ico|mp3|wmv|fla|avi)# i");

        $this->enableCookieHandling(true);

        $this->setTrafficLimit(5000 * 1024);

        if ($pURLFollowRule != null) {
            /**
             * Include links that has the contents of $pURLFollowFilter in the URL
             */
            $this->addURLFollowRule("#(" . $pURLFollowRule . ")# i");
        } else {
            log::write("Problem setting follow rule: " . $pURLFollowRule);
        }

        if ($pURLExcludeFilter != null) {
            $this->addURLExcludeFilter($pURLExcludeFilter);
        }

        if ($pURLRemoveParams != null) {
            $this->addURLRemoveParams($pURLRemoveParams);
        }

        if ($pURLIndexFilter != null) {
            $this->addURLIndexFilter($pURLIndexFilter);
        }

        if ($pCrawlMode == crawler::CRAWL_MODE_SITEMAP) {
            echo "<?xml version = \"1.0\" encoding = \"UTF-8\" ?>\n";
            echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        }
        $this->go();

        if ($pCrawlMode == crawler::CRAWL_MODE_SITEMAP) {
            echo "</urlset>";
        }

        // At the end, after the process is finished, we print a short
        // report (see method getProcessReport() for more information)
        $mReport = $this->getProcessReport();

        if ($pCrawlMode == crawler::CRAWL_MODE_INDEX) {
            if (PHP_SAPI == "cli")
                $lb = "\n";
            else
                $lb = "<br />";

            echo "Summary:" . $lb;
            echo "Links followed: " . $mReport->links_followed . $lb;
            echo "Documents received: " . $mReport->files_received . $lb;
            echo "Bytes received: " . $mReport->bytes_received . " bytes" . $lb;
            echo "Process runtime: " . $mReport->process_runtime . " sec" . $lb;
        }
        return;
    }

}
