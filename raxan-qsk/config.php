<?php

// setup PDO Database connector for employees database
$config['db.raxandb'] = array(
    'dsn'       => 'mysql: host=localhost:3306; dbname=raxandb',
    'user'      => 'root',
    'password'  => '',
    'attribs'   => PDO::ERRMODE_EXCEPTION // use pdo exception mode
);

?>
