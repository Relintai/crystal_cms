#ifndef CCMS_ROOT_H
#define CCMS_ROOT_H

//#include "web/http/web_application.h"
#include "core/object.h"
#include "web/http/web_root.h"

#undef LOG_TRACE
#undef LOG_WARN

#include "page_manager/page_manager.h"

class AdminPanel;
class RBACController;
class RBACModel;
class UserController;
class MenuNode;
class PageManager;

#define ENSURE_LOGIN(request)                  \
	if (!is_logged_in(request)) {              \
		request->send_redirect("/user/login"); \
		return;                                \
	}

class CCMSRoot : public WebRoot {
	RCPP_OBJECT(CCMSRoot, WebRoot);
	
public:
	void handle_request_main(Request *request);
	void _handle_request_main(Request *request);

	void _render_menu(Request *request);

	bool is_logged_in(Request *request);

	void setup_middleware();

	void compile_menu();

	CCMSRoot();
	~CCMSRoot();

	AdminPanel *_admin_panel; 
	RBACController *_rbac_controller;
	UserController *_user_controller;
	MenuNode *_menu;
	PageManager *_page_manager;

	static std::string menu_head;
	static std::string footer;
};

#endif