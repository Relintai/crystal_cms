#ifndef MENU_NODE_H
#define MENU_NODE_H

#include "modules/admin_panel/admin_node.h"

#include "core/containers/vector.h"
#include "core/string.h"

#include "menu_data.h"
#include "menu_data_entry.h"

class Request;
class FormValidator;

class MenuNode : public AdminNode {
	RCPP_OBJECT(MenuNode, AdminNode);

public:
	virtual void render(Request *request);

	void create_validators();

	void admin_handle_request_main(Request *request);
	String admin_get_section_name();
	void admin_add_section_links(Vector<AdminSectionLinkInfo> *links);

	void admin_render_menuentry_list(Request *request);

	struct MenudminEntryViewData {
		Ref<MenuDataEntry> entry;
		Vector<String> messages;
	};

	void admin_handle_new_menuentry(Request *request);
	void admin_handle_edit_menuentry(Request *request);
	void render_menuentry_view(Request *request, MenudminEntryViewData *data);

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

	void _notification(int what);

	MenuNode();
	~MenuNode();

protected:
	String _table;

	Ref<MenuData> _data;
};

#endif