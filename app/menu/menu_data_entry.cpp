#include "menu_data_entry.h"

bool MenuDataEntry::is_smaller(const Ref<MenuDataEntry> &b) const {
	if (!b.is_valid()) {
		return true;
	}

	return sort_order < b->sort_order;
}

MenuDataEntry::MenuDataEntry() :
		SharedResource() {

	id = 0;
	sort_order = 0;
}

MenuDataEntry::~MenuDataEntry() {
}
