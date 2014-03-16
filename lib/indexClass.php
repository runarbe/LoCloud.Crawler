<?php

class indexClass {

    public $persistent_uri;
    public $title;
    public $description;
    public $image = array();
    public $date = array();
    public $point = array();
    public $type;
    public $relations = array();

    public function __construct() {
    }

    public function addPoint($pName, $pLon = null, $pLat = null) {
        $this->point[] = new dcmiPoint($pName, $pLon, $pLat);
    }

    public function asXml() {
        $xml = "\n<object>\n";
        $mObjVars = get_object_vars($this);
//print_r($mObjVars);
        foreach ($mObjVars as $mKey => $mVal) {
            if ($mVal != null) {
                switch ($mKey) {
                    case "point":
                        if (is_array($mVal)) {
                            foreach ($this->point as $pt) {
                                $xml .= $pt->asXml();
                            }
                        }
                        break;
                    default:
                        if (is_array($mVal)) {
                            $xml .= getTags($mKey, $mVal, 1);
                        } else {
                            $xml .= getTag($mKey, $mVal, 1);
                        }
                        break;
                }
            }
        }
        $xml .= "</object>\n";
        return $xml;
    }

    public function asJSON() {
        $json = json_encode($this);
        return $json;
    }

}

function getTag($pTag, $pValue, $pIndent = 0) {
    $pValue = trim($pValue);
    return sprintf("%s<%s>%s</%s>\n", str_repeat("\t", $pIndent), $pTag, $pValue, $pTag);
}

function getTags($pTag, $pValues, $pIndent = 0) {
    $tags = "";
    foreach ($pValues as $pValue) {
        $pValue = trim($pValue);
        $tags .= sprintf("%s<%s>%s</%s>\n", str_repeat("\t", $pIndent), $pTag, $pValue, $pTag);
    }
    return $tags;
}

class dcmiPoint {

    public $name;
    public $east;
    public $north;

    public function __construct($pName = null, $pLon = null, $pLat = null) {
        $this->name = $pName;
        $this->east = $pLon;
        $this->north = $pLat;
    }

    public function asXml() {
        $xml = null;
        if ($this->name != null) {
            $xml = sprintf("\t<Point name=\"%s\">\n", $this->name);
        } else {
            $xml = "\t<Point>\n";
        }
        if ($this->east != null && $this->north != null) {
            $xml .= getTag("east", $this->east, 2);
            $xml .= getTag("north", $this->north, 2);
        }
        $xml .= "\t</Point>\n";
        return $xml;
    }

}
?>

