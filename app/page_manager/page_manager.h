#ifndef PAGE_MANAGER_H
#define PAGE_MANAGER_H

#include "modules/admin_panel/admin_node.h"

#include "core/containers/vector.h"
#include "core/string.h"

class Request;
class FormValidator;

class PageManager : public AdminNode {
	RCPP_OBJECT(PageManager, AdminNode);

public:
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

	void admin_handle_up(Request *request);
	void admin_handle_down(Request *request);
	void admin_handle_delete(Request *request);

	void initialize();

	Ref<MenuData> get_data();

	String &get_redirect_url();

	bool continue_on_missing_default_rank();

	//db

	virtual Ref<MenuData> db_load();

	virtual void db_save(const Ref<MenuData> &menu);
	virtual void db_save_menu_entry(const Ref<MenuDataEntry> &entry);
	virtual void db_delete_menu_entry(const int id);

	void create_table();
	void drop_table();
	void migrate();
	void create_default_entries();

	void _notification(int what);

	PageManager();
	~PageManager();

protected:
	String _table;

	Ref<MenuData> _data;
};

#endif