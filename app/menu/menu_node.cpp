#include "menu_node.h"

#include "core/error_macros.h"

#include "core/html/form_validator.h"
#include "core/html/html_builder.h"
#include "core/http/cookie.h"
#include "core/http/http_session.h"
#include "core/http/request.h"
#include "core/http/session_manager.h"

#include "core/database/database.h"
#include "core/database/database_manager.h"
#include "core/database/query_builder.h"
#include "core/database/query_result.h"
#include "core/database/table_builder.h"

void MenuNode::render(Request *request) {
	/*
	<div class="menu">
		<ul class="menu">
			@if (count($menu) > 0)
			@foreach ($menu as $e)
			<li class="menuentry">
				{!! link_to($e->url, trans('menu.' . $e->name_key)) !!}
			</li>
			@endforeach
			@endif
		</ul>
	</div>
	*/
}

void MenuNode::create_validators() {
}

void MenuNode::admin_handle_request_main(Request *request) {
	String seg = request->get_current_path_segment();

	if (seg == "") {
		admin_render_menuentry_list(request);
		return;
	} else if (seg == "new_entry") {
		request->push_path();

		admin_handle_new_menuentry(request);
	} else if (seg == "edit_entry") {
		request->push_path();

		admin_handle_edit_menuentry(request);
	}
}

void MenuNode::admin_handle_new_menuentry(Request *request) {
	if (request->get_method() == HTTP_METHOD_POST) {
		Ref<MenuDataEntry> entry;
		entry.instance();

		entry->name = request->get_parameter("name");
		entry->url = request->get_parameter("url");
		entry->sort_order = _data->entries.size() + 1;

		db_save_menu_entry(entry);

		_data->entries.push_back(entry);

		request->send_redirect(request->get_url_root_parent() + "edit_entry/" + String::num(entry->id));

		return;
	}

	MenudminEntryViewData data;
	render_menuentry_view(request, &data);
}

void MenuNode::admin_handle_edit_menuentry(Request *request) {
	String seg = request->get_current_path_segment();

	int id = seg.to_int();

	Ref<MenuDataEntry> entry;

	for (int i = 0; i < _data->entries.size(); ++i) {
		Ref<MenuDataEntry> e = _data->entries[i];

		if (e->id == id) {
			entry = e;
			break;
		}
	}

	if (!entry.is_valid()) {
		RLOG_MSG("MenuNode::admin_handle_edit_menuentry: !entry.is_valid()\n");
		request->send_redirect(request->get_url_root_parent());
		return;
	}

	MenudminEntryViewData data;
	data.entry = entry;

	if (request->get_method() == HTTP_METHOD_POST) {
		entry->name = request->get_parameter("name");
		entry->url = request->get_parameter("url");

		db_save_menu_entry(entry);

		data.messages.push_back("Save Success!");
	}

	render_menuentry_view(request, &data);
}

void MenuNode::render_menuentry_view(Request *request, MenudminEntryViewData *data) {
	int id = 0;
	String name = "";
	String url = "";
	int sort_order = 0;
	bool editing = false;

	if (data->entry.is_valid()) {
		id = data->entry->id;
		name = data->entry->name;
		url = data->entry->url;
		sort_order = data->entry->sort_order;
		editing = true;
	}

	HTMLBuilder b;

	b.h4()->f()->a()->href(request->get_url_root_parent())->f()->w("&lt;- Back")->ca()->ch4();
	b.h4()->f()->w("Menu Editor")->ch4();

	b.div()->cls("messages");
	for (int i = 0; i < data->messages.size(); ++i) {
		b.w(data->messages[i])->br();
	}
	b.cdiv();

	String aurl = request->get_url_root();

	if (id != 0) {
		aurl += String::num(id);
	}

	b.form()->method("POST")->action(aurl);
	{
		b.w("Name:")->br();
		b.input_text("name", name)->f()->br();

		b.w("URL:")->br();
		b.input_text("url", url)->f()->br();

		if (!editing) {
			b.w("Create Page?");
			b.input_checkbox("create_page", "create_page")->checked()->f()->br();
		}

		b.input()->type("submit")->value("Save");
	}
	b.cform();

	request->body += b.result;
}

String MenuNode::admin_get_section_name() {
	return "Menu Editor";
}

void MenuNode::admin_add_section_links(Vector<AdminSectionLinkInfo> *links) {
	links->push_back(AdminSectionLinkInfo("Editor", ""));
}

