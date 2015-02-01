<?php

$incDir = dirname(__FILE__);

/**
 * Include config file, always first
 */
require_once($incDir . "/../config.php");

/**
 * Include external libraries
 */
require_once($incDir . "/../dep/simple_html_dom.php");
require_once($incDir . "/../dep/ObjectAndXML.php");
require_once($incDir . "/../lib/edm/edm.php");
require_once($incDir . "/../dep/PHPCrawl/classes/PHPCrawler.class.php");

/**
 * Include enumerations, code-lists
 */
require_once($incDir . "/../lib/aclRight.enum.php");
require_once($incDir . "/../lib/usrType.enum.php");
require_once($incDir . "/../lib/objType.enum.php");
require_once($incDir . "/../lib/urlStatus.enum.php");
require_once($incDir . "/../lib/rightType.enum.php");

/**
 * Include base classes
 */
require_once($incDir . "/../lib/meta.class.php");
require_once($incDir . "/../lib/searchResult.class.php");
require_once($incDir . "/../lib/ftindex.class.php");
require_once($incDir . "/../lib/crawlurl.class.php");
require_once($incDir . "/../lib/db.class.php");
require_once($incDir . "/../lib/util.class.php");
require_once($incDir . "/../lib/indexer.class.php");
require_once($incDir . "/../lib/log.class.php");
require_once($incDir . "/../lib/usr.class.php");
require_once($incDir . "/../lib/collection.class.php");
require_once($incDir . "/../lib/grp.class.php");
require_once($incDir . "/../lib/acl.class.php");
require_once($incDir . "/../lib/rule.class.php");
require_once($incDir . "/../lib/url.class.php");
require_once($incDir . "/../lib/crawler.class.php");

/*
 * Important that class definintions come before session_start for proper
 * serialization of objects in SESSION
 */
session_start();

/**
 * Include code-behind file
 */
$mCodeBehindFileName = util::getCodeBehindFileNameIfExists($incDir . "/../");
if ($mCodeBehindFileName != false) {
    require_once($mCodeBehindFileName);
}

/**
 * Include the phpcrawl-mainclass
 */
require_once($incDir . "/../lib/main.class.php");
