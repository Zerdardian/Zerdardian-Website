<?php
if(!empty($_SESSION['user']['acc'])) {
    header('location: /user/dashboard');
}
?>
<div class="registerpage account" id="page">
    <?php
    if (isset($error)) {
    ?>
        <div id="error">
            <div class="mainmessage">
                <b>We ran in to a problem while trying to register your account!</b>
            </div>
            <?php
            foreach ($error as $message) {
            ?>
                <div class="message" data-errorhover="<?= $message['type'] ?>">
                    <?= $message['message'] ?>
                </div>
            <?php
            }
            ?>
        </div>
    <?php
    }
    ?>
    <div id="mainarea">
        <form action="/register" method="post">
            <label for="username">Kies een username</label>
            <input type="text" name="username" id="username" class="forminput" placeholder="Voorbeeld: Zerdardian">
            <label for="email">Kies een gebruikelijke email!</label>
            <input type="email" name="email" id="email" class="forminput" placeholder="voorbeeld: noreply@zerdardian.com">
            <label for="password">Kies een sterk wachtwoord!</label>
            <input type="password" name="password" id="password" class="forminput" placeholder="Denk aan een sterk wachtwoord">
            <label for="repassword">Voer opnieuw het wachtwoord in!</label>
            <input type="password" name="repassword" id="repassword" class="forminput" placeholder="Herhaal dit wachtwoord!">
            <div class="jserror" id="jserror"></div>
            <input type="submit" name="submit" value="submit" class="formbutton">
        </form>
    </div>
    <div class="noacc">
            Al een account? <a href="/login">Log dan in!</a>
    </div>
</div>