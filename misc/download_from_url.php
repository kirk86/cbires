<?php
function download_img($img_url)
{
	// Define a destination
	$targetFolder = '../cbires/img/gallery/thumbs'; // Relative to the root
	$galleryFolder = '../cbires/img/gallery';
	$crawlerFolder = '../cbires/img/crawler';

	//$verifyToken = md5('unique_salt' . $_POST['timestamp']);

	//$tempFile = $_FILES['Filedata']['tmp_name'];
	$filenameURL = basename($img_url);
    $tempFile = $crawlerFolder."/".$filenameURL;
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
    $galleryPath = $_SERVER['DOCUMENT_ROOT'] . $galleryFolder;

	// Validate the file type
	$fileTypes = array('jpg','jpeg'); // File extensions	
	$fileParts = pathinfo($tempFile);
	$filepath = $crawlerFolder;
	
	if(isset($fileParts['extension']))
	{
		if ( in_array( Tools::strtolower( $fileParts['extension'] ), $fileTypes) )
		{
			// size from online (if exists)
			if(getimagesize($img_url))
			{
				$date      = date_create();
				$timestamp = date_format($date, 'Y-m-d H:i:s');
				$timestamp1 = date_format($date, 'Y-m-d-H-i-s');
				$filename_hash = $timestamp1.'_'.$fileParts['basename'];
				$targetFile = rtrim($targetPath,'/') . '/' . $filename_hash;
				$galleryFile = rtrim($galleryPath,'/') . '/' . $filename_hash;
				
				file_put_contents($tempFile, file_get_contents($img_url));
				$filesize = filesize($tempFile);
				
				$filemime = 'image/'.$fileParts['extension'];
				
				PopulateImages::insertHistValuesToDB($tempFile, $galleryFile, $targetFile, 
                                                     $fileParts, $filepath, $filemime, 
                                                     $filesize, $timestamp, $filename_hash);
				//echo 'file uploaded successfully '.$rowCount;
				
				?>
				<div style="float:left;padding:10px;">
					<img src="<?php echo $img_url;?>" width="100" height="100" />
				</div>
				<?php

				// delete image
				unlink($tempFile);
			}
		}
	}
}
?>
