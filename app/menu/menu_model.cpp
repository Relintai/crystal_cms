#include "menu_model.h"

#include "core/database/database.h"
#include "core/database/database_manager.h"
#include "core/database/query_builder.h"
#include "core/database/query_result.h"
#include "core/database/table_builder.h"

#include "core/settings/settings.h"

#define MENU_TABLE "menu"

Ref<MenuData> MenuModel::load() {
	Ref<MenuData> data;
	data.instance();

	Ref<QueryBuilder> qb = DatabaseManager::get_singleton()->ddb->get_query_builder();

	qb->select("id,name,url,sort_order")->from(MENU_TABLE);
	Ref<QueryResult> res = qb->run();

	while (res->next_row()) {
		Ref<MenuDataEntry> e;
		e.instance();

		e->id = res->get_cell_int(0);
		e->name = res->get_cell_str(1);
		e->url = res->get_cell_str(2);
		e->sort_order = res->get_cell_int(3);

		data->entries.push_back(e);
	}

	data->sort_entries();

	return data;
}

void MenuModel::save(const Ref<MenuData> &menu) {
	for (int i = 0; i < menu->entries.size(); ++i) {
		Ref<MenuDataEntry> entry = menu->entries[i];

		save_menu_entry(entry);
	}
}

void MenuModel::save_menu_entry(const Ref<MenuDataEntry> &entry) {
	Ref<QueryBuilder> qb = DatabaseManager::get_singleton()->ddb->get_query_builder();

	if (entry->id == 0) {
		qb->insert(MENU_TABLE, "name,url,sort_order")->values();
		qb->val(entry->name)->val(entry->url);
		qb->val(entry->sort_order);
		qb->cvalues();
		qb->select_last_insert_id();
		Ref<QueryResult> res = qb->run();
		//qb->print();

		Ref<MenuDataEntry> e = entry;

		e->id = res->get_last_insert_rowid();
	} else {
		qb->update(MENU_TABLE)->set();
		qb->setp("name", entry->name);
		qb->setp("url", entry->url);
		qb->setp("sort_order", entry->sort_order);
		qb->cset();
		qb->where()->wp("id", entry->id);
		qb->end_command();
		qb->run_query();
		//qb->print();
	}
}

void MenuModel::create_table() {
	Ref<TableBuilder> tb = DatabaseManager::get_singleton()->ddb->get_table_builder();

	tb->create_table(MENU_TABLE);
	tb->integer("id")->auto_increment()->next_row();
	tb->varchar("name", 60)->not_null()->next_row();
	tb->varchar("url", 500)->not_null()->next_row();
	tb->integer("sort_order")->not_null()->next_row();
	tb->primary_key("id");
	tb->ccreate_table();
	tb->run_query();
	//tb->print();
}
void MenuModel::drop_table() {
	Ref<TableBuilder> tb = DatabaseManager::get_singleton()->ddb->get_table_builder();

	tb->drop_table_if_exists(MENU_TABLE)->run_query();
	//tb->print();
}
void MenuModel::migrate() {
	drop_table();
	create_table();
	create_default_entries();
}

void MenuModel::create_default_entries() {
}

MenuModel *MenuModel::get_singleton() {
	return _self;
}

MenuModel::MenuModel() :
		WebNode() {

	if (_self) {
		printf("MenuModel::MenuModel(): Error! self is not null!/n");
	}

	_self = this;
}

MenuModel::~MenuModel() {
	if (_self == this) {
		_self = nullptr;
	}
}

MenuModel *MenuModel::_self = nullptr;
