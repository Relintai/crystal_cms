#include "menu_controller.h"

#include "core/error_macros.h"

#include "core/html/form_validator.h"
#include "core/html/html_builder.h"
#include "core/http/cookie.h"
#include "core/http/http_session.h"
#include "core/http/request.h"
#include "core/http/session_manager.h"

#include "menu_model.h"

void MenuController::handle_request_main(Request *request) {
}

void MenuController::create_validators() {
}

void MenuController::admin_handle_request_main(Request *request) {
	String seg = request->get_current_path_segment();

	if (seg == "") {
		admin_render_rank_list(request);
		return;
	} else if (seg == "new_rank") {
		request->push_path();

		admin_handle_new_rank(request);
	} else if (seg == "edit_rank") {
		request->push_path();

		admin_handle_edit_rank(request);
	} else if (seg == "permission_editor") {
		request->push_path();

		admin_permission_editor(request);
	}
}

void MenuController::admin_handle_new_rank(Request *request) {

/*
	if (request->get_method() == HTTP_METHOD_POST) {
		Ref<RBACRank> rank;
		rank.instance();

		rank->name = request->get_parameter("name");
		rank->name_internal = request->get_parameter("name_internal");
		rank->settings = request->get_parameter("settings");

		RBACModel::get_singleton()->save_rank(rank);

		_ranks[rank->id] = rank;

		request->send_redirect(request->get_url_root_parent() + "edit_rank/" + String::num(rank->id));
		return;
	}

	RBACAdminRankViewData data;
	render_rank_view(request, &data);
	*/
}

void MenuController::admin_handle_edit_rank(Request *request) {
	/*
	String seg = request->get_current_path_segment();

	//check whether it's numeric
	//if (!seg.is)

	int id = seg.to_int();

	if (id == 0) {
		RLOG_MSG("MenuController::admin_handle_edit_rank: id == 0!\n");
		request->send_redirect(request->get_url_root_parent());
		return;
	}

	Ref<RBACRank> rank = _ranks[id];

	if (!rank.is_valid()) {
		RLOG_MSG("MenuController::admin_handle_edit_rank: !rank.is_valid()\n");
		request->send_redirect(request->get_url_root_parent());
		return;
	}

	RBACAdminRankViewData data;
	data.rank = rank;

	if (request->get_method() == HTTP_METHOD_POST) {
		rank->name = request->get_parameter("name");
		rank->name_internal = request->get_parameter("name_internal");
		rank->settings = request->get_parameter("settings");

		RBACModel::get_singleton()->save_rank(rank);

		data.messages.push_back("Save Success!");
	}

	render_rank_view(request, &data);
	*/
}

void MenuController::render_rank_view(Request *request, RBACAdminRankViewData *data) {
	/*
	int id = 0;
	String name = "";
	String name_internal = "";
	String settings = "";
	int base_permissions = 0;
	int rank_permissions = 0;

	if (data->rank.is_valid()) {
		id = data->rank->id;
		name = data->rank->name;
		name_internal = data->rank->name_internal;
		settings = data->rank->settings;
		base_permissions = data->rank->base_permissions;
		rank_permissions = data->rank->rank_permissions;
	}

	HTMLBuilder b;

	b.h4()->f()->a()->href(request->get_url_root_parent())->f()->w("<- Back")->ca()->ch4();
	b.h4()->f()->w("RBAC Editor")->ch4();

	b.div()->cls("messages");
	for (int i = 0; i < data->messages.size(); ++i) {
		b.w(data->messages[i])->br();
	}
	b.cdiv();

	b.form()->method("POST")->action(request->get_url_root() + String::num(id));
	{
		//b.input()->type("hidden")->name("id")->value(String::num(id))->f()->cinput();
		b.w("Name:")->br();
		b.input()->type("text")->name("name")->value(name)->f()->br();
		b.w("Name (Internal):")->br();
		b.input()->type("text")->name("name_internal")->value(name_internal)->f()->cinput()->br();
		b.w("Custom Settings:")->br();
		b.input()->type("text")->name("settings")->value(settings)->f()->cinput()->br();
		b.input()->type("submit")->value("Save");
	}
	b.cform();

	request->body += b.result;
	*/
}

