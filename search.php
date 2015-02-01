<?php
include("inc/header.php");
?>
<p>Here you can search the test index aggregated by the LoCLoud Crawler Ready Tagging Tools</p>
<form class="pure-form" method="GET">
    <fieldset>
        <div class="pure-g">
            <input name="query_term" type="text" placeholder="Enter search term..." value="<?php echo $m_query_term; ?>" class="pure-u-2-5"> 
            <select name="collection_id" class="pure-u-2-5">
                <option value="">All collections</option>
                <?php
                echo collection::getUsrCollectionsHTMLOptions($m_collection_id);
                ?>
            </select>
            <button type="submit" name="action" value="search" class="pure-button pure-button-primary pure-u-1-5">Search</button>
        </div>
    </fieldset>
</form>
<?php
if ($m_query_term) {
    ?>
    <h2>Search results</h2>
    <ol class="searchResult">
        <?php
        if ($m_search_result->num > 0) {
            echo "<p>Showing $m_search_result->startAt-" . count($m_search_results) . " out of X</p>"
            ?>
            <ol class="searchResult">
                <?php
                foreach ($m_search_result->items as $c_search_result) {
                    /* @var $c_search_result ftindex */
                    echo sprintf("<li>
            <img src=\"%s\"/><h3><a href=\"%s\">%s</a></h3>
            <p>%s Relevance: (%s)</p></li>",
                            $c_search_result->edm_isShownAt,
                            $c_search_result->url,
                            $c_search_result->dc_title,
                            $c_search_result->dc_description,
                            $c_search_result->ranking);
                }
            }
            ?>
        </ol>
        <?php
    }
    include("inc/footer.php");
    