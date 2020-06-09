<?php

if($_GET['action'] == 'add') {
$result = ''



} else if($_GET['action'] == 'change') {




} else if($_GET['action'] == 'clear') {




}

echo '<form action="index.php?action=add" method="post">
 <p>Ваше имя: <input type="text" name="name" /></p>
 <p>Ваш возраст: <input type="text" name="age" /></p>
 <p><input type="submit" /></p>
</form>';