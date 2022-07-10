<div class="settings">
    <div class="headtext">
        <h1>Settings pagina van <?=$username?></h1>
        <p>Verander hier verschillende settings van jouw username tot andere dingen. Je kunt hier ook instellen wat voor mails je wilt ontvangen van mij! Waaronder, emails.</p>
    </div>
    <div class="items row-2">
        <div class="item">
            <h2>Account settings</h2>
            <div class="items row-2">
                <div class="item items col">
                    <div class="item">
                        <label for="displayname">Verander je Display name</label>
                    </div>
                    <div class="item">
                        <label for="name">Naam</label>
                    </div>
                    <div class="item">
                        <label for="lastname">Achternaam</label>
                    </div>
                </div>
                <div class="item items col">
                    <div class="item">
                        <input type="text" id="displayname" class="input changeable" name="username" value="<?=$user['displayname']?>">
                    </div>
                    <div class="item">
                        <input type="text" id="name" class="input changeable" name="firstname" value="<?=$user['name']?>">
                    </div>
                    <div class="item">
                        <input type="text" id="lastname" class="input changeable" name="lastname" value="<?=$user['lastname']?>">
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="text">
                    <p>Kies een leeftijd</p>
                </div>
                <div class="item items row-3">
                    <input type="number" min=1 max=32 id="bday" class="input changeable" name="Birthday_day" value="<?=$user['bday']?>">
                    <input type="number" min=1 max=12 id="bmonth" class="input changeable" name="Birthday_month" value="<?=$user['bmonth']?>">
                    <input type="number" min=1900 max=<?=date("Y");?> id="byear" class="input changeable" name="Birthday_year" value="<?=$user['byear']?>">
                </div>
            </div>
        </div>
        <div class="item">
            <h2>Email settings</h2>
            <div class="item">
                <?php
                    if($user['mail_blogposts'] == 0 || $user['mail_blogposts'] == false) {
                        $mail['blog'] = '';
                    } else {
                        $mail['blog'] = 'checked';
                    }
                    if($user['mail_messages'] == 0 || $user['mail_messages'] == false) {
                        $mail['messages'] = '';
                    } else {
                        $mail['messages'] = 'checked';
                    }
                    if($user['mail_marketing'] == 0 || $user['mail_marketing'] == false) {
                        $mail['marketing'] = '';
                    } else {
                        $mail['marketing'] = 'checked';
                    }
                ?>
                <p><input type="checkbox" name="allmail" id="allmail" class="checkbox"> Accepteer alle mail</p>
                <p><input type="checkbox" name="mailblogposts" id="mail_blogposts" class="checkbox" <?=$mail['blog']?>> Accepteer mails van nieuwe blogposts</p>
                <p><input type="checkbox" name="mailmessages" id="mail_messages" class="checkbox" <?=$mail['messages']?>> Accepteer mail wanneer je een bericht ontvangt op het Zerdardian netwerk.</p>
                <p><input type="checkbox" name="mailmarketing" id="mail_marketing" class="checkbox" <?=$mail['marketing']?>> Accepteer marketing mails die van het Zerdardian netwerk binnenkomen*</p>
                <p class="disclaimer">
                    Bij het deactiveren van deze box zal je niet alle mails ontvangen rondom marketing maar wel minder tot geen. Je zult alleen mails ontvangen bij grote evenementen rondom de site, en/of streams!
                </p>
            </div>
        </div>
    </div>
    <div class="qna">
        <div class="items col">
            <h1>Veelgestelde vragen</h1>
            <div class="item">
                <b>Hoe kan ik mijn username/email veranderen?</b>
                <p>Nog niet! Momenteel ben ik er nog mee bezig om dingen werkend te krijgen! Dus nog even geduld!</p>
            </div>
            <div class="item">
                <b>Hoe kan ik mijn wachtwoord veranderen?</b>
                <p>Volgende update. Dit gaat ook via hier of al ben je je wachtwoord vergeten via de login pagina! Wachtworden kunnen alleen worden aangepast met de juiste pin die je ontvangt via je mail!</p>
            </div>
            <div class="item">
                <b>Krijg ik nog steeds mails al wil ik ze niet?</b>
                <p>Je krijgt zoizo belangrijke updates rondom mijn site zoals aanpassingen aan mijn terms of service/privacy policy, veranderingen aan je account en systeem berichten! Ook al ben je opinioned out met marketing mails, je zult soms alleen op speciale evenementen een email ontvangen!<br>
                Je zal ook nog steeds mails krijgen, al word je verbannen en/of accountaanpassingen die door ons zijn verricht!</p>
            </div>
            <div class="item">
                <b>Kan ik ook spullen aanpassen zonder dat ik ben ingelogd?</b>
                <p>Nee! Je moet zijn ingelogd om je info aan te passen. Ik hou zoizo men ogen open op python applicaties of andere applicaties die connecties kunnen maken met mijn site. Hou dat maar in de gaten.</p>
            </div>
            <div class="item">
                <b>Wat nou al ben ik te jong?</b>
                <p>Dan wordt jouw account gedeactiveerd tot dat je een gepaste leeftijd hebt behaald! Heb je de verkeerde leeftijd ingevuld? Moet je dit kunnen aantonen per mail. Stuur een mail naar <a href="mailto:info@zerdardian.com">info@zerdardian.com</a> om met mij in contact te komen rondom dit</p>
            </div>
            <div class="item">
                <b>Kan ik mijn account verliezen door met iets te schelden?</b>
                <p>Scheldwoorden of slurs in dat gebied worden meteen verwijderd! Door het te verwijderen van hun informatie of door je account te disablen voor 30 dagen en een full reset.</p>
            </div>
            <div class="item">
                <b>Wat nou al ben ik verbannen?</b>
                <p>Verbanningen kunnen lopen van min 1 dag tot 1 jaar of meer (maximaal 5 jaar). Wanneer je bent verbannen kan je niks aanpassen aan je account en/of data. Andere gebruikers kunnen jou niet zien of jouw connected accounts!<br>
                Verbanningen zijn niet op te heffen en zullen doorlopen. We kunnen je ook een defintiefe account ban geven, waardoor jouw account direct wordt verbannen!<br>
                Defenitieve account bans zal er ook voor zorgen dat je de username/email ook niet meer kan gebruiken! Account bans kunnen niet worden opgeheven!</p>
            </div>
            <div class="item">
                <b>Wat nou al zat ik er midden in?</b>
                <p>Al was jij onschuldig, kan je me een mail sturen via <a href="mailto:info@zerdardian.com">info@zerdardian.com</a> met jouw email en username om verder te gaan!</p>
            </div>
            <div class="item">
                <b>Kan ik nog worden geunbanned?</b>
                <p>Al ben je terecht verbannen, Nee!</p>
            </div>
        </div>
    </div>
</div>