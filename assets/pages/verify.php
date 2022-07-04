<?php
if (isset($_GET['two'])) {
    $email = $_GET['two'];
    $verify = $_GET['three'];

    $select = $conn->query("SELECT * FROM users INNER JOIN verify WHERE users.email = '$email' AND verify.verifiedcode = '$verify'")->fetch();
    $semail = $select['email'];
    $sverify = $select['verifiedcode'];
    $sverified = intval($select['accepted']);

    if ($sverified == 0) {
        $update = $conn->prepare("UPDATE verify SET `accepted`=1 WHERE `email`='$semail' AND `verifiedcode`='$sverify'")->execute();
        ?>
        <div class="accverified">
            <h1>Je account is nu geverifieerd!</h1>
            <p>Bedankt dat je je account wou verifieren en dat je ook echt was!<br>
            Nu dat je account is geverifieerd, kan je ook inloggen en je account bewerken! Ga naar de <a href="/login">login pagina</a> om verder te gaan!<br>
            Ben je al ingelogd? Dan kan je verder gaan op de <a href="/user/dashboard">account pagina!</a><br>
            Je kunt voor de rest deze pagina veilig verlaten!</p>
        </div>
        <?php
    }

    if ($sverified == 1) {
?>
        <div class="accverified">
            <h1>Deze email is al verified!</h1>
            <p>Deze email is al geverifeerd door iemand of door jezelf. Deze link is namelijk ook al gebruikt. duh...<br>
            Je kunt deze pagina veilig verlaten!</p>
        </div>
<?php
    }
}
?>