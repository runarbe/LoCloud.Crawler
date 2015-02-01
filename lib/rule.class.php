<?php

/**
 * Custom metadata extraction rule
 * @author runarbe
 */
class rule {

    /**
     * Identifier of rule
     * @var integer 
     */
    public $id;

    /**
     * Element to be populated from rule
     * @var string
     */
    public $element;

    /**
     * XPath expression to extract information
     * @var string 
     */
    public $expression;

    /**
     * The ID of the collection the custom rule is associated with
     * @var integer 
     */
    public $collection_id;

    /**
     * Default constructor
     */
    public function __construct() {
        
    }

    /**
     * Update a rule
     * @return boolean
     */
    public function update() {
        $mSql = sprintf("UPDATE rule SET collection_id=%s, element='%s', expression='%s' WHERE id = %s",
                $this->collection_id,
                $this->element,
                $this->expression,
                $this->id);
        
        $mRes = db::query($mSql);
        if (db::affectedRows() == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Inserts a new custom extraction rule
     * @param integer $pCollectionID
     * @param string $pElement
     * @param string $pExpression
     * @return boolean True on success, false on error
     */
    public static function insert($pCollectionID,
            $pElement,
            $pExpression) {
        if (usr::isAuth()) {
            return db::execute(sprintf("INSERT INTO rule (collection_id, element, expression) VALUES (%s, '%s', '%s')",
                                    $pCollectionID,
                                    $pElement,
                                    $pExpression));
        } else {
            log::write("Cannot create rule, no user authenticated");
        }
    }

    /**
     * Return a rule based on the rule ID
     * @param type $pRuleID
     * @return rule|false
     */
    public static function select($pRuleID) {
        if (usr::isAuth()) {
            $mRes = db::query(sprintf("SELECT * FROM rule WHERE id = %s;",
                                    $pRuleID));
            if (db::hasRows($mRes)) {
                $mRow = db::getNextRow($mRes);
                return util::createClassFromArray("rule",
                                $mRow);
            } else {
                return false;
            }
        }
    }

    /**
     * Delete a rule based on its ID
     * @param type $pRuleID
     * @return boolean
     */
    public static function delete($pRuleID) {
        if (usr::isAuth()) {
            db::execute(sprintf("DELETE FROM rule WHERE id = %s;",
                            $pRuleID));
            if (db::affectedRows() == 1) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Get all the rules associated with a specific collection
     * @param integer $pCollectionID
     * @return collection[]|boolean
     */
    public static function getRulesForCollection($pCollectionID) {
        if (usr::isAuth()) {
            $mSql = sprintf("SELECT * FROM rule WHERE collection_id=%s",
                    $pCollectionID);
            $mRes = db::query($mSql);

            $mRules = array();
            if ($mRes != false && mysqli_num_rows($mRes) > 0) {
                while ($mRow = db::getNextRow($mRes)) {
                    $mRules[] = util::createClassFromArray("rule",
                                    $mRow);
                }
            }
            return $mRules;
        }
        return false;
    }

}
