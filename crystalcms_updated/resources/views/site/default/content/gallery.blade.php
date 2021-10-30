@if ($permissions->get('edit'))
<div>
{{ trans('contentcontroller.gallery') }} {!! link_to('editor/gallery_editor/' . $dbrow->page_id . '/' .  $contentdata->id ,trans('contentcontroller.edit')) !!} {!! link_to('editor/gallery_editor/images/' . $dbrow->page_id . '/' .  $contentdata->id, trans('contentcontroller.edit_images')) !!}
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
@if (count($images))
<?php $count = 0; ?>
@foreach ($images as $i)
	<li class="<?php if ($count % 4 == 0) echo "galleryulfirst"; else echo "galleryulli"; ?>">
		<a class="galleryimagea" href="{!! asset('img/gallery/' . $contentdata->folder . '/big/' . $i->image_thumb) !!}">
			<img class="galleryimage" src="{!! asset('img/gallery/' . $contentdata->folder . '/thumb/' . $i->image_thumb) !!}" alt="{{ $i->description }}">
		</a>
	</li>
	<?php $count++; ?>
@endforeach
@endif
	@if ($permissions->get('edit'))
	<a id="add_image_link" href="{!! url('editor/gallery_editor/add_image/' . $dbrow->page_id . '/' .  $contentdata->id) !!}">
	<li>
		<img class="galleryimage" src="{!! asset('img/gallery/add.jpg') !!}" alt="{{ trans('contentcontroller.add') }}">
	</li>
	</a>
	@endif
</ul>
</div>