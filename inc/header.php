<?php
require_once("inc/require.php");
if (!usr::isAuth() && (strpos($_SERVER["SCRIPT_FILENAME"],
                "login.php" != false || strpos($_SERVER["SCRIPT_FILENAME"],
                        "register.php")) != false)) {
    util::redirect("login.php");
}
?>
<html>
    <head>
        <link href="css/pure-min.css" rel="stylesheet" type="text/css"/>
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="UTF-8"/>
        <title><?php echo $pageTitle; ?> | LoCloud Crawler</title>
    </head>
    <body>
        <div class="pure-g">
            <div class="pure-u-1-5 blackongray fullHeight">
                <div class="addmargins">
                    <h1>LoCloud Crawler</h1>
                    <p>
                        Crawler Ready Tagging Tools (D2.6)
                    </p>
                    <div class="pure-menu pure-menu-open">
                        <ul>
                            <?php
                            if (usr::isAuth()) {
                                ?>
                                <a class="pure-menu-heading">Crawler menu</a>
                                <li><a href="manageCollection.php">Manage collections</a></li>
                                <li><a href="submitUrls.php">Submit URL</a></li>
                                <li><a href="uploadSitemap.php">Submit Sitemap</a></li>
                                <li><a href="manageRule.php">Manage rules</a></li>
                                <li class="pure-menu-heading">Tools</li>
                                <li><a href="search.php">Search demo</a></li>
                                <li class="pure-menu-heading">Authentication</li>
                                <li><a href="login.php?action=logout">Logout</a></li>
                                <?php
                            } else {
                                ?>
                                <li class="pure-menu-heading">Menu</li>
                                <li><a href="login.php">Login</a></li>
                                <li><a href="register.php">Register</a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class = "pure-u-3-5 addmargins">
                <h1><?php echo $pageTitle; ?></h1>
                <p class="infoMessage"><?php echo log::getMsg(); ?></p>
