<?php

$pageTitle = "Upload Google Sitemap XML";

$m_collection_id = util::getPostInt("collection_id");

if ($m_collection_id) {
    $m_collection = collection::select($m_collection_id);

    if (isset($_FILES["url_file"])) {

        $m_delete_existing = util::getPostBoolean("delete_existing");

        $mTgtDir = "uploads/";
        $mFile = $mTgtDir . basename($_FILES["url_file"]["name"]);
        $mUploadOK = 1;
        $mFileType = pathinfo($mFile,
                PATHINFO_EXTENSION);

        $m_id = filter_input(INPUT_POST,
                "collection_id",
                FILTER_VALIDATE_INT);

        if (!$m_id) {
            log::write("No collection ID specified, aborted upload.");
        }

// Check if file already exists
        if (file_exists($mFile)) {
            log::write("Sorry, file already exists: " . $mFile);
            $mUploadOK = 0;
        } else {
// Check file size
            if ($_FILES["url_file"]["size"] > (2000 * 1024)) {
                log::write("Sorry, your file is too large, maximum 2 Mb file-size is permitted.");
                $mUploadOK = 0;
            }
// Allow certain file formats
            if ($mFileType != "xml" && $mFileType != "txt") {
                log::write("Sorry, only XML and TXT files are allowed.");
                $mUploadOK = 0;
            }
// Check if $uploadOk is set to 0 by an error
            if ($mUploadOK == 0) {
                log::write("Sorry, an error occurred, your file was not uploaded.");
// if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES["url_file"]["tmp_name"],
                                $mFile)) {
                    log::setMsg("The file " . basename($_FILES["url_file"]["name"]) . " has been uploaded successfully.");
                } else {
                    log::write("There was an error uploading the file: " . $mFile);
                }
            }

            $m_collection = collection::select($m_id);

            if ($m_delete_existing && $m_collection_id) {
                url::deleteCollectionURLs($m_collection_id);
            }

            if ($mFileType == "xml") {
                $mNumberOfURLs = $m_collection->importGoogleSitemap($mFile);
                log::setMsg("Inserted " . $mNumberOfURLs . " URLs...");
                unlink($mFile);
            }
        }
    }
}