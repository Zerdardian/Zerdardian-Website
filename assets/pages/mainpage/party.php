<div id="party" class="minigame daily item">
    <?php
    if (empty($userid) || empty($username) || empty($email)) {
        $spaces = $conn->query('SELECT * FROM `spaces` WHERE `id` BETWEEN 1 AND 10')->fetchAll();
        $curspace['id'] = null;
    } else {
        $curspace = $conn->query("SELECT * FROM `space-player` WHERE `userid`='$userid'")->fetch();
        if (empty($curspace)) {
            $conn->prepare("INSERT INTO `space-player`(`userid`, `spaceid`) VALUES ('$userid',1)")->execute();
            $curspace = $conn->query("SELECT * FROM `space-player` WHERE `userid`='$userid'")->fetch();
            $conn->prepare("UPDATE `spaces` SET `spacecapacity`=+1 WHERE `id`='1'")->execute();
        }

        if ($curspace['spaceid'] < 3) {
            $spaces = $conn->query('SELECT * FROM `spaces` WHERE `id` BETWEEN 1 AND 10')->fetchAll();
        } else {
            $min = $curspace['spaceid'];
            $max = $curspace['spaceid'] + 7;
            $spaces = $conn->query('SELECT * FROM `spaces` WHERE `id` BETWEEN ' . $min . ' AND ' . $max)->fetchAll();
            if(sizeof($spaces) < 8) {
                for($i = sizeof($spaces); $i < 8; $i++) {

                }
            }
        }
    }
    ?>
    <div class="diceblock"></div>
    <div class="board">
        <?php
        foreach ($spaces as $space) {
            switch ($space['spacetype']) {
                case 0:
                    $spacename = 'start';
                    break;
                case 1:
                    $spacename = 'blue';
                    break;
                case 2:
                    $spacename = 'red';
                    break;
                case 3:
                    $spacename = 'question';
                    break;
                case 4:
                    $spacename = 'luck';
                    break;
                case 5:
                    $spacename = 'unluck';
                    break;
            }
        ?>
            <div class="space <?= $spacename ?>" id="space-<?= $space['id'] ?>" data-space=<?= $space['id'] ?>></div>
        <?php
        }
        ?>
    </div>
    <div class="board row none">
        <?php
        foreach ($spaces as $space) {
            if ($curspace['id'] != null) {
                if ($space['id'] == $curspace['spaceid']) { ?>
                    <div class="space" id="mspace-<?= $space['id'] ?>" data-space=<?= $space['id'] ?>>
                        <div class="player display" style="background-image:url('<?= $profilepicture ?>')"></div>
                    </div>
                <?php
                } else {
                ?>
                    <div class="space" id="mspace-<?= $space['id'] ?>" data-space=<?= $space['id'] ?>></div>
                <?php
                }
            } else {
                ?>
                <div class="space" id="mspace-<?= $space['id'] ?>" data-space=<?= $space['id'] ?>></div>
        <?php
            }
        }
        ?>
    </div>
</div>