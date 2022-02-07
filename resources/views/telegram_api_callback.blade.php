<?php

$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$txt = "Telegram Callback\n";
fwrite($myfile, $txt);
fclose($myfile);

