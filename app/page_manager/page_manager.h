#ifndef PAGE_MANAGER_H
#define PAGE_MANAGER_H

#include "web_modules/admin_panel/admin_node.h"

#include "core/containers/vector.h"
#include "core/string.h"

class Request;
class FormValidator;

class Page;
class PageContent;

// Handle the pages with nodes
//Add rw lock for when then need to change

// PageManager
//   Page1 (uri)
//     TextContent (TextContentNode) -> contains the editor, and handles it's table directly (Controller - Model - View)
//     ImageContent (ImageContentNode) -> contains the editor code, and handles it's table directly
//   Page2
//etc

class PageManager : public AdminNode {
	RCPP_OBJECT(PageManager, AdminNode);

public:
	enum PageType {
		PAGE_TYPE_PAGE = 0,
	};

	void _handle_request_main(Request *request);

	void create_validators();

	void admin_handle_request_main(Request *request);
	String admin_get_section_name();
	void admin_add_section_links(Vector<AdminSectionLinkInfo> *links);

	void admin_render_page_list(Request *request);

	struct PageAdminEntryViewData {
		Ref<Page> entry;
		Vector<String> messages;
	};

	void admin_handle_new_page(Request *request);
	void admin_handle_edit_page(Request *request);
	void render_page_view(Request *request, PageAdminEntryViewData *data);

	void admin_handle_delete(Request *request);

	void invalidate_cache();

	//void invalidate_cache(String page);
	//Page -> owner (PageManager*) -> when changes 

	//db

	virtual Vector<Ref<Page> > db_get_pages();

	virtual void db_save_page(const Ref<Page> &page);
	virtual void db_save_page_content(const Ref<PageContent> &entry);
	virtual void db_delete_page(const int id);

	void create_table();
	void drop_table();
	void create_default_entries();

	virtual void initialize();
	void _notification(int what);

	PageManager();
	~PageManager();

protected:
	String _table_prefix;
	String _table;

	//rwlock _cache_lock;
	//map<Sting, Page> _page_cache;
};

#endif