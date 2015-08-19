<!-- main body -->
<div id="main" class="clearingfix">
	<div id="mainmiddle" class="floatbox withright">

	<?php if ($site_message != ''): ?>
		<div class="green-box">
			<h3><?php echo $site_message; ?></h3>
		</div>
	<?php endif; ?>

		<!-- right column -->
		<div id="right" class="clearingfix">
			
			<?php
			// Action::main_sidebar_pre_filters - Add Items to the Entry Page before filters
			Event::run('ushahidi_action.main_sidebar_pre_filters');
			?>

			<!-- category filters -->
			<div class="cat-filters clearingfix">
				<strong>
					<?php echo Kohana::lang('ui_main.category_filter');?>
					<span>
						[<a href="javascript:toggleLayer('category_switch_link', 'category_switch')" id="category_switch_link">
							<?php echo Kohana::lang('ui_main.hide'); ?>
						</a>]
					</span>
				</strong>
			</div>

			<ul id="category_switch" class="category-filters">
				<?php
				$color_css = 'class="category-icon swatch" style="background-color:#'.$default_map_all.'"';
				$all_cat_image = '';
				if ($default_map_all_icon != NULL)
				{
					$all_cat_image = html::image(array(
						'src'=>$default_map_all_icon
					));
					$color_css = 'class="category-icon"';
				}
				?>
				<li>
					<a class="active" id="cat_0" href="#">
						<span <?php echo $color_css; ?>><?php echo $all_cat_image; ?></span>
						<span class="category-title"><?php echo Kohana::lang('ui_main.all_categories');?></span>
					</a>
				</li>
				<?php
					foreach ($categories as $category => $category_info)
					{
						$category_title = html::escape($category_info[0]);
						$category_color = $category_info[1];
						$category_image = ($category_info[2] != NULL)
						    ? url::convert_uploaded_to_abs($category_info[2])
						    : NULL;
						$category_description = html::escape(Category_Lang_Model::category_description($category));

						$color_css = 'class="category-icon swatch" style="background-color:#'.$category_color.'"';
						if ($category_info[2] != NULL)
						{
							$category_image = html::image(array(
								'src'=>$category_image,
								));
							$color_css = 'class="category-icon"';
						}

						echo '<li>'
						    . '<a href="#" id="cat_'. $category .'" title="'.$category_description.'">'
						    . '<span '.$color_css.'>'.$category_image.'</span>'
						    . '<span class="category-title">'.$category_title.'</span>'
						    . '</a>';

						// Get Children
						echo '<div class="hide" id="child_'. $category .'">';
						if (sizeof($category_info[3]) != 0)
						{
							echo '<ul>';
							foreach ($category_info[3] as $child => $child_info)
							{
								$child_title = html::escape($child_info[0]);
								$child_color = $child_info[1];
								$child_image = ($child_info[2] != NULL)
								    ? url::convert_uploaded_to_abs($child_info[2])
								    : NULL;
								$child_description = html::escape(Category_Lang_Model::category_description($child));
								
								$color_css = 'class="category-icon swatch" style="background-color:#'.$child_color.'"';
								if ($child_info[2] != NULL)
								{
									$child_image = html::image(array(
										'src' => $child_image
									));

									$color_css = 'class="category-icon"';
								}

								echo '<li>'
								    . '<a href="#" id="cat_'. $child .'" title="'.$child_description.'">'
								    . '<span '.$color_css.'>'.$child_image.'</span>'
								    . '<span class="category-title">'.$child_title.'</span>'
								    . '</a>'
								    . '</li>';
							}
							echo '</ul>';
						}
						echo '</div></li>';
					}
				?>
			</ul>
			<!-- / category filters -->

			<?php if ($layers): ?>
				<!-- Layers (KML/KMZ) -->
				<div class="layers-filters clearingfix">
					<strong><?php echo Kohana::lang('ui_main.layers_filter');?> 
						<span>
							[<a href="javascript:toggleLayer('kml_switch_link', 'kml_switch')" id="kml_switch_link">
								<?php echo Kohana::lang('ui_main.hide'); ?>
							</a>]
						</span>
					</strong>
				</div>
				<ul id="kml_switch" class="category-filters">
				<?php
					foreach ($layers as $layer => $layer_info)
					{
						$layer_name = $layer_info[0];
						$layer_color = $layer_info[1];
						$layer_url = $layer_info[2];
						$layer_file = $layer_info[3];

						$layer_link = ( ! $layer_url)
						    ? url::base().Kohana::config('upload.relative_directory').'/'.$layer_file
						    : $layer_url;
						
						echo '<li>'
						    . '<a href="#" id="layer_'. $layer .'">'
						    . '<span class="swatch" style="background-color:#'.$layer_color.'"></span>'
						    . '<span class="layer-name">'.$layer_name.'</span>'
						    . '</a>'
						    . '</li>';
					}
				?>
				</ul>
				<!-- /Layers -->
			<?php endif; ?>

			<?php
			// Action::main_sidebar_post_filters - Add Items to the Entry Page after filters
			Event::run('ushahidi_action.main_sidebar_post_filters');
			?>

			<br />


				<!--div class="additional-content">
					<h5><?php echo Kohana::lang('ui_main.how_to_report'); ?></h5>

					<div class="how-to-report-methods">

<p><b>By sending a text to 0123 456 789</b><br>
Include the place name, how it makes you feel and why, and a picture (optional)</p>
<br>
<p><b><a href="<?php echo url::site()."reports/submit";?>">By filling this form on our website</a></b><br>



					</div>

				</div-->

			<?php
			// Action::main_sidebar - Add Items to the Entry Page Sidebar
			Event::run('ushahidi_action.main_sidebar');
			?>
	
		</div>
		<!-- / right column -->
	
		<!-- content column -->
		<div id="content" class="clearingfix">
			<div class="floatbox">

				<!-- filters -->
				<div class="filters clearingfix">
					<div class="media-filters">
						<strong><?php echo Kohana::lang('ui_main.filters'); ?></strong>
						<ul>
							<li><a id="media_0" class="active" href="#"><span><?php echo Kohana::lang('ui_main.all'); ?></span></a></li>
							<li><a id="media_1" href="#"><span><?php echo Kohana::lang('ui_main.pictures'); ?></span></a></li>
							<li><a id="media_2" href="#"><span><?php echo Kohana::lang('ui_main.video'); ?></span></a></li>
						</ul>
					</div>


					<?php
					// Action::main_filters - Add items to the main_filters
					Event::run('ushahidi_action.map_main_filters');
					?>
				</div>
				<!-- / filters -->

				<?php								
				// Map and Timeline Blocks
				echo $div_map;
				echo $div_timeline;
				?>
			</div>
		</div>
		<!-- / content column -->

	</div>
</div>
<!-- / main body -->

<!-- content -->
<div class="content-container">

	<!-- content blocks -->
	<div class="content-blocks clearingfix">
		<ul class="content-column">
			<?php blocks::render(); ?>

			<li class="howtoreport">
									<h5><?php echo Kohana::lang('ui_main.how_to_report'); ?></h5>

					<div class="how-to-report-methods">

<p><b>By sending a text to +61 (0) 481 070 810</b><br>
Include the place name, how it makes you feel and why, and a picture (optional)</p>
<br>
<p><b><a href="<?php echo url::site()."reports/submit";?>">By filling this form on our website</a></b><br>



					</div>
			</li>
		</ul>
	</div>
	<!-- /content blocks -->

</div>
<!-- content -->
