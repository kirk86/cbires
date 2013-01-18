<?php require_once('header.php'); ?>

			<div>
				<ul class="breadcrumb">
					<li>
						<a href="index.php">Home</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="gallery.php">Gallery</a>
					</li>
				</ul>
			</div>

			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-picture"></i> Gallery</h2>
						<div class="box-icon">
							<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
                    <!-- START OF: fullscreen button -->
                    <!--
						<p class="center">
							<button id="toggle-fullscreen" class="btn btn-large btn-primary visible-desktop" data-toggle="button">Toggle Fullscreen</button>
						</p>
                    -->
                    <!-- END OF: fullscreen button -->
                    <?php
                    //var_dump($_POST);
                    if ( Tools::getIsset('topk')             && 
                         Tools::getIsset('distanceFunction') && 
                         Tools::getIsset('colorSpace')       && 
                         Tools::getIsset('histogram')        &&
                         Validate::isUnsignedInt(Tools::getValue('topk'))
                        )
                    {
                        $sqlThres = "SELECT COUNT(*) AS total_images FROM tbl_image";
                        $imageThres = DB::getOne($sqlThres);
                        if (Tools::getValue('topk') <= 0 || Tools::getValue('topk') > $imageThres[0])
                            echo MESSAGE_INVALID_TOPK;
                        else
                        {
                            $_SESSION['threshold']        = Tools::getValue('topk');
                            $_SESSION['distanceFunction'] = Tools::getValue('distanceFunction');
                            $_SESSION['colorSpace']       = Tools::getValue('colorSpace');
                            session_write_close();
                            echo MESSAGE_SUCCESS_SETTINGS;
                        }
                    }
                    if ( Tools::isSubmit('requery') && 
                         Tools::getIsset('requery') && 
                         Tools::getValue('requery') == 'relevance_feedback' )
                    {
                        echo "teste";
                    }
                    if (Tools::isSubmit('submit') && Tools::getIsset('submit'))
                    {
                        
                        $targetFolder = '/cbires/img/gallery/thumbs/query_image.jpg';
                        $galleryFolder = '/cbires/img/gallery/query_image.jpg';
                        
                        if ( !empty( $_FILES['fileInput']['name'] ) && 
                             !empty( $_FILES['fileInput']['type'] ) && 
                             !empty( $_FILES['fileInput']['tmp_name'] ) )
                        {
                            $tempFile = $_FILES['fileInput']['tmp_name'];
                            $targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
                            $galleryPath = $_SERVER['DOCUMENT_ROOT'] . $galleryFolder;
                            
                            // Validate the file type
                            $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
                            $fileParts = pathinfo($_FILES['fileInput']['name']);
                            
                            if ( in_array( Tools::strtolower( $fileParts['extension'] ), $fileTypes ) )
                            {
                                $img = new Image($tempFile);
                                $img->save($galleryPath, false);
                                $img->resize(300, 0); // Lower quality image created using width ratio otherwise $img->resize(300, 0, false); for quality not speed
                                $img->save($targetPath);
                                $resized_img = $img->file_name;
                                $_SESSION['resized_img'] = $resized_img;
                                 
                                //$filemime = 'image/'.$fileParts['extension'];
                                if ( isset($_SESSION['colorSpace'])       && !empty($_SESSION['colorSpace'])       &&
                                     isset($_SESSION['distanceFunction']) && !empty($_SESSION['distanceFunction']) &&
                                     isset($_SESSION['threshold'])        && !empty($_SESSION['threshold'])
                                   )
                                {
                                    switch ($_SESSION['colorSpace'])
                                    {
                                        case 'RGB':
                                        $combRGB = PopulateImages::computeRgbImages($resized_img);
                                        break;
                                        
                                        case 'HSV':
                                        $combHSV = PopulateImages::computeHsvImages($resized_img);
                                        break;
                                    }
                                }
                                else { $combRGB = PopulateImages::computeRgbImages($resized_img, true); } 
                                unset($img);
                    ?>
                    <!-- START OF: fullscreen button -->
						<p class="center">
							<button id="toggle-fullscreen" class="btn btn-large btn-primary visible-desktop" data-toggle="button">Toggle Fullscreen</button>
						</p>
                    <!-- END OF: fullscreen button -->
                    <!-- START OF: info message 1 -->    
                        <div class="alert alert-info">
							<p class="center" style="color: blue; font-size: x-large;">Retrieval Results</p>
						</div>
                    <!-- END OF: info message 1 -->
                    <form class="form-horizontal" action="gallery.php" method="post">
        				<fieldset>
        						<ul class="thumbnails gallery">
                                <!-- Query image -->
                                <!--
                                    <li id="image-query" class="thumbnail">
        								<a style="background:url(img/gallery/thumbs/query_image.jpg)" title="Sample Image Query" href="img/gallery/query_image.jpg">
                                            <img class="grayscale" src="img/gallery/thumbs/query_image.jpg" alt="Sample Image Query" />
                                        </a>
        							</li>
                                    -->
                                <!-- Query image -->
                                    <table>
                                        <tr>
        							<?php
                                    if ( isset($_SESSION['threshold']) && !empty($_SESSION['threshold']) && 
                                         isset($_SESSION['colorSpace']) && !empty($_SESSION['colorSpace'])
                                       )
                                    {
                                            switch ($_SESSION['colorSpace'])
                                            {
                                                case 'RGB':
                                                PopulateImages::populateColorSpaceImages($combRGB);
                                                break;
                                                 
                                                case 'HSV':
                                                PopulateImages::populateColorSpaceImages($combHSV);
                                                break;
                                            }
                                            //session_unset();
                                            //session_destroy();
                                     }
                                     else { PopulateImages::populateColorSpaceImages($combRGB, 14); }
                                    ?>
                                    </table>
        						</ul>
                                </table>
                                <p style="text-align: center;">
                                    <button class="btn btn-warning btn-round" type="submit" name="requery" value="relevance_feedback">Requery</button>
                                </p>
						  </fieldset>
						</form>
                        <?php
                           }
                        	else { echo MESSAGE_INVALID_FILE_TYPE; }
                        }
                 }
                 //session_unset();
                 ?> 
                    
                    <legend class="center"></legend>
                    
                    <!-- START OF: info message 3 -->    
                        <div class="alert alert-info">
							<p class="center" style="color: blue; font-size: x-large;">Upload - Use query image from your file system</p>
						</div>
                        
                        <div class="box-content">
						<form class="form-horizontal" action="gallery.php" method="post" target="_self" enctype="multipart/form-data">
						  <fieldset>
							<div class="control-group">
								<p class="center">File input: &nbsp;
                                    <!--<input type="hidden" name="submit-value" id="submit-value" value="submited" />-->
                                    <input class="input-file uniform_on" type="file" id="fileInput" name="fileInput" /> &nbsp;
                                    <button class="btn btn-small btn-danger btn-round" type="submit" id="submit" name="submit">Query :-)</button>
                                </p>
							</div>
						  </fieldset>
						</form>
					   </div>
                    <!-- END OF: info message 3 -->
					</div>
				</div><!--/span-->
			
			</div><!--/row-->
    
<?php require_once('footer.php'); ?>
