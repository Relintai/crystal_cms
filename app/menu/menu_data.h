#ifndef MENU_DATA_H
#define MENU_DATA_H

#include "core/string.h"
#include "core/containers/vector.h"

#include "core/shared_resource.h"

#include "menu_data_entry.h"

class Request;

class MenuData : public SharedResource {
	RCPP_OBJECT(MenuData, SharedResource);

public:
	Vector<Ref<MenuDataEntry> > entries;

	void sort_entries();

	MenuData();
	~MenuData();
};

#endif