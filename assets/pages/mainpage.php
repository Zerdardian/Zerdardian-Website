<?php
$dateOfBirth = "10-02-2002";
$today = date("Y-m-d");
$diff = date_diff(date_create($dateOfBirth), date_create($today));
?>
<div class="mainpage">
    <div class="main" id="headerpage">
        <div class="shape1 headeritem"></div>
        <div class="shape2 headeritem"></div>
        <div class="shape3 headeritem"></div>
        <div class="mainheader headeritem">
            <div class="text">
                <h1>Zerdardian</h1>
            </div>
            <div class="image">
                <img id="himgmain" src="/assets/images/base/20220629_111629-removebg.png" alt="Image me">
            </div>
        </div>
        <div class="block">
            <div class="item">
                <div class="noitem"></div>
                <div class="text">A Gamer, An Developer. Most things are just things. But this is me. Kjeld. A Developer following his dreams.</div>
            </div>
            <div class="item">
                <div class="noitem"></div>
                <div class="text">
                    <p>
                        Hoi, Mijn naam is Kjeld!<br>
                        Ik zelf ben <?= $diff->format('%y') ?> jaren jong en ben een developer. Zelf in het leven doe ik mijn ding gewoon en ben ik zelf ook een gamer! Waaronder in League of Legends.
                        Vind meer over mij door op de knoppen hieronder te drukken of naar de aboutme pagina te gaan.
                    </p>
                    <div class="buttons">
                        <a href="/aboutme">
                            <button>Over mij</button>
                        </a>
                        <a href="/portofolio">
                            <button>Portofolio</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="headerending">
        </div>
    </div>
    <?=include_once "./assets/pages/mainpage/party.php"?>
</div>