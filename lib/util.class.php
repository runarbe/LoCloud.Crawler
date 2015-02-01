<?php

/**
 * A utility class, placeholder for various useful functions
 */
class util {

    /**
     * Remove query string parameters and their values from an URL
     * @param string $pURL
     * @param string $pParam
     * @return string URL without query string parameter
     */
    public static function removeQueryStringParam($pURL,
            $pParam) {
        return preg_replace('/([?&])' . $pParam . '=[^&]+(&|$)/',
                '$1',
                $pURL);
    }

    /**
     * Get an integer HttpPost parameter
     * @param type $pKey
     * @param type $pDefault
     * @return type
     */
    public static function getPostInt($pKey,
            $pDefault = false) {
        return util::getPost($pKey,
                        $pDefault,
                        FILTER_VALIDATE_INT);
    }

    /**
     * Get an integer HttpGet parameter
     * @param type $pKey
     * @param type $pDefault
     * @return type
     */
    public static function getGetInt($pKey,
            $pDefault = false) {
        return util::getGet($pKey,
                        $pDefault,
                        FILTER_VALIDATE_INT);
    }

    /**
     * Get a post parameter as a floating point number
     * @param type $pKey
     * @param type $pDefault
     * @return type
     */
    public static function getPostFloat($pKey,
            $pDefault = false) {
        return util::getPost($pKey,
                        $pDefault,
                        FILTER_VALIDATE_FLOAT);
    }

    /**
     * Get a post parameter as a URL
     * @param type $pKey
     * @param type $pDefault
     * @return type
     */
    public static function getPostURL($pKey,
            $pDefault = false) {
        return util::getPost($pKey,
                        $pDefault,
                        FILTER_VALIDATE_URL);
    }

    /**
     * Get am email address POST parameter
     * @param string $pKey
     * @param mixed $pDefault = false
     * @return string|mixed E-mail address if valid, otherwise $pDefault
     */
    public static function getPostEMail($pKey,
            $pDefault = false) {
        return util::getPost($pKey,
                        $pDefault,
                        FILTER_VALIDATE_EMAIL);
    }

