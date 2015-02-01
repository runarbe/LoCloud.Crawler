<?php
include("inc/header.php");
?>
<h2>Select collection to add custom rule for</h2>
<form class = "pure-form pure-form-aligned" method = "POST">
    <fieldset>
        <div class = "pure-control-group">
            <label for = "collection_id">Collection</label>
            <select id = "collection_id" name = "collection_id">
                <?php
                echo collection::getUsrCollectionsHTMLOptions($m_collection_id);
                ?>
            </select>
        </div>
        <div class = "pure-control-group">
            <label></label>
            <button type = "submit" name="action" value="select" class = "pure-button pure-button-primary">Select</button>
        </div>
    </fieldset>
</form>
<?php
if ($m_collection_id) {
    ?>
    <h2>Add new or update existing rule</h2>
    <form class = "pure-form pure-form-aligned" method = "POST">
        <fieldset>
            <div class = "pure-control-group">
                <label>Target element</label>
                <select name = "element">
                    <?php
                    util::vars2HtmlOptions(meta,
                            $m_rule->element,
                            false);
                    ?>
                </select>        
            </div>
            <div class = "pure-control-group">
                <label>Query expression</label>
                <input name = "expression" type = "text" value="<?php echo $m_rule->expression ?>">
            </div>
            <div class = "pure-control-group">
                <label></label>
                <button type = "submit" name="action" value="insert" class = "pure-button pure-button-primary">Save</button>
                <button type = "submit" name = "action" value="delete" class = "pure-button">Delete</input>
            </div>
        </fieldset>
        <input type="hidden" name="id" value="<?php echo $m_rule->id ?>"/>
        <input type="hidden" name="collection_id" value="<?php echo $m_rule->collection_id ?>"/>
    </form>
    <?php
    if ($m_crawlurls) {
        ?>
        <h2>Rules for Collection
        </h2>
        <table class="pure-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Element</th>
                    <th>Expression</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($m_crawlurls as $m_row) {
                    /* @var $m_row rule */
                    echo sprintf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>",
                            $m_row->id,
                            $m_row->element,
                            $m_row->expression,
                            util::getActionsHTML("id",
                                    $m_row->id,
                                    "collection_id",
                                    $m_row->collection_id));
                }
                ?>
            </tbody>
        </table>
        <?php
    }
}
include("inc/footer.php");
