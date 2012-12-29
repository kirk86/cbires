<?php

// Define a destination
$targetFolder = '/cbires/uploads'; // Relative to the root

//$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES))
{
    $msg = '<button class="btn btn-primary noty" data-upload-options=';
    $msg .= '{"text":"This is a success information","layout":"top","type":"information"}';
    $msg .= ">".'<i class="icon-bell icon-white"></i> Top Full Width</button>';
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array($fileParts['extension'], $fileTypes))
    {
        move_uploaded_file($tempFile, $targetFile);
		echo 'uploads';
        //echo '{"text":"File '.$_FILES['Filedata']['name'].' successfuly saved to: '.$targetFolder."/".$_FILES['Filedata']['name'].'","layout":"top","type":"information"}';
    }
	else
		echo 'Invalid file type.';
}
?>
