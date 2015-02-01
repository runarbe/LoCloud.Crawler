<?php

$pageTitle = "Manage Collections";

//print_r($_POST);

$m_action = util::getPost("action");

$m_id = util::getPostInt("id");

$m_name = util::getPost("name");

$m_ctype = util::getPost("ctype");

$m_license = util::getPost("license");

$m_geo = util::getPost("geo");

$m_collection = new collection($m_name,
        $m_ctype,
        $m_license,
        $m_geo,
        $m_id);

switch ($m_action) {
    case "insert":
        if ($m_id && $m_collection->isComplete()) {
            $m_collection->update();
        } elseif (!$m_id && $m_name) {
            collection::insert($m_name,
                    $m_ctype,
                    $m_geo,
                    $m_license);
        }
        break;
    case "update":
        if ($m_id) {
            $m_collection = collection::select($m_id);
            //print_r($m_collection);
        } else {
            log::write("Could not select collection");
        }
        break;
    case "delete":
        if (is_numeric($m_id)) {
            $m_collection = collection::select($m_id);            
            $m_collection->delete();
        } else {
            log::write("Could not delete collection");
        }
        break;
    case "re_index":
        if ($m_id != false) {
            ftindex::deleteCollectionFTIndex($m_id);
            log::setMsg("Deleted all index items for the collection");
        }
        break;
    case "delete_urls":
        if ($m_id != false) {
            ftindex::deleteCollectionFTIndex($m_id);
            url::deleteCollectionURLs($m_id);
            log::setMsg("Deleted all URLs and index items for the collection");
        }
        break;
    default:
        break;
}