void MenuController::admin_permission_editor(Request *request) {
/*
	String seg = request->get_current_path_segment();

	//check whether it's numeric
	//if (!seg.is)

	int id = seg.to_int();

	if (id == 0) {
		RLOG_MSG("MenuController::admin_permission_editor: id == 0!\n");
		request->send_redirect(request->get_url_root_parent());
		return;
	}

	Ref<RBACRank> rank = _ranks[id];

	if (!rank.is_valid()) {
		RLOG_MSG("MenuController::admin_permission_editor: !rank.is_valid()\n");
		request->send_redirect(request->get_url_root_parent());
		return;
	}

	RBACAdminEditPermissionView data;
	data.rank = rank;

	request->push_path();

	String segn = request->get_current_path_segment();

	if (segn == "") {
		admin_render_permission_editor_main_view(request, &data);
		return;
	}

	if (segn == "new") {
		request->push_path();

		if (request->get_method() == HTTP_METHOD_POST) {
			if (admin_process_permission_editor_entry_edit_create_post(request, &data)) {
				return;
			}
		}

		admin_render_permission_editor_entry_edit_create_view(request, &data);
		return;
	}

	if (segn.is_uint()) {
		int perm_index = segn.to_int();

		request->push_path();

		if (perm_index < 0 || perm_index >= rank->permissions.size()) {
			RLOG_ERR("(perm_index < 0 || perm_index >= rank->permissions.size())!\n");
			request->send_redirect(request->get_url_root_parent());
			return;
		}

		data.permission = rank->permissions[perm_index];

		if (!data.permission.is_valid()) {
			RLOG_ERR("(!data.permission.is_valid()\n");
			request->send_error(503);
			return;
		}

		if (request->get_method() == HTTP_METHOD_POST) {
			if (admin_process_permission_editor_entry_edit_create_post(request, &data)) {
				return;
			}
		}

		admin_render_permission_editor_entry_edit_create_view(request, &data);
		return;
	}

	request->send_error(404);
	*/
}

void MenuController::admin_render_permission_editor_main_view(Request *request, RBACAdminEditPermissionView *data) {
	/*
	HTMLBuilder b;

	Ref<RBACRank> rank = data->rank;

	b.h4()->f()->a()->href(request->get_url_root_parent(2))->f()->w("<- Back")->ca()->ch4();
	b.h4()->f()->w("RBAC Editor")->ch4();

	b.div()->cls("heading");
	{
		b.w("[ Id ]: ")->wn(rank->id)->w(", [ Name ]: ")->w(rank->name)->w(", [ Name Internal ]: ")->w(rank->name_internal);
	}
	b.cdiv();

	b.br();

	for (int i = 0; i < rank->permissions.size(); ++i) {
		Ref<RBACPermission> perm = rank->permissions[i];

		if (!perm.is_valid()) {
			RLOG_ERR("MenuController::admin_render_permission_editor_main_view: !perm.is_valid()\n");
			continue;
		}

		b.div()->cls("row");
		{
			b.a()->href(request->get_url_root() + String::num(i));

			b.w("-- Rank: [ Id ]: ")->wn(perm->id)->w(", [ Rank Id ]: ")->wn(perm->rank_id)->w(", [ Name ]: ")->w(perm->name);
			b.w(" [ URL ]: ")->w(perm->url)->w(", [ Sort Order ]: ")->wn(perm->sort_order);
			b.ca();
		}
		b.cdiv();
	}

	b.br();

	b.a()->href(request->get_url_root("new"));
	b.w("New Permission");
	b.ca();

	request->body += b.result;
	*/
}

