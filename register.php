<?php
include("inc/header.php");
?>
<form class = "pure-form pure-form-aligned" action = "register.php" method = "POST">
    <fieldset>
        <div class = "pure-control-group">
            <label for = "usr">Username</label>
            <input id = "usr" name = "usr" type = "text" value="test"/>
        </div>
        <div class = "pure-control-group">
            <label for = "pwd">Password</label>
            <input id = "pwd" name = "pwd" type = "password" value="test">
        </div>
        <div class = "pure-control-group">
            <label for = "pwd">Repeat password</label>
            <input id = "pwd2" name = "pwd2" type = "password" value="test">
        </div>
        <div class = "pure-control-group">
            <label ></label>
            <button type = "submit" class = "pure-button pure-button-primary">Register</button>
        </div>
    </fieldset>
</form>
<?php
include("inc/footer.php");
