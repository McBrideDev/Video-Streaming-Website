<?php
$zip = new ZipArchive;
$res = $zip->open($_SERVER['DOCUMENT_ROOT'].'/xstreamer.zip');
if ($res === TRUE) {
    $zip->extractTo($_SERVER['DOCUMENT_ROOT']);
    $zip->close();
    echo 'ok';
} else {
    echo 'failed';
}