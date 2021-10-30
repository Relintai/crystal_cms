{!! Form::open(array('url' => 'admin/blog_editor/blog_page_settings_set')) !!}
{!! Form::hidden('id', $id) !!}
{!! Form::select('blog_id', $blogs, $selected) !!}
{!! Form::submit(trans('admin.save')) !!}
{!! Form::close() !!}