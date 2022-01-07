#include "ccms_root.h"

#include "core/http/request.h"

#include <iostream>

#include "core/file_cache.h"

#include "core/http/handler_instance.h"

#include "core/database/database_manager.h"

#include "core/html/html_builder.h"
#include "core/http/http_session.h"
#include "core/http/session_manager.h"

#include "modules/users/user.h"
//#include "modules/users/user_controller.h"
#include "modules/rbac_users/rbac_user_controller.h"

#include "modules/admin_panel/admin_panel.h"
#include "modules/rbac/rbac_controller.h"

#include "ccms_user_controller.h"

#include "core/os/platform.h"

#include "menu/menu_node.h"

bool CCMSRoot::is_logged_in(Request *request) {
	if (!request->session) {
		return false;
	}

	Ref<User> u = request->reference_data["user"];

	return u.is_valid();
}

void CCMSRoot::index(Object *instance, Request *request) {
	//ENSURE_LOGIN(request);

	add_menu(instance, request);

	request->body += "test";
	request->compile_and_send_body();
}

void CCMSRoot::session_middleware_func(Object *instance, Request *request) {
}

void CCMSRoot::add_menu(Object *instance, Request *request) {
	request->head += menu_head;

	Object::cast_to<CCMSRoot>(instance)->_menu->render(request);

	HTMLBuilder b;

	b.div()->cls("main");
	b.write_tag();

	request->body += b.result;

	request->footer = footer;
}

void CCMSRoot::village_page_func(Object *instance, Request *request) {
	add_menu(instance, request);

	// dynamic_cast<ListPage *>(instance)->index(request);
	request->body += "test";
	request->compile_and_send_body();
}

void CCMSRoot::admin_page_func(Object *instance, Request *request) {
	AdminPanel::get_singleton()->handle_request_main(request);
}

void CCMSRoot::user_page_func(Object *instance, Request *request) {
	if (is_logged_in(request)) {
		add_menu(instance, request);
	}

	UserController::get_singleton()->handle_request_default(request);
}

void CCMSRoot::setup_routes() {
	WebRoot::setup_routes();

	index_func = HandlerInstance(index, this);
	main_route_map["admin"] = HandlerInstance(admin_page_func, this);
	main_route_map["user"] = HandlerInstance(user_page_func, this);
}

void CCMSRoot::setup_middleware() {
	middlewares.push_back(HandlerInstance(::SessionManager::session_setup_middleware));
	// middlewares.push_back(HandlerInstance(::UserController::user_session_setup_middleware));
	// middlewares.push_back(HandlerInstance(::RBACUserController::rbac_user_session_setup_middleware));
	middlewares.push_back(HandlerInstance(::RBACUserController::rbac_default_user_session_middleware));

	WebRoot::setup_middleware();
}

void CCMSRoot::migrate() {
	_rbac_controller->migrate();
	_user_controller->migrate();

	if (Platform::get_singleton()->arg_parser.has_arg("-u")) {
		printf("Creating test users.\n");
		_user_controller->create_test_users();
	}

	_menu->migrate();
}

void CCMSRoot::compile_menu() {
	HTMLBuilder bh;

	bh.meta()->charset_utf_8();
	bh.title();
	bh.w("Crystal CMS");
	bh.ctitle();

	bh.link()->rel_stylesheet()->href("/css/base.css");
	bh.link()->rel_stylesheet()->href("/css/menu.css");
	bh.write_tag();

	menu_head = bh.result;

	HTMLBuilder bf;

	bf.cdiv();
	bf.footer();
	bf.cfooter();

	footer = bf.result;
}

CCMSRoot::CCMSRoot() :
		WebRoot() {

	_user_controller = new CCMSUserController();
	// user_manager->set_path("./users/");
	add_child(_user_controller);

	_rbac_controller = new RBACController();
	_rbac_controller->initialize();

	_menu = new MenuNode();

	_admin_panel = new AdminPanel();
	_admin_panel->register_admin_controller("rbac", _rbac_controller);
	_admin_panel->register_admin_controller("menu", _menu);

	_admin_panel->add_child(_rbac_controller);
	_admin_panel->add_child(_menu);

	add_child(_admin_panel);

	compile_menu();
}

CCMSRoot::~CCMSRoot() {
	delete _admin_panel;
	delete _rbac_controller;
	delete _menu;
	delete _user_controller;
}

std::string CCMSRoot::menu_head = "";
std::string CCMSRoot::footer = "";
