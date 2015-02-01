<?php

$pageTitle = "Full-Text Search Demo";

$m_collection_id = util::getGetInt("collection_id");

$m_query_term = util::getGet("query_term");

if ($m_query_term != false) {
    $m_search_result = ftindex::search($m_query_term);
}
