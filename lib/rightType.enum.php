<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of licenseTypes
 *
 * @author runarbe
 */
class rightType {

    /**
     * 
     * @return string
     */
    public static function getTypes() {
        $mTypes = array("http://creativecommons.org/publicdomain/mark/1.0/" => "1 The Public Domain Mark (PDM)",
            "http://www.europeana.eu/rights/out-of-copyright-non-commercial.html" => "2 Out of copyright - non commercial re-use (OOC-NC)",
            "http://creativecommons.org/publicdomain/zero/1.0/" => "3 The Creative Commons CC0 1.0 Universal Public Domain Dedication (CC0)",
            "http://creativecommons.org/licenses/by/4.0/" => "4 Creative Commons - Attribution (BY)",
            "http://creativecommons.org/licenses/by-sa/4.0/" => "5 Creative Commons - Attribution, ShareAlike (BY-SA)",
            "http://creativecommons.org/licenses/by-nd/4.0/" => "6 Creative Commons - Attribution, No Derivatives (BY-ND)",
            "http://creativecommons.org/licenses/by-nc/4.0/" => "7 Creative Commons - Attribution, Non-Commercial (BY-NC)",
            "http://creativecommons.org/licenses/by-nc-sa/4.0/" => "8 Creative Commons - Attribution, Non-Commercial, ShareAlike (BY-NC-SA)",
            "http://creativecommons.org/licenses/by-nc-nd/4.0/" => "9 Creative Commons - Attribution, Non-Commercial, No Derivatives (BY-NC-ND)",
            "http://www.europeana.eu/rights/rr-f/" => "10 Free access - no re-use",
            "http://www.europeana.eu/rights/rr-p/" => "11 Paid access - no re-use",
            "http://www.europeana.eu/rights/orphan-work-eu" => "12 Orphan work",
            "http://www.europeana.eu/rights/unknown/" => "13 Unknown");
        return $mTypes;
    }

    public static function getHTMLOptions($pSelectedOptionValue) {
        $mHTML = "";
        print_r($pSelectedOptionValue);
        
        foreach (rightType::getTypes() as $mURL => $mRight) {
            if ($pSelectedOptionValue == $mURL) {
                $mSelected = " selected=\"true\"";
            } else {
                $mSelected = "";
            }

            $mHTML .= sprintf("<option value=\"%s\" %s>%s</option>",
                    $mURL,
                    $mSelected,
                    $mRight);
        }

        return $mHTML;
    }

}
