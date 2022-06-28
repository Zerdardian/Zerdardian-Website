<pre>
<?php
$lUser = getLeagueUser('Zerdardian');
print_r(getRankUser($lUser['id']));
echo "<br>";
// print_r($_SESSION['game']);