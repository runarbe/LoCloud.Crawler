<?php

$pageTitle = "Register New User";

$mRegisterMsg = "test";

$mUsr = filter_input(INPUT_POST,
        "usr",
        FILTER_DEFAULT);
$mPwd = filter_input(INPUT_POST,
        "pwd",
        FILTER_DEFAULT);

$mPwd2 = filter_input(INPUT_POST,
        "pwd2",
        FILTER_DEFAULT);

if ($mUsr && $mPwd) {

    if ($mPwd == $mPwd2) {

        if (usr::exists($mUsr) == false) {

            if (usr::insert($mUsr,
                            $mPwd) != false) {
                log::write("Created user $mUsr.");
                util::redirect("login.php");
            }
        } else {
            log::setMsg("A user with the specified name already exists");
        }
    } else {
        log::setMsg("The two password fields are not identical");
    }
} else {
    log::setMsg("Could not create user");
}
