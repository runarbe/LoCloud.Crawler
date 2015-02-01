<?php
include("inc/header.php");
?>
<form class = "pure-form pure-form-aligned" action = "login.php" method = "POST">
    <fieldset>
        <div class = "pure-control-group">
            <label for = "usr">Username</label>
            <input id = "crawlerBaseUrl" name = "usr" type = "text" value="test">
        </div>
        <div class = "pure-control-group">
            <label for = "pwd">Password</label>
            <input id = "crawlerBaseUrl" name = "pwd" type = "password" value="test">
        </div>
        <div class = "pure-control-group">
            <label ></label>
            <button type = "submit" class = "pure-button pure-button-primary">Login</button>
        </div>
        <div class = "pure-control-group">
            <label ></label>
            <a href="register.php" class = "pure-button">Register</a>
        </div>
    </fieldset>
</form>
<?php
include("inc/footer.php");