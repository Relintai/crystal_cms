{!! link_to('/', trans('admin.back')) !!}<br>

{{ trans('admin.blog_editor') }}<br>
	
@if (count($errors) > 0)
<div class="errors">
		@foreach ($errors->all() as $e)
			{{ $e }}<br>
		@endforeach
</div>
@endif

@if ($info)
<div class="info">
	{{ $info }}
</div>
@endif

<div>
	{!! link_to('editor/image_uploader', trans('admin.image_uploader')) !!}
</div>
	
{!! Form::model($entry, array('url' => 'editor/blog_editor/entry')) !!}
{!! Form::hidden('blog_id', $blog_id) !!}<br>
{!! Form::hidden('entry_id', $entry_id) !!}<br>

{{ trans('admin.title') }}:<br>
{!! Form::text('title') !!}<br>

{{ trans('admin.text') }}:<br>
{!! Form::textarea('nomod_text') !!}<br>

{!! Form::submit(trans('admin.save')) !!}

{!! Form::close() !!}
<br><br>