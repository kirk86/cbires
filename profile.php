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
						<form class="form-horizontal">
							<fieldset>
                            <legend>Edit your profile settings</legend>
							<div class="control-group">
								<label class="control-label" for="focusedInput">Username</label>
								<div class="controls">
								  <input class="input-xlarge focused" id="focusedInput" type="text" value="Username…" />
								</div>
							</div>
                            <div class="control-group">
								<label class="control-label" for="focusedInput">Password</label>
								<div class="controls">
								  <input class="input-xlarge focused" id="focusedInput" type="text" value="Password…" />
								</div>
							</div>
							<div class="control-group">
							  <label class="control-label" for="date01">Date registered </label>
							  <div class="controls">
								<input type="text" class="input-xlarge datepicker" id="date01" value="26/12/2012" />
							  </div>
							</div>
                            
                            <!--
							  <div class="control-group">
								<label class="control-label">Uneditable input</label>
								<div class="controls">
								  <span class="input-xlarge uneditable-input">Some value here</span>
								</div>
							  </div>
							  <div class="control-group">
								<label class="control-label" for="disabledInput">Disabled input</label>
								<div class="controls">
								  <input class="input-xlarge disabled" id="disabledInput" type="text" placeholder="Disabled input here…" disabled="" />
								</div>
							  </div>
                              -->
                              <!--
							  <div class="control-group warning">
								<label class="control-label" for="inputWarning">Input with warning</label>
								<div class="controls">
								  <input type="text" id="inputWarning">
								  <span class="help-inline">Something may have gone wrong</span>
								</div>
							  </div>
							  <div class="control-group error">
								<label class="control-label" for="inputError">Input with error</label>
								<div class="controls">
								  <input type="text" id="inputError">
								  <span class="help-inline">Please correct the error</span>
								</div>
							  </div>
							  <div class="control-group success">
								<label class="control-label" for="inputSuccess">Input with success</label>
								<div class="controls">
								  <input type="text" id="inputSuccess">
								  <span class="help-inline">Woohoo!</span>
								</div>
							  </div>
                              -->
							  <div class="control-group">
								<label class="control-label">Role</label>
								<div class="controls">
								  <label class="radio">
									<input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="" /> Admin
								  </label>
								  <label class="radio">
									<input type="radio" name="optionsRadios" id="optionsRadios2" value="option2" />	Member
								  </label>
                                  <label class="radio">
									<input type="radio" name="optionsRadios" id="optionsRadios3" value="option3" /> Staff
								  </label>
								</div>
							  </div>
							  <div class="control-group">
								<label class="control-label" for="selectError">Status</label>
								<div class="controls">
								  <select id="selectError" data-rel="chosen">
									<option>Active</option>
									<option>Inactive</option>
									<option>Pending</option>
									<option>Banned</option>
								  </select>
								</div>
							  </div>
							  <div class="form-actions">
								<button type="submit" class="btn btn-primary">Save changes</button>
								<button class="btn">Cancel</button>
							  </div>
							</fieldset>
						  </form>
					
					</div>
				</div><!--/span-->
			
			</div><!--/row-->
    
<?php require_once('footer.php'); ?>
