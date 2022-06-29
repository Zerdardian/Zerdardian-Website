<div class="loginpage account" id="page">
    <?php
        if(isset($error)) {
            ?>
            <div id="error">
                <?=$error['message']?>
            </div>
            <?php
        }
    ?>
    <div id="mainarea">
        <form action="/login" method="post">
            <label for="user">Vul een user in</label>
            <input type="text" name="user" id="user">
            <label for="password">Vul een wachtwoord in!</label>
            <input type="password" name="password" id="password">
            <input type="submit" name="submit" value="submit">
        </form>
    </div>
</div>