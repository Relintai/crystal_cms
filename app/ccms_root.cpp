#include "ccms_root.h"

#include "core/http/request.h"

#include <iostream>

#include "core/file_cache.h"

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

void CCMSRoot::handle_request_main(Request *request) {
	process_middlewares(request);

	if (try_send_wwwroot_file(request)) {
		return;
	}

	// this is a hack, until I have a simple index node, or port contentcontroller.
	if (request->get_path_segment_count() == 0) {
		index(request);
		return;
	}

	WebNode *handler = get_request_handler_child(request);

	if (!handler) {
		request->send_error(404);
		return;
	}

	add_menu(request);
	handler->handle_request_main(request);
}

bool CCMSRoot::is_logged_in(Request *request) {
	if (!request->session.is_valid()) {
		return false;
	}

	Ref<User> u = request->reference_data["user"];

	return u.is_valid();
}

void CCMSRoot::index(Request *request) {
	// ENSURE_LOGIN(request);

	add_menu(request);

	request->body += "test";
	request->compile_and_send_body();
}

void CCMSRoot::add_menu(Request *request) {
	request->head += menu_head;

	_menu->render(request);

	HTMLBuilder b;

	b.div()->cls("main");
	b.write_tag();

	request->body += b.result;

	request->footer = footer;
}

void CCMSRoot::setup_middleware() {
	_middlewares.push_back(Ref<SessionSetupMiddleware>(new SessionSetupMiddleware()));
	// _middlewares.push_back(Ref<UserSessionSetupMiddleware>(new UserSessionSetupMiddleware()));
	// _middlewares.push_back(Ref<RBACUserSessionSetupMiddleware>(new RBACUserSessionSetupMiddleware()));
	_middlewares.push_back(Ref<RBACDefaultUserSessionSetupMiddleware>(new RBACDefaultUserSessionSetupMiddleware()));

	// WebRoot::setup_middleware();
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
	_user_controller->set_uri_segment("user");
	// user_manager->set_path("./users/");
	add_child(_user_controller);

	_rbac_controller = new RBACController();
	_rbac_controller->initialize();

	_menu = new MenuNode();

	_admin_panel = new AdminPanel();
	_admin_panel->set_uri_segment("admin");
	_admin_panel->register_admin_controller("rbac", _rbac_controller);
	_admin_panel->register_admin_controller("menu", _menu);

	_admin_panel->add_child(_rbac_controller);
	_admin_panel->add_child(_menu);

	add_child(_admin_panel);

	compile_menu();
}

CCMSRoot::~CCMSRoot() {
}

std::string CCMSRoot::menu_head = "";
std::string CCMSRoot::footer = "";
