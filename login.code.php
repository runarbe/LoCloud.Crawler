<?php

$pageTitle = "Login";

if (filter_input(INPUT_POST,
                "action") == "logout") {
    usr::logout();
}

$mUsr = filter_input(INPUT_POST,
        "usr",
        FILTER_DEFAULT);
$mPwd = filter_input(INPUT_POST,
        "pwd",
        FILTER_DEFAULT);
log::write($mUsr);
log::write($mPwd);
log::write(usr::login($mUsr,
                $mPwd));
if ($mUsr && $mPwd && usr::login($mUsr,
                $mPwd) != false) {
    util::redirect("manageCollection.php");
}