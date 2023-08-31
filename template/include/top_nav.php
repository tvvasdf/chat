<?php

use SITE\Main;
use SITE\User;

$menu = include Main::getRoot('/template/data/top_nav.php');
?>

<?php foreach ($menu as $link): ?>
    <?php if (isset($link['auth']) && $link['auth'] != User::authorized()) continue ?>
    <?php if ($link['url'] == Main::getPath()): ?>
        <li class="active"><a><?= $link['name'] ?></a></li>
    <?php else: ?>
        <li><a href="<?= $link['url'] ?>"><?= $link['name'] ?></a></li>
    <?php endif; ?>
<?php endforeach; ?>


<!--<li><a class="drop" href="#">Dropdown</a>-->
<!--    <ul>-->
<!--        <li><a href="#">Level 2</a></li>-->
<!--        <li><a class="drop" href="#">Level 2 + Drop</a>-->
<!--            <ul>-->
<!--                <li><a href="#">Level 3</a></li>-->
<!--                <li><a href="#">Level 3</a></li>-->
<!--                <li><a href="#">Level 3</a></li>-->
<!--            </ul>-->
<!--        </li>-->
<!--        <li><a href="#">Level 2</a></li>-->
<!--    </ul>-->
<!--</li>-->
