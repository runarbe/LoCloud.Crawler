<?php
include("inc/header.php");
?>
<h2>Select a collection to add crawl URL to</h2>
<form class="pure-form pure-form-aligned" method="POST">
    <fieldset>
        <div class="pure-control-group">
            <label>Select collection</label>
            <select name="collection_id">
                <?php
                echo collection::getUsrCollectionsHTMLOptions($m_collection_id);
                ?>
            </select>
        </div>
        <div class="pure-control-group">
            <label ></label>
            <button type="submit" name="action" value="select" class="pure-button pure-button-primary">Select</button>
        </div>
    </fieldset>
</form>
<?php if ($m_collection_id) { ?>
    <h2>Submit a URL</h2>
    <p>From this URL, the LoCloud spider will crawl all the html/text links on your page</p>
    <form class="pure-form pure-form-aligned" method="POST">
        <fieldset>
            <div class="pure-control-group">
                <label for="crawlerBaseUrl">Base URL</label>          
                <input id="crawler_base_url" name="crawler_base_url" type="text" value="http://www.wiltshiremuseum.org.uk/galleries/"> 
            </div>
            <h3>Advanced settings (ignore if you don't know what they mean)</h3>
            <div class="pure-control-group">
                <label >Follow filter</label>          
                <input name="url_follow_filter" type="text" value="Action="/> 
            </div>
            <div class="pure-control-group">
                <label>Index filter</label>          
                <input name="url_index_filter" type="text" value="Action=4"/> 
            </div>
            <div class="pure-control-group">
                <label>Exclude filter</label>          
                <input name="url_exclude_filter" type="text" value="__&"/> 
            </div>
            <div class="pure-control-group">
                <label>Remove query string parameters</label>          
                <input name="url_remove_params" type="text" value="prevID,oprevID"/> 
            </div>
            <div class="pure-control-group">
                <label>Delete existing URLs for collection</label>
                <input name="delete_existing" type="checkbox" checked="false"/>
            </div>
            <div class="pure-control-group">
                <label ></label>
                <button type="submit" name="action" value="insert" class="pure-button pure-button-primary">Save</button>
            </div>
            <input type="hidden" name="collection_id" value="<?php echo $m_collection_id; ?>" />
        </fieldset>
    </form>
    <h2>URLs to be crawled for the collection
    </h2>
    <table class="pure-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Crawler URL</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (is_array($m_urls))
                foreach ($m_urls as $c_url) {
                    /* @var $c_url crawlurl */
                    echo sprintf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>",
                            $c_url->id,
                            $c_url->url,
                            $c_url->status,
                            util::getActionsHTML("id",
                                    $c_url->id,
                                    "collection_id",
                                    $c_url->collection_id));
                }
            ?>
        </tbody>
    </table>
    <?php
}
include("inc/footer.php");
