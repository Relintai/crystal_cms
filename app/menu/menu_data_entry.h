#ifndef MEUN_DATA_ENTRY_H
#define MEUN_DATA_ENTRY_H

#include "core/string.h"

#include "core/shared_resource.h"

class MenuDataEntry : public SharedResource {
	RCPP_OBJECT(MenuDataEntry, SharedResource);

public:
	int id;
	String name;
	String url;
	int sort_order;

	bool is_smaller(const Ref<MenuDataEntry> &b) const;

	MenuDataEntry();
	~MenuDataEntry();
};

#endif