<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of searchResults
 *
 * @author runarbe
 */
class searchResult {

    /**
     * Total number of results for search
     * @var integer 
     */
    public $numTotal = 0;

    /**
     * Offset of the first item in the searchResult object
     * @var integer 
     */
    public $startAt = 0;

    /**
     * Offset of the last item in the searchResult object
     * @var type 
     */
    public $endAt = 0;

    /**
     * Number of results in the current serachResult object
     * @var integer 
     */
    public $num = 0;

    /**
     * An array of result objects
     * @var ftindex[] 
     */
    public $items = array();

    /**
     * Add an ftindex object to the searchResult object
     * @param ftindex $pItem
     */
    public function addItem($pItem) {
        $this->items[] = $pItem;
        $this->num = count($this->items);
    }

    public function setStartAt($pIndex) {
        $this->startAt = $pIndex;
        $this->endAt = $pIndex + $this->num;
    }

}
