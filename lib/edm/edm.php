<?php

require_once("edmType.php");
require_once("edmProvidedCHO.php");
require_once("edmWebResource.php");
require_once("edmPlace.php");
require_once("oreAggregation.php");

/**
 * Description of edm
 *
 * @author runarbe
 */
class edm {

    private $_ProvidedCHO = array();
    private $_WebResource = array();
    private $_Place = array();
    private $_Aggregation = array();

    public function addProvidedCHO($pProvidedCHO) {
        $this->_ProvidedCHO[] = $pProvidedCHO;
    }

}
