#include "menu_data.h"

void MenuData::sort_entries() {
	for (int i = 0; i < entries.size(); ++i) {
		for (int j = i + 1; j < entries.size(); ++j) {
			if (entries[j]->is_smaller(entries[i])) {
				entries.swap(i, j);
			}
		}
	}
}

MenuData::MenuData() :
		SharedResource() {
}

MenuData::~MenuData() {
	entries.clear();
}
