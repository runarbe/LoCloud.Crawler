<?php

/**
 * Description of acl
 */
class acl {

    public function update() {
        
    }

    /**
     * Add a new ACL entry
     * @param string $pSrcType
     * @param integer $pSrcID
     * @param string $pDstType
     * @param integer $pDstID
     * @param aclRight $pAclRight
     * @return boolean True on success, false on error
     */
    public static function insert($pSrcType,
            $pSrcID,
            $pDstType,
            $pDstID,
            $pAclRight) {
        return db::execute(sprintf(
                                "INSERT INTO acl (src_type, src_id, dst_type, dst_id, acl_right) VALUES ('%s', '%s', '%s', '%s', %s)",
                                $pSrcType,
                                $pSrcID,
                                $pDstType,
                                $pDstID,
                                $pAclRight
        ));
    }

    public function delete() {
        
    }

    public static function deleteUsrAclEntries($pUsrID) {
        return acl::deleteByTypeAndID(objType::User,
                        $pUsrID);
    }

    public static function deleteByTypeAndID($pType,
            $pID) {
        return db::execute(sprintf("DELETE FROM acl WHERE (src_type='%s' AND src_id=%s) OR (dst_type='%s' AND dst_id=%s)",
                                $pType,
                                $pID,
                                $pType,
                                $pID));
    }

    /**
     * 
     * @param objType $pDstType
     * @param aclRight $pRight
     * @return boolean
     */
    public function requireUsrRight($pDstType,
            $pRight) {
        $mRes = db::executeScalar(sprintf(
                                "SELECT id FROM acl WHERE src_type='usr' AND src_id=%s AND right > %s",
                                $pDstType,
                                $pRight));
        if (is_numeric($mRes)) {
            return true;
        } else {
            return false;
        }
    }

    public static function select($pID) {
        
    }

}
