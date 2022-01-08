#ifndef CCMS_ROOT_H
#define CCMS_ROOT_H

//#include "core/http/web_application.h"
#include "core/object.h"
#include "core/http/web_root.h"

#undef LOG_TRACE
#undef LOG_WARN

#include "modules/list_page/list_page.h"
#include "modules/message_page/message_page.h"
#include "modules/paged_article/paged_article.h"
#include "modules/paged_list/paged_list.h"

class AdminPanel;
class RBACController;
class RBACModel;
class UserController;
class MenuNode;

#define ENSURE_LOGIN(request)                  \
	if (!is_logged_in(request)) {              \
		request->send_redirect("/user/login"); \
		return;                                \
	}

class CCMSRoot : public WebRoot {
	RCPP_OBJECT(CCMSRoot, WebRoot);
	
public:
	void handle_request_main(Request *request);

	bool is_logged_in(Request *request);

	void index(Request *request);

	void add_menu(Request *request);

	virtual void setup_middleware();
	void migrate();

	void compile_menu();

	CCMSRoot();
	~CCMSRoot();

	AdminPanel *_admin_panel; 
	RBACController *_rbac_controller;
	UserController *_user_controller;
	MenuNode *_menu;

	static std::string menu_head;
	static std::string footer;
};

#endif