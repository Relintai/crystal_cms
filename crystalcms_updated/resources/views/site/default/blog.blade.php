@if ($info)
<div class="info">
	{{ $info }}
</div>
@endif
@if (count($entries) > 0)
@foreach ($entries as $e)
	{!! $e->title !!} {!! link_to('editor/blog_editor/edit_entry/' . $blogs->id . '/' . $e->id, '[' . trans('admin.edit') . ']') !!} {!! link_to('editor/blog_editor/delete_entry/' . $blogs->id . '/' . $e->id, '[' . trans('admin.delete') . ']') !!}<br><br>
	{!! $e->truncated_text !!}<br><br><br>
@endforeach
@endif
@if ($permissions->get('add'))
<div>
{!! link_to('editor/blog_editor/add_entry/' . $blogs->id, trans('admin.new_entry')) !!} 
</div>
@endif