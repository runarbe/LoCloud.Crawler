<?php

include("../inc/header.php");

// User tests
$mNewUsr2 = usr::insert("balle",
                "klorin");
log::write("Created new user");


$mAuthUsr = usr::login("balle",
                "klorin");

log::write("Logged in");

$mColl = collection::insert("Test collection " . rand(1,
                        1000),
                edmType::TEXT);

print_r($mColl);

if ($mColl != false) {
    log::write("Inserted new collection");
    
    url::insert("http://bergheim.dk", $mColl->id);

    //$mColl->delete();
    log::write("Deleted collection");
}



indexer::index("http://bergheim.dk");


//crawler::crawl("bergheim.dk");

usr::logout();
log::write("Logged out");
$mAuthUsr->delete();

include("../inc/footer.php");
