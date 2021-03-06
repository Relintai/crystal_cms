#include "menu_node.h"

#include "core/error_macros.h"

#include "web/html/form_validator.h"
#include "web/html/html_builder.h"
#include "web/http/cookie.h"
#include "web/http/http_session.h"
#include "web/http/request.h"
#include "web/http/session_manager.h"

#include "database/database.h"
#include "database/database_manager.h"
#include "database/query_builder.h"
#include "database/query_result.h"
#include "database/table_builder.h"

void MenuNode::render(Request *request) {
	HTMLBuilder b;

	b.div("menu");
	{
		b.ul()->cls("menu");
		{
			// read lock _data
			for (int i = 0; i < _data->entries.size(); ++i) {
				Ref<MenuDataEntry> e = _data->entries[i];

				b.li();
				{
					b.a("/" + e->url)->w(e->url)->ca();
				}
				b.cli();
			}
			// read unlock _data
		}
		b.cul();
	}
	b.cdiv();

	request->body += b.result;
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
	} else if (seg == "up") {
		request->push_path();

		admin_handle_up(request);
	} else if (seg == "down") {
		request->push_path();

		admin_handle_down(request);
	} else if (seg == "delete") {
		request->push_path();

		admin_handle_delete(request);
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
		b.csrf_token(request);

		b.w("Name:")->br();
		b.input_text("name", name)->br();

		b.w("URL:")->br();
		b.input_text("url", url)->br();

		if (!editing) {
			b.w("Create Page?");
			b.input_checkbox("create_page", "create_page", true)->br();
		}

		b.br();
		b.input()->type("submit")->value("Save");
	}
	b.cform();

	request->body += b.result;
}

void MenuNode::admin_handle_up(Request *request) {
	String pid = request->get_parameter("id");

	if (!pid.is_int()) {
		request->send_redirect(request->get_url_root_parent());
		return;
	}

	int id = pid.to_int();

	// TODO
	// Also lock everywhere else
	//_data->write_lock()

	for (int i = 0; i < _data->entries.size(); ++i) {
		Ref<MenuDataEntry> e = _data->entries[i];

		if (e->id == id) {
			if (e->sort_order == 0) {
				RLOG_ERR("MenuEditor->up: Up operation on 0th element!");
				break;
			}

			int aso = e->sort_order - 1;
			Ref<MenuDataEntry> above;

			for (int j = 0; j < _data->entries.size(); ++j) {
				Ref<MenuDataEntry> ae = _data->entries[j];

				if (ae->sort_order == aso) {
					above = ae;
					break;
				}
			}

			if (above.is_valid()) {
				above->sort_order += 1;
				db_save_menu_entry(above);
			}

			e->sort_order -= 1;
			db_save_menu_entry(e);
			_data->sort_entries();
			break;
		}
	}

	//_data->write_unlock()
	request->send_redirect(request->get_url_root_parent());
}

void MenuNode::admin_handle_down(Request *request) {
	String pid = request->get_parameter("id");

	if (!pid.is_int()) {
		request->send_redirect(request->get_url_root_parent());
		return;
	}

	int id = pid.to_int();

	// TODO
	// Also lock everywhere else
	//_data->write_lock()

	for (int i = 0; i < _data->entries.size(); ++i) {
		Ref<MenuDataEntry> e = _data->entries[i];

		if (e->id == id) {
			if (e->sort_order == _data->entries.size()) {
				RLOG_ERR("MenuEditor->down: Down operation on the last element!");
				break;
			}

			int bso = e->sort_order + 1;
			Ref<MenuDataEntry> below;

			for (int j = 0; j < _data->entries.size(); ++j) {
				Ref<MenuDataEntry> be = _data->entries[j];

				if (be->sort_order == bso) {
					below = be;
					break;
				}
			}

			if (below.is_valid()) {
				below->sort_order -= 1;
				db_save_menu_entry(below);
			}

			e->sort_order += 1;
			db_save_menu_entry(e);
			_data->sort_entries();
			break;
		}
	}

	//_data->write_unlock()
	request->send_redirect(request->get_url_root_parent());
}

void MenuNode::admin_handle_delete(Request *request) {
	String pid = request->get_parameter("id");

	if (!pid.is_int()) {
		request->send_redirect(request->get_url_root_parent());
		return;
	}

	int id = pid.to_int();

	// TODO
	// Also lock everywhere else
	//_data->write_lock()

	for (int i = 0; i < _data->entries.size(); ++i) {
		Ref<MenuDataEntry> e = _data->entries[i];

		if (e->id == id) {
			int sort_order = e->sort_order;

			for (int j = 0; j < _data->entries.size(); ++j) {
				Ref<MenuDataEntry> be = _data->entries[j];

				if (be->sort_order > sort_order) {
					be->sort_order -= 1;
					db_save_menu_entry(be);
				}
			}

			db_delete_menu_entry(e->id);

			_data->entries.remove_keep_order(i);

			break;
		}
	}

	//_data->write_unlock()
	request->send_redirect(request->get_url_root_parent());
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
						b.csrf_token(request);
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
						b.csrf_token(request);
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
					b.csrf_token(request);
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

void MenuNode::db_delete_menu_entry(const int id) {
	Ref<QueryBuilder> qb = get_query_builder();

	qb->del(_table)->where()->wp("id", id);
	qb->run_query();
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
