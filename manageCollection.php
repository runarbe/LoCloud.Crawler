<?php
include("inc/header.php");
?>
<h2>List of collections owned by you</h2>
<table class="pure-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        $m_collections = collection::getUsrCollections();
        foreach ($m_collections as $c_collection) {
            if ($i % 2 == 0) {
                ?>
                <tr class="pure-table-odd">
                    <?php
                } else {
                    ?>
                <tr>
                    <?php
                }
                $i++;
                ?>
                <td><?php echo $c_collection->id ?></td>
                <td><?php echo $c_collection->name ?></td>
                <td><?php echo $c_collection->ctype ?></td>
                <td>
                    <form method="POST" action="#form">
                        <button type="submit" name="action" value="update" class="pure-button pure-button-primary">Update</button>
                        <button type = "submit" name = "action" value="delete" class = "pure-button pure-button-primary">Delete</button>
                        <button type = "submit" name = "action" value="delete_urls" class = "pure-button pure-button-primary">Delete URLs</button> 
                        <button type = "submit" name = "action" value="re_index" class = "pure-button pure-button-primary">Re-index</button>
                        <input type="hidden" name="id" value="<?php echo $c_collection->id ?>"/>
                    </form>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
<a name="form"/>
<h2>Add new or update selected collection</h2>
<form class = "pure-form pure-form-aligned" method = "POST">
    <fieldset>
        <div class = "pure-control-group">
            <label>Collection name</label>
            <input name = "name" type = "text" value="<?php echo $m_collection->name ?>"/>
        </div>
        <div class = "pure-control-group">
            <label>Default location</label>
            <input name = "geo" type = "text" value="<?php echo $m_collection->geo ?>">
        </div>
        <div class = "pure-control-group">
            <label>Default content type</label>
            <select name = "ctype">
                <?php
                util::const2HtmlOptions(edmType,
                        $m_collection->ctype,
                        true)
                ?>
            </select>        
        </div>
        <div class = "pure-control-group">
            <label>Default license</label>
            <select name = "license" class="pure-u-1-2">
                <?php
                echo rightType::getHTMLOptions($m_collection->license);
                ?>
            </select>        
        </div>
        <div class = "pure-control-group">
            <label></label>
            <button type = "submit" name="action" value="insert" class = "pure-button pure-button-primary">Save</button>
        </div>
    </fieldset>
    <input type="hidden" name="id" value="<?php echo $m_collection->id ?>"/>
</form>
<?php
include("inc/footer.php");