    /**
     * Get a boolean post parameter
     * @param string $pKey
     * @param mixed $pDefault = null
     * @return boolean|mixed True or false if boolean, otherwise return value of $pDefault
     */
    public static function getPostBoolean($pKey,
            $pDefault = null) {
        return util::getPost($pKey,
                        $pDefault,
                        FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Create a new object and populate it with the values from an array
     * where the property names match the array keys
     * @param string $pClass The name of a class
     * @param mixed[] $pArray
     * @return object
     */
    public static function createClassFromArray($pClass,
            $pArray,
            $pOmitKeys = array()) {
        $mClass = new $pClass();
        $mRClass = new ReflectionClass($pClass);
        foreach ($mRClass->getProperties() as $mProperty) {
            /* @var $mProperty ReflectionProperty */
            if (array_key_exists($mProperty->name,
                            $pArray)) {
                $mKey = $mProperty->name;
                $mClass->$mKey = $pArray[$mKey];
            }
        }
        return $mClass;
    }

    /**
     * Return a HttpPost parameter
     * @param string $pKey
     * @param mixed $pDefault
     * @param FILTER_VALIDATE_* $pFilter
     * @return mixed
     */
    public static function getPost($pKey,
            $pDefault = false,
            $pFilter = FILTER_DEFAULT) {

        $mVar = filter_input(INPUT_POST,
                $pKey,
                $pFilter);

        if ($pFilter != FILTER_VALIDATE_BOOLEAN && $mVar == false) {
            return $pDefault;
        } else if ($pFilter == FILTER_VALIDATE_BOOLEAN && $mVar == null) {
            return $pDefault;
        }
        return $mVar;
    }

    /**
     * Return a HttpGet parameter
     * @param string $pKey
     * @param mixed $pDefault
     * @param FILTER_VALIDATE_* $pFilter
     * @return mixed
     */
    public static function getGet($pKey,
            $pDefault = false,
            $pFilter = FILTER_DEFAULT) {

        $mVar = filter_input(INPUT_GET,
                $pKey,
                $pFilter);

        if ($pFilter != FILTER_VALIDATE_BOOLEAN && $mVar == false) {
            return $pDefault;
        } else if ($pFilter == FILTER_VALIDATE_BOOLEAN && $mVar == null) {
            return $pDefault;
        }
        return $mVar;
    }

    /**
     * Get the constants of a class as HTML options suitable for use in a form select control
     * @param class $pClass Name of a valid class
     * @param mixed $pSelectedValue
     * @param boolean $pIncludeUndefined
     */
    public static function const2HtmlOptions($pClass,
            $pSelectedValue,
            $pIncludeUndefined = true) {

        $mRClass = new ReflectionClass($pClass);

        if ($pIncludeUndefined) {
            echo "<option value=\"\">-</option>";
        }

        foreach ($mRClass->getConstants() as $mKey => $mVal) {
            if ($mVal == $pSelectedValue) {
                $mSelected = "selected=\"true\"";
            } else {
                $mSelected = "";
            }
            echo "<option value=\"$mVal\" $mSelected>$mKey</option>";
        }
    }

    /**
     * Get the variables of a class as HTML options suitable for use in a form select control
     * @param class $pClass Name of a valid class
     * @param mixed $pSelectedValue
     * @param boolean $pIncludeUndefined
     */
    public static function vars2HtmlOptions($pClass,
            $pSelectedValue,
            $pIncludeUndefined = true) {

        $mRClass = new ReflectionClass($pClass);

        if ($pIncludeUndefined) {
            echo "<option value=\"\">-</option>";
        }

        foreach ($mRClass->getProperties() as $mProperty) {

            /* @var $mProperty ReflectionProperty */

            $mVal = $mProperty->name;
            $mKey = str_replace("_",
                    ":",
                    $mVal);

            if ($mVal == $pSelectedValue) {
                $mSelected = "selected=\"true\"";
            } else {
                $mSelected = "";
            }
            echo "<option value=\"$mVal\" $mSelected>$mKey</option>";
        }
    }

    /**
     * Include a code-behind file for a PHP template file
     * @param string $rootDir The root directory of the PHP application
     * @return string|boolean The codebehind filename on success, false if no codebehind file
     */
    public static function getCodeBehindFileNameIfExists($rootDir) {
        $mScriptName = $_SERVER["SCRIPT_NAME"];
        if (is_string($mScriptName)) {
            $mPathInfo = pathinfo($mScriptName);
            $mScript = $mPathInfo["filename"];
            $mCodeBehind = $mScript . ".code.php";
            if (file_exists($rootDir . $mCodeBehind)) {
                return $rootDir . $mCodeBehind;
            } else {
                return false;
            }
        }
    }

    /**
     * Redirect the current response to another URL
     * @param string $pUrl URL to redirect to
     */
    public static function redirect($pUrl) {
        header("Location: " . $pUrl);
    }

    public static function getActionsHTML($pIDVarName,
            $pIDVarValue,
            $pIDVarName2 = null,
            $pIDVarValue2 = null,
            $pUpdate = true,
            $pDelete = true) {
        $mHTML .= "<form method=\"POST\">";
        if ($pUpdate) {
            $mHTML .= " <button type = \"submit\" name = \"action\" value = \"update\" class = \"pure-button pure-button-primary\">Update</button>";
        }
        if ($pDelete) {
            $mHTML .= " <button type = \"submit\" name = \"action\" value = \"delete\" class = \"pure-button pure-button-primary\">Delete</button>";
        }
        if ($pIDVarName2 != null && $pIDVarValue2 != null) {
            $mHTML .= sprintf("<input type = \"hidden\" name = \"%s\" value = \"%s\"/>",
                    $pIDVarName2,
                    $pIDVarValue2);
        }
        $mHTML .= sprintf("<input type = \"hidden\" name = \"%s\" value = \"%s\"/>",
                $pIDVarName,
                $pIDVarValue);
        $mHTML .= "</form>";
        return $mHTML;
    }

    /**
     * Download the data from a URL
     * @param string $pURL URL to download
     * @return string|false Data returned from URL
     */
    public static function downloadURL($pURL) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch,
                CURLOPT_URL,
                $pURL);
        curl_setopt($ch,
                CURLOPT_RETURNTRANSFER,
                1);
        curl_setopt($ch,
                CURLOPT_CONNECTTIMEOUT,
                $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * Checks if a variable is of a specific type
     * @param mixed $pVar
     * @param mixed $pDefault = false
     * @param FILTER_* $pFilter = FILTER_DEFAULT
     * @return mixed|boolean The variable if valid, otherwise false
     */
    public static function is_type($pVar,
            $pDefault = false,
            $pFilter = FILTER_DEFAULT) {
        $mVar = filter_var($pVar,
                $pFilter);

        if ($pFilter == FILTER_VALIDATE_BOOLEAN && $mVar == null) {
            $mVar = $pDefault;
        } else if ($pFilter != FILTER_VALIDATE_BOOLEAN && $mVar == false) {
            $mVar = $pDefault;
        }
        return $mVar;
    }

    public static function is_url($pVar,
            $pDefault = false) {
        return util::is_type($pVar,
                        $pDefault,
                        FILTER_VALIDATE_URL);
    }

    public static function is_int($pVar,
            $pDefault = false) {
        return util::is_type($pVar,
                        $pDefault,
                        FILTER_VALIDATE_INT);
    }

}
