@if ($permissions->get('edit'))
<div>
{{ trans('contentcontroller.blog') }} {!! link_to('editor/gallery_editor/' . $dbrow->page_id . '/' .  $contentdata->id ,trans('contentcontroller.edit')) !!} {!! link_to('editor/gallery_editor/images/' . $dbrow->page_id . '/' .  $contentdata->id, trans('contentcontroller.edit_blog_entries')) !!}
</div>
@endif
<div>
	@if ($contentdata->name)
	<div class="row">
		{{ $contentdata->name }}
	</div>
	@endif
	@if ($contentdata->description)
	<div class="row">
		{{ $contentdata->description }}
	</div>
	@endif
<ul class="galleryul">
@if (count($blogentries))
<?php $count = 0; ?>
@foreach ($blogentries as $e)
	<div class="blog_entry">
		{!! $e !!}
	</div>
	<?php $count++; ?>
@endforeach
@endif
	@if ($permissions->get('edit'))
	<div class="blog_entry">
		{!! link_to('editor/blog_editor/add_entry/' . $dbrow->page_id . '/' .  $contentdata->id, trans('contentcontroller.new_blog_entry')) !!}
	</div>
	@endif
</ul>
</div>