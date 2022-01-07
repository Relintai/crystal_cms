#ifndef MENU_CONTROLLER_H
#define MENU_CONTROLLER_H

#include "modules/admin_panel/admin_node.h"

#include "core/containers/vector.h"
#include "core/string.h"

#include "menu_data.h"
#include "menu_data_entry.h"

class Request;
class FormValidator;

class MenuController : public AdminNode {
	RCPP_OBJECT(MenuController, AdminNode);

public:
	void handle_request_main(Request *request);
	void create_validators();

	void admin_handle_request_main(Request *request);
	String admin_get_section_name();
	void admin_add_section_links(Vector<AdminSectionLinkInfo> *links);

	struct RBACAdminRankViewData {
		Ref<MenuData> rank;
		Vector<String> messages;

		int id = 0;
		String name = "";
		String name_internal = "";
		String settings = "";
		int rank_permissions = 0;
	};

	void admin_handle_new_rank(Request *request);
	void admin_handle_edit_rank(Request *request);
	void render_rank_view(Request *request, RBACAdminRankViewData *data);

	struct RBACAdminEditPermissionView {
		Ref<MenuData> rank;
		Ref<MenuDataEntry> permission;
		Vector<String> messages;

		int rank_id = 0;
		int permission_id = 0;
	};

	void admin_permission_editor(Request *request);
	void admin_render_permission_editor_main_view(Request *request, RBACAdminEditPermissionView *data);
	void admin_render_permission_editor_entry_edit_create_view(Request *request, RBACAdminEditPermissionView *data);
	bool admin_process_permission_editor_entry_edit_create_post(Request *request, RBACAdminEditPermissionView *data);

	void admin_render_rank_list(Request *request);
	void admin_render_rank_editor(Request *request);

	void initialize();

	Ref<MenuData> get_data();

	String &get_redirect_url();

	bool continue_on_missing_default_rank();

	//db

	virtual Ref<MenuData> db_load();

	virtual void db_save(const Ref<MenuData> &menu);
	virtual void db_save_menu_entry(const Ref<MenuDataEntry> &entry);

	void create_table();
	void drop_table();
	void migrate();
	void create_default_entries();

	MenuController();
	~MenuController();

protected:
	String _table;

	Ref<MenuData> _data;
};

#endif