<?php
include("inc/header.php");
?>
<h2>Select a collection to add URLs to</h2>
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
    <h2>Select a file to upload</h2>
    <p>Upload a Google Sitemap XML file or a text-file with one URL per line</p>
    <form class="pure-form pure-form-aligned" method="POST" enctype="multipart/form-data">
        <fieldset>
            <div class="pure-control-group">
                <label>Select file</label>
                <div class="fileUpload btn pure-button">
                    <span>Browse...</span>
                    <input name="url_file" type="file" class="upload" />
                </div>            
            </div>
            <div class="pure-control-group">
                <label>Delete existing URLs</label>
                <input name="delete_existing" type="checkbox" checked="false"/>
            </div>
            <div class="pure-control-group">
                <label ></label>
                <button type="submit" name="action" value="upload" class="pure-button pure-button-primary">Upload</button>
            </div>
            <input type="hidden" name="collection_id" value ="<?php echo $m_collection->id ?>"/>
        </fieldset>
    </form>
    <?php
}
include("inc/footer.php");