void MenuNode::admin_render_menuentry_list(Request *request) {
	HTMLBuilder b;

	b.h4()->f()->a()->href(request->get_url_root_parent())->f()->w("&lt;- Back")->ca()->ch4();
	b.h4()->f()->w("Menu Editor")->ch4();
	b.style()->f()->w("li { display: inline-block; }")->cstyle();

	for (int i = 0; i < _data->entries.size(); ++i) {
		Ref<MenuDataEntry> e = _data->entries[i];

		if (!e.is_valid()) {
			continue;
		}

		b.div()->cls("row")->f()->ul();
		{
			b.li();
			b.a()->href(request->get_url_root("edit_entry/") + String::num(e->id));
			b.w("id: ")->wn(e->id)->w(" name: ")->w(e->name)->w(" url: ")->w(e->url);
			b.ca();
			b.cli();

			b.li();
			{
				if (i != 0) {
					b.form()->method("POST")->action(request->get_url_root() + "up");
					{
						b.input_hidden("id", String::num(e->id));
						b.input_submit("Up");
					}
					b.cform();
				} else {
					b.w("Up");
				}
			}
			b.cli();

			b.li();
			{
				if (i + 1 != _data->entries.size()) {
					b.form()->method("POST")->action(request->get_url_root() + "down");
					{
						b.input_hidden("id", String::num(e->id));
						b.input_submit("Down");
					}
					b.cform();
				} else {
					b.w("Down");
				}
			}
			b.cli();

			b.li();
			{
				b.form()->method("POST")->action(request->get_url_root() + "delete");
				{
					b.input_hidden("id", String::num(e->id));
					b.input_submit("Delete");
				}
				b.cform();
			}
			b.cli();
		}
		b.cul()->cdiv();
	}

	b.br();

	b.a()->href(request->get_url_root("new_entry"));
	b.w("New Menu Entry");
	b.ca();

	request->body += b.result;
}

void MenuNode::initialize() {
	_data = db_load();
}

Ref<MenuData> MenuNode::get_data() {
	return _data;
}

bool MenuNode::continue_on_missing_default_rank() {
	// todo, add setting
	return false;
}

// DB

Ref<MenuData> MenuNode::db_load() {
	Ref<MenuData> data;
	data.instance();

	Ref<QueryBuilder> qb = get_query_builder();

	qb->select("id,name,url,sort_order")->from(_table);
	Ref<QueryResult> res = qb->run();

	while (res->next_row()) {
		Ref<MenuDataEntry> e;
		e.instance();

		e->id = res->get_cell_int(0);
		e->name = res->get_cell_str(1);
		e->url = res->get_cell_str(2);
		e->sort_order = res->get_cell_int(3);

		data->entries.push_back(e);
	}

	data->sort_entries();

	return data;
}

void MenuNode::db_save(const Ref<MenuData> &menu) {
	for (int i = 0; i < menu->entries.size(); ++i) {
		Ref<MenuDataEntry> entry = menu->entries[i];

		db_save_menu_entry(entry);
	}
}

void MenuNode::db_save_menu_entry(const Ref<MenuDataEntry> &entry) {
	Ref<QueryBuilder> qb = get_query_builder();

	if (entry->id == 0) {
		qb->insert(_table, "name,url,sort_order")->values();
		qb->val(entry->name)->val(entry->url);
		qb->val(entry->sort_order);
		qb->cvalues();
		qb->select_last_insert_id();
		Ref<QueryResult> res = qb->run();
		// qb->print();

		Ref<MenuDataEntry> e = entry;

		e->id = res->get_last_insert_rowid();
	} else {
		qb->update(_table)->set();
		qb->setp("name", entry->name);
		qb->setp("url", entry->url);
		qb->setp("sort_order", entry->sort_order);
		qb->cset();
		qb->where()->wp("id", entry->id);
		qb->end_command();
		qb->run_query();
		// qb->print();
	}
}

void MenuNode::create_table() {
	Ref<TableBuilder> tb = get_table_builder();

	tb->create_table(_table);
	tb->integer("id")->auto_increment()->next_row();
	tb->varchar("name", 60)->not_null()->next_row();
	tb->varchar("url", 500)->not_null()->next_row();
	tb->integer("sort_order")->not_null()->next_row();
	tb->primary_key("id");
	tb->ccreate_table();
	tb->run_query();
	// tb->print();
}
void MenuNode::drop_table() {
	Ref<TableBuilder> tb = get_table_builder();

	tb->drop_table_if_exists(_table)->run_query();
	// tb->print();
}
void MenuNode::migrate() {
	drop_table();
	create_table();
	create_default_entries();
}

void MenuNode::create_default_entries() {
}

void MenuNode::_notification(int what) {
	if (what == Node::NOTIFICATION_ENTER_TREE) {
		initialize();
	}
}

MenuNode::MenuNode() :
		AdminNode() {

	_table = "menu";
}

MenuNode::~MenuNode() {
}
