<?php

// Define a destination
$targetFolder = '/cbires/uploads'; // Relative to the root and should match the upload folder in the uploader script

if (file_exists($_SERVER['DOCUMENT_ROOT'] . $targetFolder . '/' . $_POST['filename'])) {
	echo 1;
} else {
	echo 0;
}
?>
