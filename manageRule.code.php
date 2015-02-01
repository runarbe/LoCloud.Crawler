<?php

$pageTitle = "Manage Custom Metadata Extraction Rules";

$m_action = util::getPost("action");
$m_collection_id = util::getPostInt("collection_id");
$m_id = util::getPostInt("id");
$m_expression = util::getPost("expression");
$m_element = util::getPost("element");

$m_rule = new rule();
$m_rule->id = $m_id;
$m_rule->element = $m_element;
$m_rule->expression = $m_expression;
$m_rule->collection_id = $m_collection_id;

switch ($m_action) {
    case "update":
        if ($m_id) {
            $m_rule = rule::select($m_id);
        }
        break;
    case "insert":
        if ($m_id) {
            if (!$m_rule->update()) {
                log::write("Could not update element");
            }
        } else {
            rule::insert($m_collection_id,
                    $m_element,
                    $m_expression);
        }
        break;
    case "delete":
        if ($m_id) {
            $m_rule = rule::select($m_id);
            if ($m_rule) {
                $m_rule->delete($m_id);
            }
        }
        break;
}

if ($m_collection_id) {
    $m_crawlurls = rule::getRulesForCollection($m_collection_id);
}