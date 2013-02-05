<?php require_once('header.php'); ?>

			<div>
				<ul class="breadcrumb">
					<li>
						<a href="index.php">Home</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="profile.php">Profile</a>
					</li>
				</ul>
			</div>
			
			<?php
			if(isset($_GET['id']))
			{
				$sql = "SELECT * FROM public.tbl_user
						WHERE tbl_user.id_user = ".$_GET['id'];
				$result = DB::getAll($sql);
			}
			?>
			
			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-edit"></i> Profile </h2>
						<div class="box-icon">
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<form class="form-horizontal" action="user.php" method="post">
							<fieldset>
								<legend>Edit your profile settings</legend>
								<div class="control-group">
									<label class="control-label" for="username">Username</label>
									<div class="controls">
										<input id="userid" name="userid" type="hidden" value="<?php echo $result[0]['id_user'];?>" />
										<input class="input-xlarge focused" id="username" name="username" type="text" value="<?php echo $result[0]['username'];?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="password">Password</label>
									<div class="controls">
										<input class="input-xlarge focused" id="password" name="password" type="text" value="<?php echo $result[0]['password'];?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="date01">Date registered </label>
									<div class="controls">
										<?php 
										$timestamp = strtotime($result[0]['date_registered']);
										$date = date("d/m/Y", $timestamp);
										?>
										<input type="text" class="input-xlarge datepicker" id="date01" name="date01" value="<?php echo $date;?>" />
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Role</label>
									<div class="controls">
										<label class="radio">
											<input type="radio" name="optionsRadios" id="optionsRadios1" value="1" <?php echo $result[0]['id_user_role']==1 ? 'checked="checked"' : '';?> /> Admin
										</label>
										<label class="radio">
											<input type="radio" name="optionsRadios" id="optionsRadios2" value="2" <?php echo $result[0]['id_user_role']==2 ? 'checked="checked"' : '';?> />	Member
										</label>
										<label class="radio">
											<input type="radio" name="optionsRadios" id="optionsRadios3" value="3" <?php echo $result[0]['id_user_role']==3 ? 'checked="checked"' : '';?> /> Staff
										</label>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="selectStaus">Status</label>
									<div class="controls">
										<select id="selectStaus" name="selectStaus" data-rel="chosen">
											<option value="active" <?php echo $result[0]['status']=='active' ? 'selected="selected"' : '';?>>Active</option>
											<option value="inactive" <?php echo $result[0]['status']=='inactive' ? 'selected="selected"' : '';?>>Inactive</option>
											<option value="pending" <?php echo $result[0]['status']=='pending' ? 'selected="selected"' : '';?>>Pending</option>
											<option value="banned" <?php echo $result[0]['status']=='banned' ? 'selected="selected"' : '';?>>Banned</option>
										</select>
									</div>
								</div>
								<div class="form-actions">
									<button type="submit" id="savebtn" name="savebtn" class="btn btn-primary">Save changes</button>
									<button class="btn">Cancel</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div><!--/span-->
			</div><!--/row-->

<?php require_once('footer.php'); ?>
