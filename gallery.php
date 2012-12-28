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
						<p class="center">
							<button id="toggle-fullscreen" class="btn btn-large btn-primary visible-desktop" data-toggle="button">Toggle Fullscreen</button>
						</p>
                    <!-- END OF: fullscreen button -->
						<br />
                    <!-- START OF: info message 1 -->    
                        <div class="alert alert-info">
							<h2><p class="center">Retrieval Results</p></h2>
						</div>
                    <!-- END OF: info message 1 -->
                    <!--
                        <legend class="center"></legend>
                        <div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable datatable">
						  <thead>
							  <tr>
								  <th> </th>
								  <th> </th>
								  <th> </th>
								  <th> </th>
								  <th> </th>
                                  <th> </th>
                                  <th> </th>
							  </tr>
						  </thead>   
						  <tbody>
                          <ul class="thumbnails gallery">
                          -->
                          <?php //for ($i = 0; $i <= 10; $i++) { ?>
						  <!--<tr>-->
                            <?php //for ($j = 1; $j <= 7; $j++) { ?>
								<!--
                                <td class="center">
                                Image<?php //echo $counter2; ?> <hr />
                                Relevance<?php //echo $counter2+$j; ?> <hr />
                                Score<?php //echo $counter2+$j; ?> <hr />
                                </td>
                                -->
                            <?php //} ?>
							<!--</tr>-->
                            <?php //} ?>
                            <!--
                            </ul>
						  </tbody>
					  </table>            
					</div>
                    -->
                    <form class="form-horizontal">
        				<fieldset>
        						<ul class="thumbnails gallery">
        							<?php for ($i = 1; $i <= 24; $i++) : ?>
        							<li id="image-<?php echo $i ?>" class="thumbnail">
        								<a style="background:url(img/gallery/thumbs/<?php echo $i ?>.jpg)" title="Sample Image <?php echo $i ?>" href="img/gallery/<?php echo $i ?>.jpg">
                                            <img class="grayscale" src="img/gallery/thumbs/<?php echo $i ?>.jpg" alt="Sample Image <?php echo $i ?>" />
                                        </a>
        							</li>
        							<?php endfor; ?>
        						</ul>
                            <p style="text-align: center;">
                                    <button type="submit" class="btn btn-primary">more results</button>&nbsp;
                                    <button type="submit" class="btn btn-primary">requery</button>
                                </p>
						  </fieldset>
						</form>
                        
                        <legend class="center"></legend>
                        
                    <!-- START OF: info message 2 -->
                        <div class="alert alert-info">
							<h2><p class="center">Random Images - Click one to start a query</p></h2>
						</div>
                        <ul class="thumbnails gallery">
							<?php for ($i = 1; $i <= 7; $i++) : ?>
							<li id="image-<?php echo $i ?>" class="thumbnail">
								<a style="background:url(img/gallery/thumbs/<?php echo $i ?>.jpg)" title="Sample Image <?php echo $i ?>" href="img/gallery/<?php echo $i ?>.jpg">
                                    <img class="grayscale" src="img/gallery/thumbs/<?php echo $i ?>.jpg" alt="Sample Image <?php echo $i ?>" />
                                </a>
							</li>
							<?php endfor; ?>
						</ul>
                        
                        <div class="box-content">
						<form class="form-horizontal">
						  <fieldset>
							  <div class="controls">
								<p style="text-align: right;"> <button type="submit" class="btn btn-primary">more random images</button></p>
							  </div>
						  </fieldset>
						</form>   

					</div>
                    <!-- END OF: info message 2 -->
                    
                    <legend class="center"></legend>
                    
                    <!-- START OF: info message 3 -->    
                        <div class="alert alert-info">
							<h2><p class="center">Upload - Use query image from your file system</p></h2>
						</div>
                        
                        <div class="box-content">
						<form class="form-horizontal">
						  <fieldset>
							<div class="control-group">
								<p class="center">File input: &nbsp;
                                    <input class="input-file uniform_on" id="fileInput" type="file" /> &nbsp;
                                    <button type="submit" class="btn btn-primary">Query :-)</button>
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
