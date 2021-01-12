<?php

$content = file_get_contents('cashlog.json');
$content = json_decode($content, true);
$content[] = $_POST;
file_put_contents('cashlog.json', json_encode($content));


