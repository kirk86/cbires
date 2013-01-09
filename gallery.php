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
						<!--<br />-->
                    <?php                    
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
                                //copy($tempFile, $galleryPath);
                                $img->resize(300, 0); // Lower quality image created using width ratio otherwise $img->resize(300, 0, false); for quality not speed
                                $img->save($targetPath);
                                $resized_img = $img->file_name;
                                
                                //$filemime = 'image/'.$fileParts['extension'];
                                if ( isset($_SESSION['colorSpace'])       && !empty($_SESSION['colorSpace'])       &&
                                     isset($_SESSION['distanceFunction']) && !empty($_SESSION['distanceFunction']) &&
                                     isset($_SESSION['threshold'])        && !empty($_SESSION['threshold'])
                                   )
                                {
                                    switch ($_SESSION['colorSpace'])
                                    {
                                        case 'RGB':
                                        
                                        $objRGB      = new Histogram($resized_img);
                                        $histoRGB    = $objRGB->generateHistogram();
                                        $normHistRGB = DistanceMetrics::computeHistogram($histoRGB, 64, min($histoRGB), max($histoRGB));
                                        $meanRGB     = DistanceMetrics::mean($normHistRGB);
                                        $stdRGB      = DistanceMetrics::std($normHistRGB);
                                        $sql         = "SELECT id_image, filename, filepath, filemime, filename_hash,
                                                       array_to_json(color_histogram) AS rgb_histogram 
                                                       FROM tbl_image";
                                        $qresult = DB::getAll($sql);
                                        $distArrayRGB = array();
                                        $img_ids      = array();
                                        $img_filename = array();
                                        foreach ($qresult as $key => $value)
                                        {
                                            if ($_SESSION['distanceFunction'] == 'L1')
                                                $distRGB = DistanceMetrics::manhattan($normHistRGB, json_decode($qresult[$key]['rgb_histogram']));
                                            if ($_SESSION['distanceFunction'] == 'L2')
                                                $distRGB = DistanceMetrics::euclidean($normHistRGB, json_decode($qresult[$key]['rgb_histogram']));
                                            if ($_SESSION['distanceFunction'] == 'JDV')
                                                $distRGB = DistanceMetrics::jeffrey($normHistRGB, json_decode($qresult[$key]['rgb_histogram']));
                                            if ($_SESSION['distanceFunction'] == 'ChiSquare')
                                                $distRGB = DistanceMetrics::chiSquare($normHistRGB, json_decode($qresult[$key]['rgb_histogram']));
                                            if ($_SESSION['distanceFunction'] == 'Chebychev')
                                                $distRGB = DistanceMetrics::chebychev($normHistRGB, json_decode($qresult[$key]['rgb_histogram']));
                                            if ($_SESSION['distanceFunction'] == 'Tanimoto')
                                                $distRGB = DistanceMetrics::tanimoto($normHistRGB, json_decode($qresult[$key]['rgb_histogram']));
                                            if ($_SESSION['distanceFunction'] == 'Cosine')
                                                $distRGB = DistanceMetrics::cosineCoefficient($normHistRGB, json_decode($qresult[$key]['rgb_histogram']));
                                            
                                            $distArrayRGB[] = $distRGB;
                                            $img_ids[]      = $qresult[$key]['id_image'];
                                            $img_filename[] = $qresult[$key]['filename_hash'];
                                        }
                                        $combRGB = array_combine($distArrayRGB, $img_filename);
                                        $ok = ksort($combRGB);
                                        unset($objRGB); unset($histoRGB); unset($normHistRGB);
                                        unset($qresult); unset($distArrayRGB); unset($img_ids);
                                        unset($img_filename);
                                        break;
                                        
                                        case 'HSV':
                                        
                                        $objHSV      = new Histogram($resized_img);
                                        $histoHSV    = $objHSV->generateHistogram(true);
                                        $binHistHSV  = DistanceMetrics::computeHistogram($histoHSV, 64, min($histoHSV), max($histoHSV), false);
                                        $normHistHSV = DistanceMetrics::normalize($binHistHSV);
                                        $meanHSV     = DistanceMetrics::mean($normHistHSV);
                                        $stdHSV      = DistanceMetrics::std($normHistHSV);
                                        $sql         = "SELECT id_image, filename, filepath, filemime, filename_hash,
                                                       array_to_json(hsv_histogram) AS hsv_histogram 
                                                       FROM tbl_image";
                                        $qresult = DB::getAll($sql);
                                        $distArrayHSV = array();
                                        $img_ids      = array();
                                        $img_filename = array();
                                        foreach ($qresult as $key => $value)
                                        {
                                            if ($_SESSION['distanceFunction'] == 'L1')
                                                $distHSV = DistanceMetrics::manhattan($normHistHSV, json_decode($qresult[$key]['hsv_histogram']));
                                            if ($_SESSION['distanceFunction'] == 'L2')
                                                $distHSV = DistanceMetrics::euclidean($normHistHSV, json_decode($qresult[$key]['hsv_histogram']));
                                            if ($_SESSION['distanceFunction'] == 'JDV')
                                                $distHSV = DistanceMetrics::jeffrey($normHistHSV, json_decode($qresult[$key]['hsv_histogram']));
                                            if ($_SESSION['distanceFunction'] == 'ChiSquare')
                                                $distHSV = DistanceMetrics::chiSquare($normHistHSV, json_decode($qresult[$key]['hsv_histogram']));
                                            if ($_SESSION['distanceFunction'] == 'Chebychev')
                                                $distHSV = DistanceMetrics::chebychev($normHistHSV, json_decode($qresult[$key]['hsv_histogram']));
                                            if ($_SESSION['distanceFunction'] == 'Tanimoto')
                                                $distHSV = DistanceMetrics::tanimoto($normHistHSV, json_decode($qresult[$key]['hsv_histogram']));
                                            if ($_SESSION['distanceFunction'] == 'Cosine')
                                                $distHSV = DistanceMetrics::cosineCoefficient($normHistHSV, json_decode($qresult[$key]['hsv_histogram']));
                                            
                                            $distArrayHSV[] = $distHSV;
                                            $img_ids[]      = $qresult[$key]['id_image'];
                                            $img_filename[] = $qresult[$key]['filename_hash'];
                                        }
                                        $combHSV = array_combine($distArrayHSV, $img_filename);
                                        $ok = ksort($combHSV);
                                        unset($objHSV); unset($histoHSV); unset($binHistHSV);
                                        unset($normHistHSV); unset($qresult); unset($distArrayHSV);
                                        unset($img_ids); unset($img_filename);
                                        break;
                                    }
                                }
                                else
                                {
                                    $objRGB      = new Histogram($resized_img);
                                    $histoRGB    = $objRGB->generateHistogram();
                                    $normHistRGB = DistanceMetrics::computeHistogram($histoRGB, 64, min($histoRGB), max($histoRGB));
                                    $meanRGB     = DistanceMetrics::mean($normHistRGB);
                                    $stdRGB      = DistanceMetrics::std($normHistRGB);
                                    $sql         = "SELECT id_image, filename, filepath, filemime, filename_hash,
                                                    array_to_json(color_histogram) AS rgb_histogram 
                                                    FROM tbl_image";
                                    $qresult = DB::getAll($sql);
                                    $distArrayRGB = array();
                                    $img_ids      = array();
                                    $img_filename = array();
                                    foreach ($qresult as $key => $value)
                                    {
                                        $distRGB = DistanceMetrics::euclidean($normHistRGB, json_decode($qresult[$key]['rgb_histogram']));
                                        $distArrayRGB[] = $distRGB;
                                        $img_ids[]      = $qresult[$key]['id_image'];
                                        $img_filename[] = $qresult[$key]['filename_hash'];
                                    }
                                    
                                    $combRGB = array_combine($distArrayRGB, $img_filename);
                                    $ok = ksort($combRGB);
                                    unset($objRGB); unset($histoRGB); unset($normHistRGB);
                                    unset($qresult); unset($distArrayRGB); unset($img_ids);
                                    unset($img_filename);
                                }
                                
                                unset($img);
                            
                    ?>
                    <!-- START OF: fullscreen button -->
						<p class="center">
							<button id="toggle-fullscreen" class="btn btn-large btn-primary visible-desktop" data-toggle="button">Toggle Fullscreen</button>
						</p>
                    <!-- END OF: fullscreen button -->
						<!--<br />-->
                    <!-- START OF: info message 1 -->    
                        <div class="alert alert-info">
							<h2><p class="center">Retrieval Results</p></h2>
						</div>
                    <!-- END OF: info message 1 -->
                    
                    <form class="form-horizontal">
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
        							<?php
                                    if ( isset($_SESSION['threshold']) && !empty($_SESSION['threshold']) && 
                                         isset($_SESSION['colorSpace']) && !empty($_SESSION['colorSpace'])
                                       )
                                    {
                                            switch ($_SESSION['colorSpace'])
                                            {
                                                case 'RGB':
                                                
                                                $counter = 0;
                                                foreach ($combRGB as $key => $value)
                                                {
                                                    if ($counter < $_SESSION['threshold'])
                                                    {
                                      ?>
                    							<li id="image-<?php echo $counter + 1; ?>" class="thumbnail">
                    								<a style="background:url(img/gallery/thumbs/<?php echo $value; ?>)" title="Sample Image <?php echo $value; ?>" href="img/gallery/<?php echo $value; ?>">
                                                        <img class="grayscale" src="img/gallery/thumbs/<?php echo $value; ?>" alt="Sample Image <?php echo $value; ?>" />
                                                    </a>
                    							</li>
        							  <?php
                                                    }
                                                    $counter++;
                                                 }
                                                 break;
                                                 
                                                 case 'HSV':
                                                 
                                                 $counter = 0;
                                                 foreach ($combHSV as $key => $value)
                                                 {
                                                    if ($counter < $_SESSION['threshold'])
                                                    {
                                      ?>
                    							<li id="image-<?php echo $counter + 1; ?>" class="thumbnail">
                    								<a style="background:url(img/gallery/thumbs/<?php echo $value; ?>)" title="Sample Image <?php echo $value; ?>" href="img/gallery/<?php echo $value; ?>">
                                                        <img class="grayscale" src="img/gallery/thumbs/<?php echo $value; ?>" alt="Sample Image <?php echo $value; ?>" />
                                                    </a>
                    							</li>
        							  <?php
                                                    }
                                                    $counter++;
                                                 }
                                                 break;
                                            }
                                            //session_unset();
                                            //session_destroy();
                                     }
                                     else
                                     {
                                        $threshold = 14;
                                        $counter = 0;
                                        foreach ($combRGB as $key => $value)
                                        {
                                            if ($counter < $threshold)
                                            {
                                      ?>
                    						<li id="image-<?php echo $counter + 1; ?>" class="thumbnail">
                    							<a style="background:url(img/gallery/thumbs/<?php echo $value; ?>)" title="Sample Image <?php echo $value; ?>" href="img/gallery/<?php echo $value; ?>">
                                                     <img class="grayscale" src="img/gallery/thumbs/<?php echo $value; ?>" alt="Sample Image <?php echo $value; ?>" />
                                                 </a>
                    						</li>
        							  <?php
                                             }
                                            $counter++;
                                        }
                                     }
                                        ?>
        						</ul>
                            <p style="text-align: center;">
                                    <button type="submit" class="btn btn-warning btn-round">more results</button>&nbsp;
                                    <button type="submit" class="btn btn-success btn-round">requery</button>
                                </p>
						  </fieldset>
						</form>
                        <?php
                           }
                        	else
                            {
                                echo MESSAGE_INVALID_FILE_TYPE;
                            }
                        }
                 }
                 //session_unset();
                 ?> 
                    
                    <legend class="center"></legend>
                    
                    <!-- START OF: info message 3 -->    
                        <div class="alert alert-info">
							<h2><p class="center">Upload - Use query image from your file system</p></h2>
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
