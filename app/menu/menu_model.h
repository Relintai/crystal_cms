#ifndef MENU_MODEL_H
#define MENU_MODEL_H

#include "core/http/web_node.h"

#include "core/containers/vector.h"
#include "core/string.h"

#include "menu_data.h"
#include "menu_data_entry.h"

class MenuModel : public WebNode {
	RCPP_OBJECT(MenuModel, WebNode);

public:
	virtual Ref<MenuData> load();

	virtual void save(const Ref<MenuData> &menu);
	virtual void save_menu_entry(const Ref<MenuDataEntry> &entry);

	void create_table();
	void drop_table();
	void migrate();
	virtual void create_default_entries();

	static MenuModel *get_singleton();

	MenuModel();
	~MenuModel();

protected:
	static MenuModel *_self;
};

#endif