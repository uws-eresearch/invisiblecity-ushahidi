<?php blocks::open("reports");?>
<?php blocks::title("Recent Reports Submitted");?>
<table class="table-list">
	<thead>
		<tr>
			<th scope="col" class="title"><?php echo Kohana::lang('ui_main.title'); ?></th>
			<th scope="col" class="title">How does this place make you feel?</th>
			<th scope="col" class="title">Why do you feel this way?</th>
			<th scope="col" class="title">Would you like to change this place? If so, how and why?</th>
			<th scope="col" class="title">Image</th>
			<th scope="col" class="date"><?php echo Kohana::lang('ui_main.date'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		if ($incidents->count() == 0)
		{
			?>
			<tr><td colspan="6"><?php echo Kohana::lang('ui_main.no_reports'); ?></td></tr>
			<?php
		}
		foreach ($incidents as $incident)
		{
			$incident_id = $incident->id;
			$incident_title = text::limit_chars(html::strip_tags($incident->incident_title), 25, '...', True);
			$incident_date = $incident->incident_date;
			$incident_date = date('M j Y', strtotime($incident->incident_date));
			$incident_location = $incident->location->location_name;

			// How the person feels is a concat of categories on the report
			$how_feel = '';
			foreach ($incident->category AS $category)
			{
				$how_feel .= $category->category_title.', ';
			}
			$how_feel = trim($how_feel,', ');

			// Why they feel this way and how they would change it are custom form fields
			$why_feel = '';
			$change_place = '';
			$custom_data = customforms::get_custom_form_fields($incident_id);
			foreach($custom_data AS $custom) {
				switch ($custom['field_id']) {
					case '1':
						$why_feel = text::limit_chars(html::strip_tags($custom['field_response']), 100, '...', True);
					case '2':
						$change_place = text::limit_chars(html::strip_tags($custom['field_response']), 100, '...', True);
				}
			}

			$incident_image = false;
			foreach ($incident->media as $media)
			{
				if ($media->media_type == 1)
				{
					$incident_image = url::convert_uploaded_to_abs($media->media_thumb);
				}
			}
		?>
		<tr>
			<td><a href="<?php echo url::site() . 'reports/view/' . $incident_id; ?>"> <?php echo $incident_title ?></a></td>
			<td><?php echo html::escape($how_feel) ?></td>
			<td><?php echo html::escape($why_feel) ?></td>
			<td><?php echo html::escape($change_place) ?></td>
			<td><?php if(!$incident_image) { ?>No Image.<?php }else{ ?><img src="<?=$incident_image?>" style="max-width:100px;"><?php } ?></td>
			<td><?php echo $incident_date; ?></td>
		</tr>
		<?php
		}
		?>
	</tbody>
</table>
<a class="more" href="<?php echo url::site() . 'reports/' ?>"><?php echo Kohana::lang('ui_main.view_more'); ?></a>
<div style="clear:both;"></div>
<?php blocks::close();?>