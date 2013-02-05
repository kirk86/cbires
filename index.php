<?php require_once('header.php'); ?>

			<div>
				<ul class="breadcrumb">
					<li>
						<a href="index.php">Home</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="index.php">Dashboard</a>
					</li>
				</ul>
			</div>
			<?php
				// member
				$sql_total_members = "SELECT count(*) AS count_member
                                      FROM public.tbl_user, public.tbl_user_role
                                      WHERE tbl_user_role.id_user_role = tbl_user.id_user_role";
				$total_members = DB::getOne($sql_total_members);
				$member_info = DB::getAll($sql_total_members); 
				$sql_new_members = "SELECT count(*) AS new_member
                                    FROM public.tbl_user, public.tbl_user_role
                                    WHERE tbl_user_role.id_user_role = tbl_user.id_user_role
                                    AND date_registered > now()::date - 1";
				$new_members = DB::getOne($sql_new_members);
				
				// categories
				$sql_total_categories = "SELECT count(*) AS count_categories
										FROM public.tbl_categories";
				$total_categories = DB::getOne($sql_total_categories);
                $categories_info = DB::getAll($sql_total_categories); 
                $sql_new_categories = "SELECT count(*) AS new_categories
										FROM public.tbl_categories
										WHERE date_created > now()::date - 1";
                $new_categories = DB::getOne($sql_new_categories);
				
				// images
				$sql_total_images = "SELECT count(*) AS count_image
										FROM public.tbl_image";
				$total_images = DB::getOne($sql_total_images);
                $image_info = DB::getAll($sql_total_images); 
                $sql_new_images = "SELECT count(*) AS new_image
										FROM public.tbl_image
										WHERE timestamp::date > now()::date - 1";
                $new_images = DB::getOne($sql_new_images);
				
				// logs				
				// get contents of a file into a string
				$filename = "logs/error_logs.txt";
				$handle = fopen($filename, "r");
				$contents = fread($handle, filesize($filename));
				fclose($handle);
			?>
			<div class="sortable row-fluid">
				<a data-rel="tooltip" title="<?php echo $new_members[0]['new_member']; ?> new members." class="well span3 top-block" href="#">
					<span class="icon32 icon-red icon-user"></span>
					<div>Total Members</div>
					<div><?php echo $total_members[0]['count_member']; ?></div>
					<span class="notification"><?php echo $new_members[0]['new_member']; ?></span>
				</a>
				<a data-rel="tooltip" title="<?php echo $new_categories[0]; ?> new image categories." class="well span3 top-block" href="#">
					<span class="icon32 icon-color icon-star-on"></span>
					<div>Image Categories</div>
					<div><?php echo $total_categories[0];?></div>
					<span class="notification green"><?php echo $new_categories[0]; ?></span>
				</a>
				<a data-rel="tooltip" title="<?php echo $new_images[0]; ?> new images." class="well span3 top-block" href="#">
					<span class="icon32 icon-color icon-image"></span>
					<div>Total Images</div>
					<div><?php echo $total_images[0]; ?></div>
					<span class="notification yellow"><?php echo $new_images[0]; ?></span>
				</a>
				
				<a data-rel="tooltip" title="12 new logs." class="well span3 top-block" href="#">
					<span class="icon32 icon-color icon-book"></span>
					<div>Logs</div>
					<div><?php echo substr_count($contents, 'ERRNO');?></div>
					<!--
					<span class="notification red">12</span>
					-->
				</a>
			</div>
			
			<div class="row-fluid">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-info-sign"></i> Introduction</h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<h1>CBIRES <small>free, open-source, online, content based image retrieval system.</small></h1>
						<p>It's a live demo of the CBIRES, which was created due to an assignement during our graduate degree in CS. :)</p>
						<p><b>At the moment you cannot delete images from the databse, you can only insert new ones !</b></p>
						<p class="center">
							<a href="https://github.com/kirk86/cbires" class="btn btn-large"><i class="icon-download-alt"></i> Download Page</a>
						</p>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
					
			<div class="row-fluid sortable">
            	<div class="box span4">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i> Member Activity</h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
                    <?php $sql_member_info = "SELECT *
                                             FROM public.tbl_user, public.tbl_user_role
                                             WHERE tbl_user_role.id_user_role = tbl_user.id_user_role
                                             ORDER BY date_registered DESC
                                             LIMIT 4";
                          $member_info = DB::getAll($sql_member_info);
                    ?>
					<div class="box-content">
						<div class="box-content">
							<ul class="dashboard-list">
                            <?php foreach ($member_info as $key => $value) : ?>
								<li>
									<a href="#">
										<img class="dashboard-avatar" alt="<?php echo $member_info[$key]['username']; ?>" src="http://www.gravatar.com/avatar/<?php echo md5( strtolower( trim( "kirk86@walla.com" ) ) ); ?>.png?s=50" /></a>
										<strong>Name:</strong> <a href="#"><?php echo ucfirst($member_info[$key]['username']); ?>
									</a><br />
									<strong>Since:</strong> <?php echo $member_info[$key]['date_registered']; ?> <br />
									<strong>Status:</strong> <span class="<?php if ($member_info[$key]['status'] == 'active') echo 'label label-success';
                                                       elseif ($member_info[$key]['status'] == 'pending') echo 'label label-warning';
                                                       elseif ($member_info[$key]['status'] == 'banned') echo 'label label-important';
                                                       elseif ($member_info[$key]['status'] == 'inactive') echo 'label'; ?>">
                                                       <?php if ($member_info[$key]['status'] == 'active') echo 'Active';
                                                       elseif ($member_info[$key]['status'] == 'pending') echo 'Pending';
                                                       elseif ($member_info[$key]['status'] == 'banned') echo 'Banned';
                                                       elseif ($member_info[$key]['status'] == 'inactive') echo 'Inactive'; ?>
                                                            </span>                                  
								</li>
                            <?php endforeach; ?>
                            
							</ul>
						</div>
					</div>
				</div><!--/span-->
                
				<!-- YOU CAN ADD MORE GRIDS HERE BELOW -->
                
                <!-- START OF Image Categories -->
                <div class="box span4">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-star"></i> Image Categories </h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<?php $sql_category_info = "SELECT *
                                             FROM public.tbl_categories
                                             ORDER BY RANDOM()
                                             LIMIT 4";
                          $category_info = DB::getAll($sql_category_info);
                    ?>
					<div class="box-content">
						<div class="box-content">
							<ul class="dashboard-list">
								<?php foreach ($category_info as $key => $value) : ?>
								<li>
									<a href="#">
										<img class="dashboard-avatar" alt="<?php echo ucfirst($category_info[$key]['category_name']); ?>" src="img/categories/<?php echo ucfirst($category_info[$key]['category_image']);?>" /></a>
										<strong>Name:</strong> <a href="#"><?php echo ucfirst($category_info[$key]['category_name']); ?>
									</a><br />
									<strong>Created:</strong> <?php echo $category_info[$key]['date_created']; ?> <br />                                  
									<div style="clear:both;"></div>
								</li>
                            <?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div><!--/span-->
				<!-- END OF Image Categories -->
			  
				<!-- START OF Donut chart -->    
				<div class="box span4">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-list-alt"></i> Statistics Categories</h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						 <div id="donutchart" style="height: 300px;"></div>
					</div>
				</div>
				<!-- END OF Donut chart -->
			</div><!--/row-->
<?php require_once('footer.php'); ?>