void MenuController::admin_render_permission_editor_entry_edit_create_view(Request *request, RBACAdminEditPermissionView *data) {
	/*
	HTMLBuilder b;

	Ref<RBACRank> rank = data->rank;
	Ref<RBACPermission> perm = data->permission;

	String name;
	String url;
	int sort_order = 0;
	int permissions = 0;

	if (perm.is_valid()) {
		name = perm->name;
		url = perm->url;
		sort_order = perm->sort_order;
		permissions = perm->permissions;
	}

	b.h4()->f()->a()->href(request->get_url_root_parent())->f()->w("<- Back")->ca()->ch4();
	b.h4()->f()->w("RBAC Editor")->ch4();
	b.br();

	b.div()->cls("messages");
	for (int i = 0; i < data->messages.size(); ++i) {
		b.w(data->messages[i])->br();
	}
	b.cdiv();
	b.br();

	b.div()->cls("heading");
	{
		b.w("Rank: [ Id ]: ")->wn(rank->id)->w(", [ Name ]: ")->w(rank->name)->w(", [ Name Internal ]: ")->w(rank->name_internal);
	}
	b.cdiv();
	b.br();

	b.form()->method("POST")->action(request->get_url_root());
	{
		b.w("Name:")->br();
		b.input()->type("text")->name("name")->value(name)->f()->br();
		b.w("URL:")->br();
		b.input()->type("text")->name("url")->value(url)->f()->cinput()->br();
		b.input()->type("submit")->value("Save");
	}
	b.cform();

	request->body += b.result;
	*/
}

bool MenuController::admin_process_permission_editor_entry_edit_create_post(Request *request, RBACAdminEditPermissionView *data) {
	/*
	Ref<RBACRank> rank = data->rank;

	Ref<RBACPermission> perm = data->permission;

	if (!perm.is_valid()) {
		perm.instance();

		perm->rank_id = rank->id;

		if (rank->permissions.size() > 0) {
			Ref<RBACPermission> p = rank->permissions[rank->permissions.size() - 1];

			perm->sort_order = p->sort_order + 1;
		}

		rank->permissions.push_back(perm);
	}

	perm->name = request->get_parameter("name");
	perm->url = request->get_parameter("url");

	//set this up in the form by default
	//perm->sort_order = request->get_parameter("sort_order").to_int();

	RBACModel::get_singleton()->save_permission(perm);

	if (perm->id == 0) {
		RLOG_ERR("MenuController::admin_process_permission_editor_entry_edit_create_post: perm->id == 0!\n");
	}

	request->send_redirect(request->get_url_root_parent());
*/
	return true;
}

void MenuController::admin_render_rank_list(Request *request) {
	/*
	HTMLBuilder b;

	b.h4()->f()->a()->href(request->get_url_root_parent())->f()->w("<- Back")->ca()->ch4();
	b.h4()->f()->w("RBAC Editor")->ch4();

	for (std::map<int, Ref<RBACRank> >::iterator p = _ranks.begin(); p != _ranks.end(); p++) {
		Ref<RBACRank> r = p->second;

		if (!r.is_valid()) {
			continue;
		}

		b.div()->cls("row");
		{
			b.a()->href(request->get_url_root("permission_editor/") + String::num(r->id));
			b.w("[ Id ]: ")->wn(r->id)->w(", [ Name ]: ")->w(r->name)->w(", [ Name Internal ]: ")->w(r->name_internal);
			b.ca();

			b.w(" - ");

			b.a()->href(request->get_url_root("edit_rank/") + String::num(r->id));
			b.w("[ Edit ]");
			b.ca();
		}
		b.cdiv();
	}

	b.br();

	b.a()->href(request->get_url_root("new_rank"));
	b.w("New Rank");
	b.ca();

	request->body += b.result;
	*/
}

void MenuController::admin_render_rank_editor(Request *request) {
}

String MenuController::admin_get_section_name() {
	return "Menu Editor";
}

void MenuController::admin_add_section_links(Vector<AdminSectionLinkInfo> *links) {
	links->push_back(AdminSectionLinkInfo("Editor", ""));
}

void MenuController::initialize() {
	_data = MenuModel::get_singleton()->load();
}

Ref<MenuData> MenuController::get_data() {
	return _data;
}

bool MenuController::continue_on_missing_default_rank() {
	//todo, add setting
	return false;
}

MenuController *MenuController::get_singleton() {
	return _self;
}

MenuController::MenuController() :
		AdminController() {

	if (_self) {
		printf("MenuController::MenuController(): Error! self is not null!/n");
	}

	_self = this;
}

MenuController::~MenuController() {
	if (_self == this) {
		_self = nullptr;
	}
}

MenuController *MenuController::_self = nullptr;
