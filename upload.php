<?php require_once('header.php'); ?>

		
			<div>
				<ul class="breadcrumb">
					<li>
						<a href="index.php">Home</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="upload.php">File Uploader</a>
					</li>
				</ul>
			</div>
			
			<div class="row-fluid sortable">	
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-plus"></i> File Uploader </h2>
						<div class="box-icon">
							<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<table class="table table-bordered table-striped">
							<tr>
								<td><h3>Multiple File Upload</h3></td>
								<td>
									<input data-no-uniform="true" type="file" name="file_upload" id="file_upload" />
								</td>
							</tr>
                            <!--
							<tr>
								<td><h3>Choose Folder - Upload all at once</h3></td>
								<td>
									<input data-no-uniform="true" type="file" name="file_upload2" id="file_upload2" />
								</td>
							</tr>
                            -->
						</table>
					</div>	
				</div><!--/span-->
				
			</div><!--/row-->
			
		
<?php require_once('footer.php'); ?>
