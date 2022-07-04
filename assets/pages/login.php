<?php
if(!empty($_SESSION['user']['acc'])) {
    header('location: /user/dashboard');
}
?>

<div class="loginpage account" id="page">
    <?php
        if(isset($error)) {
            ?>
            <div id="error">
                <div class="headmessage">
                    <b>We ran in to an error while logging in.</b>
                </div>
                <?php
                    foreach($error as $item) {
                        ?>
                        <div class="message">
                            <?=$item['message']?>
                        </div>
                        <?php
                    }
                ?>
            </div>
            <?php
        }
    ?>
    <div id="mainarea">
        <form action="/login" method="post">
            <label for="user">Vul een gebruiker in in</label>
            <input type="text" name="user" id="user" class='forminput' placeholder="Username of een email">
            <label for="password">Vul een wachtwoord in!</label>
            <input type="password" name="password" id="password" class='forminput' placeholder="Vul jouw wachtwoord in!">
            <input type="submit" name="submit" value="submit" class='formbutton'>
        </form>
    </div>
    <div class="noacc">
        Nog geen account? <a href="/register">Registreer je hier!</a>
    </div>
</div>