<div id="dashboard" class="user">
    <?php
    if (empty($_GET['two']) || $_GET['two'] == '.php') {
    ?>
        <div class="empty emptyacc">
            <h2>Dit was niet de bedoeling...</h2>
            <p>Ben je opzoek naar je dashboard?</p>
            <div class="buttons">
                <div class="button">
                    <a href="/user/dashboard">
                        <button>
                            Dashboard
                        </button>
                    </a>
                </div>
            </div>
            <p><a href="/">Keer terug naar de home pagina!</a></p>
        </div>
        <?php
    } else {
        $item = str_replace('.php', '', $_GET['two']);
        if (!empty($_GET['three'])) {
            $setting = strtolower(str_replace('.php', '', $_GET['three']));
        } else {
            $setting = 'home';
        }

        if ($item == 'dashboard') {
            if (empty($_SESSION['user']['acc'])) {
                header('location: /login');
            }
            if (file_exists("./assets/pages/dashboard/$setting.php")) {
                $user = $conn->query("SELECT * FROM users INNER JOIN userinfo INNER JOIN settings WHERE users.userid = '$userid'")->fetch();
        ?>
                <div id="dashheader">
                    <div class="dashbackground"></div>
                    <div class="dashheadinfo">
                        <div class="dashinfo">
                            <div class="username">
                                <div class="text displayusername">
                                    <?= $user['displayname'] ?>
                                </div>
                            </div>
                            <div class="profilepicture">
                                <div class="image">
                                    <img src="<?=$profilepicture?>" alt="Profile Icon">
                                </div>
                            </div>
                        </div>
                        <div class="dashmenu" data-item="<?= $setting ?>">
                            <a href="/user/dashboard">
                                <div class="item" data-item="home">
                                    Home
                                </div>
                            </a>
                            <a href="/user/dashboard/connections">
                                <div class="item" data-item="connections">
                                    Connections
                                </div>
                            </a>
                            <a href="/user/dashboard/messages">
                                <div class="item" data-item="messages">
                                    Messages
                                </div>
                            </a>
                            <a href="/user/dashboard/settings">
                                <div class="item" data-item="settings">
                                    Settings
                                </div>
                            </a>
                        </div>
                    </div>
                </div><?php
                        include_once "./assets/pages/dashboard/$setting.php";
                    } else {
                        ?>
                <div class="notfound error">
                    <h1>Pagina niet gevonden!</h1>
                    <p>De volgende pagina waar je naar zocht, bestaat niet, is ooit verwijderd of er is iets fout gegaan aan mijn kant! Keer terug naar de <a href="/user/dashboard">dashboard</a> pagina</p>
                </div>
    <?php
                    }
                } else {
                    $targetuser = str_replace('.php', '', $_GET['two']);
                    if ($targetuser == $username) {
                        header('location: /user/dashboard');
                    }
                }
            } ?>
</div>