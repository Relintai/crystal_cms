#include <string.h>
#include <iostream>
#include <string>

#include "core/bry_http/http_server.h"
#include "core/file_cache.h"
#include "core/http/web_application.h"

#include "app/ccms_application.h"

#include "database/db_init.h"

#include "core/settings.h"

#include "core/http/session_manager.h"

#define MAIN_CLASS CCMSApplication

#include "modules/drogon/web_application.h"

//Backends
#include "backends/hash_hashlib/setup.h"

#include "modules/users/user.h"
#include "app/ccms_user_controller.h"
#include "modules/users/user_model.h"

#include "core/database/database_manager.h"

void initialize_backends() {
	initialize_database_backends();
	backend_hash_hashlib_install_providers();
}

void create_databases() {
	DatabaseManager *dbm = DatabaseManager::get_singleton();

	uint32_t index = dbm->create_database("sqlite");
	Database *db = dbm->databases[index];
	db->connect("database.sqlite");
}

int main(int argc, char **argv) {
	initialize_backends();

	bool migrate = false;

	for (int i = 1; i < argc; ++i) {
		const char *a = argv[i];

		if (a[0] == 'm') {
			migrate = true;
		}
	}

	::SessionManager *session_manager = new ::SessionManager();

	//todo init these in the module automatically
	UserController *user_controller = new CCMSUserController();
	UserModel *user_model = new UserModel();
	//user_manager->set_path("./users/");

	Settings *settings = new Settings(true);
	//settings->parse_file("settings.json");

	FileCache *file_cache = new FileCache(true);
	file_cache->wwwroot = "./www";
	file_cache->wwwroot_refresh_cache();

	DatabaseManager *dbm = new DatabaseManager();

	create_databases();

	DWebApplication *app = new MAIN_CLASS();

	app->load_settings();
	app->setup_routes();
	app->setup_middleware();

	app->add_listener("127.0.0.1", 8080);
	LOG_INFO << "Server running on 127.0.0.1:8080";

	if (!migrate) {
		session_manager->load_sessions();

		printf("Initialized!\n");
		app->run();
	} else {
		printf("Running migrations.\n");

		session_manager->migrate();
		user_model->migrate();

		app->migrate();
	}

	delete app;
	delete dbm;
	delete file_cache;
	delete settings;
	delete user_controller;
	delete user_model;
	delete session_manager;

	return 0;
}