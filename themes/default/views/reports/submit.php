<div id="content">
	<div class="content-bg">

		<?php if ($site_submit_report_message != ''): ?>
			<div class="green-box">
				<h3><?php echo $site_submit_report_message; ?></h3>
			</div>
		<?php endif; ?>

		<!-- start report form block -->
		<?php print form::open(NULL, array('enctype' => 'multipart/form-data', 'id' => 'reportForm', 'name' => 'reportForm', 'class' => 'gen_forms')); ?>
		<input type="hidden" name="latitude" id="latitude" value="<?php echo $form['latitude']; ?>">
		<input type="hidden" name="longitude" id="longitude" value="<?php echo $form['longitude']; ?>">
		<input type="hidden" name="country_name" id="country_name" value="<?php echo $form['country_name']; ?>" />
		<input type="hidden" name="incident_zoom" id="incident_zoom" value="<?php echo $form['incident_zoom']; ?>" />
		<div class="big-block">
			<h1 style="width: 100%;text-align:center;"><img src="<?php echo url::site();?>/themes/default/images/form-header.png" width="600" style="margin-top: 30px;margin-bottom: 30px" /></h1>
			<?php if ($form_error): ?>
			<!-- red-box -->
			<div class="red-box">
				<h3>Error!</h3>
				<ul>
					<?php
						foreach ($errors as $error_item => $error_description)
						{
							print (!$error_description) ? '' : "<li>" . $error_description . "</li>";
						}
					?>
				</ul>
			</div>
			<?php endif; ?>
			<div class="row">
				<input type="hidden" name="form_id" id="form_id" value="<?php echo $id?>">
			</div>
			<div class="report_left">
