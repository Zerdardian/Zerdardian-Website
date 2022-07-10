<?php
$connections = $conn->query("SELECT * FROM connections WHERE `userid`='$userid'")->fetch();
?>

<div id="connections" class="connectionss">
    <div id="leagueoflegends">
        <?php
        if (!empty($connections['cleagueuser'])) {
        } else {
        ?>
            <div class="title">
                <h1>League of Legends</h1>
            </div>
            <div class="items row-2">
                <div class="item user">
                    <div class="items col">
                        <div class="item">
                            <input type="text" name="tleagueuser" id="tleagueuser" class="input">
                            <select name="leagueserver" id="tleagueserver">
                                <option value="">Kies een server</option>
                                <option value="euw1">EUW</option>
                                <option value="eun1">EUNE</option>
                                <option value="br1">BR</option>
                                <option value="jp1">JP</option>
                                <option value="la1">LA - 1</option>
                                <option value="la2">LA - 2</option>
                                <option value="oc1">OC</option>
                                <option value="tr1">TR</option>
                                <option value="RU">RU</option>
                            </select>
                            <button id="tleaguesearch">Zoeken</button>
                        </div>
                        <div class="item" id="fleagueuser">

                        </div>
                    </div>
                </div>
                <div class="item empty"></div>
            <?php
        }
            ?>
            </div>
    </div>
    <div id="connecting" class="conLeagueAcc" data-type="" data-name="" data-id=""></div>
</div>