<?php print form::input('incident_title', $form['incident_title'], ' class="text long hidden" style="display:none"'); ?>
			<?php print form::textarea('incident_description', $form['incident_description'], ' rows="10" class="textarea long"  style="display:none"') ?>
				<?php Event::run('ushahidi_action.report_form_location', $id); ?>
				<div class="report_row">
					<h4>
						What is the name of the location you want to report on? (click and drag the red icon on the map to choose the location on the map. If you are using a touch screen device you will need to type in the location in the field below)

					</h4>
					<?php print form::input('location_name', $form['location_name'], ' class="text long"'); ?>
				</div>

				<div class="report_row">
					<?php if(count($forms) > 1): ?>
					<div class="row">
						<h4><span><?php echo Kohana::lang('ui_main.select_form_type');?></span>
						<span class="sel-holder">
							<?php print form::dropdown('form_id', $forms, $form['form_id'],
						' onchange="formSwitch(this.options[this.selectedIndex].value, \''.$id.'\')"') ?>
						</span>
						<div id="form_loader"></div>
						</h4>
					</div>
					<?php endif; ?>

					<h4>How does this place make you feel? <span class="max-check-rule">(choose up to 3 emotions)</span><span class="required">*</span></h4>
					<div class="report_category" id="categories">
					<?php
						$selected_categories = (!empty($form['incident_category']) AND is_array($form['incident_category']))
							? $selected_categories = $form['incident_category']
							: array();

						echo category::form_tree('incident_category', $selected_categories, 2);
						?>
						<script>
							// Enforce max 3 category rule.
							$('.category-column').find('.check-box').click(function(e){
								if($('.category-column').find('input.check-box:checked').length > 3) {
									// We hit the max so don't check the one that was clicked
									$(this).prop("checked",false);
									// Gentle reminder that only three are allowed
									$('.max-check-rule').fadeTo('fast', 0.25).fadeTo('fast', 1);
								}
							});
						</script>
					</div>
				</div>

				<div class="report_row"  style="display:none">
					<h4><?php echo Kohana::lang('ui_main.reports_description'); ?> <span class="required">*</span> </h4>
					<span class="allowed-html"><?php echo html::allowed_html(); ?></span>
					<?php print form::textarea('incident_description', $form['incident_description'], ' rows="10" class="textarea long" ') ?>
				</div>
				<div class="report_row" id="datetime_default"  style="display:none">
					<h4>
						<a href="#" id="date_toggle" class="show-more"><?php echo Kohana::lang('ui_main.modify_date'); ?></a>
						<?php echo Kohana::lang('ui_main.date_time'); ?>:
						<?php echo Kohana::lang('ui_main.today_at')." "."<span id='current_time'>".$form['incident_hour']
							.":".$form['incident_minute']." ".$form['incident_ampm']."</span>"; ?>
						<?php if($site_timezone): ?>
							<small>(<?php echo $site_timezone; ?>)</small>
						<?php endif; ?>
					</h4>
				</div>
				<div class="report_row hide" id="datetime_edit">
					<div class="date-box">
						<h4><?php echo Kohana::lang('ui_main.reports_date'); ?></h4>
						<?php print form::input('incident_date', $form['incident_date'], ' class="text short"'); ?>
						<script type="text/javascript">
							$().ready(function() {
								$("#incident_date").datepicker({
									showOn: "both",
									buttonImage: "<?php echo url::file_loc('img'); ?>media/img/icon-calendar.gif",
									buttonImageOnly: true
								});
							});
						</script>
					</div>
					<div class="time">
						<h4><?php echo Kohana::lang('ui_main.reports_time'); ?></h4>
						<?php
							for ($i=1; $i <= 12 ; $i++)
							{
								// Add Leading Zero
								$hour_array[sprintf("%02d", $i)] = sprintf("%02d", $i);
							}
							for ($j=0; $j <= 59 ; $j++)
							{
								// Add Leading Zero
								$minute_array[sprintf("%02d", $j)] = sprintf("%02d", $j);
							}
							$ampm_array = array('pm'=>'pm','am'=>'am');
							print form::dropdown('incident_hour',$hour_array,$form['incident_hour']);
							print '<span class="dots">:</span>';
							print form::dropdown('incident_minute',$minute_array,$form['incident_minute']);
							print '<span class="dots">:</span>';
							print form::dropdown('incident_ampm',$ampm_array,$form['incident_ampm']);
						?>
						<?php if ($site_timezone != NULL): ?>
							<small>(<?php echo $site_timezone; ?>)</small>
						<?php endif; ?>
					</div>
					<div style="clear:both; display:block;" id="incident_date_time"></div>
				</div>
				<div class="report_row">
					<!-- Adding event for endtime plugin to hook into -->
				<?php Event::run('ushahidi_action.report_form_frontend_after_time'); ?>
				</div>


				<?php
				// Action::report_form - Runs right after the report categories
				Event::run('ushahidi_action.report_form');
				?>

				<?php echo $custom_forms ?>

					<div class="report_row">
						<h4>What is your full name (this will not be made visible to anyone)?</h4>
						<?php print form::input('person_first', $form['person_first'], ' class="text long"'); ?>
					</div>


				<div class="report_row">

					<h4>What do you want to call this report? <span class="required">*</span> </h4>
					<?php print form::input('incident_title', $form['incident_title'], ' class="text long"'); ?>
				</div>

			</div>
			<div class="report_right">

				<?php if (count($cities) > 1): ?>
				<div class="report_row">
					<h4><?php echo Kohana::lang('ui_main.reports_find_location'); ?></h4>
					<?php print form::dropdown('select_city',$cities,'', ' class="select" '); ?>
				</div>
				<?php endif; ?>
				<div class="report_row">

					<div id="divMap" class="report_map">
						<div id="geometryLabelerHolder" class="olControlNoSelect">
							<div id="geometryLabeler">
								<div id="geometryLabelComment">
									<span id="geometryLabel">
										<label><?php echo Kohana::lang('ui_main.geometry_label');?>:</label>
										<?php print form::input('geometry_label', '', ' class="lbl_text"'); ?>
									</span>
									<span id="geometryComment">
										<label><?php echo Kohana::lang('ui_main.geometry_comments');?>:</label>
										<?php print form::input('geometry_comment', '', ' class="lbl_text2"'); ?>
									</span>
								</div>
								<div>
									<span id="geometryColor">
										<label><?php echo Kohana::lang('ui_main.geometry_color');?>:</label>
										<?php print form::input('geometry_color', '', ' class="lbl_text"'); ?>
									</span>
									<span id="geometryStrokewidth">
										<label><?php echo Kohana::lang('ui_main.geometry_strokewidth');?>:</label>
										<?php print form::dropdown('geometry_strokewidth', $stroke_width_array, ''); ?>
									</span>
									<span id="geometryLat">
										<label><?php echo Kohana::lang('ui_main.latitude');?>:</label>
										<?php print form::input('geometry_lat', '', ' class="lbl_text"'); ?>
									</span>
									<span id="geometryLon">
										<label><?php echo Kohana::lang('ui_main.longitude');?>:</label>
										<?php print form::input('geometry_lon', '', ' class="lbl_text"'); ?>
									</span>
								</div>
							</div>
							<div id="geometryLabelerClose"></div>
						</div>
					</div>
					<div class="report-find-location">
						<?php print form::input('location_find', '', ' title="'.Kohana::lang('ui_main.location_example').'" class="findtext"'); ?>
						<input type="button" name="button" id="button" value="<?php echo Kohana::lang('ui_main.find_location'); ?>" class="btn_find" />
						<div id="find_loading" class="report-find-loading"></div>
						<div style="clear:both;" id="find_text"><?php echo Kohana::lang('ui_main.pinpoint_location'); ?>.</div>
					</div>
				</div>



								<!-- Photo Fields -->
				<div id="divPhoto" class="report_row">
					<h4>If you’d like, you can now upload photos of this location or images that represent how you feel about it. You can also link to a video or audio file you have uploaded to the internet.</h4>
					<span class="allowed-html"><?php echo Kohana::lang('ui_main.maximum_filesize'),": ", Kohana::config('settings.max_upload_size'), "Mb"; ?></span>
					<?php
						// Initialize the counter
						$i = (empty($form['incident_photo']['name'][0])) ? 1 : 0;
					?>

					<?php if (empty($form['incident_photo']['name'][0])): ?>
					<div class="report_row">
						<?php print form::upload('incident_photo[]', '', ' class="file long2"'); ?>
						<a href="#" class="add" onClick="addFormField('divPhoto', 'incident_photo','photo_id','file'); return false;">add</a>
					</div>
					<?php else: ?>
						<?php foreach ($form['incident_photo']['name'] as $value): ?>

							<div class="report_row" id="<?php echo $i; ?>">
								<?php print form::upload('incident_photo[]', $value, ' class="file long2"'); ?>
								<a href="#" class="add" onClick="addFormField('divPhoto','incident_photo','photo_id','file'); return false;">add</a>

								<?php if ($i != 0): ?>
									<?php $css_id = "#incident_photo_".$i; ?>
									<a href="#" class="rem"	onClick="removeFormField('<?php echo $css_id; ?>'); return false;">remove</a>
								<?php endif; ?>

							</div>

							<?php $i++; ?>

						<?php endforeach; ?>
					<?php endif; ?>

					<?php print form::input(array('name'=>'photo_id','type'=>'hidden','id'=>'photo_id'), $i); ?>
				</div>


				<!-- Video Fields -->
				<div id="divVideo" class="report_row">
					<?php
						// Initialize the counter
						$i = (empty($form['incident_video'])) ? 1 : 0;
					?>

					<?php if (empty($form['incident_video'])): ?>
						<div class="report_row">
							<?php print form::input('incident_video[]', '', ' class="text long2"'); ?>
							<a href="#" class="add" onClick="addFormField('divVideo','incident_video','video_id','text'); return false;">add</a>
						</div>
					<?php else: ?>
						<?php foreach ($form['incident_video'] as $value): ?>
							<div class="report_row" id="<?php  echo $i; ?>">

							<?php print form::input('incident_video[]', $value, ' class="text long2"'); ?>
							<a href="#" class="add" onClick="addFormField('divVideo','incident_video','video_id','text'); return false;">add</a>

							<?php if ($i != 0): ?>
								<?php $css_id = "#incident_video_".$i; ?>
								<a href="#" class="rem"	onClick="removeFormField('<?php echo $css_id; ?>'); return false;">remove</a>
							<?php endif; ?>

							</div>
							<?php $i++; ?>
						<?php endforeach; ?>
					<?php endif; ?>

					<?php print form::input(array('name'=>'video_id','type'=>'hidden','id'=>'video_id'), $i); ?>
				</div>

				<?php Event::run('ushahidi_action.report_form_after_video_link'); ?>



			</div>

				<div class="report_row">
					<input name="submit" type="submit" value="<?php echo Kohana::lang('ui_main.reports_btn_submit'); ?>" class="btn_submit" />
				</div>


		</div>
		<?php print form::close(); ?>
		<!-- end report form block -->
	</div>
</div>
