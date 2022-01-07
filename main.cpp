#include <string.h>
#include <iostream>
#include <string>

#include "core/file_cache.h"
#include "core/http/web_root.h"

#include "app/ccms_application.h"

#include "database/db_init.h"

#include "core/settings/settings.h"
#include "core/settings/db_settings.h"

#include "core/http/session_manager.h"

#include "modules/drogon/drogon_web_server.h"

// Backends
#include "backends/hash_hashlib/setup.h"

#include "app/ccms_user_controller.h"
#include "modules/users/user.h"

#include "core/database/database_manager.h"
#include "platform/platform_initializer.h"

#include "core/os/platform.h"

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

int main(int argc, char **argv, char **envp) {
	PlatformInitializer::allocate_all();
	PlatformInitializer::arg_setup(argc, argv, envp);

	initialize_backends();

	::SessionManager *session_manager = new ::SessionManager();

	// todo init these in the module automatically
	UserController *user_controller = new CCMSUserController();
	// user_manager->set_path("./users/");

	DBSettings *settings = new DBSettings(true);
	// settings->parse_file("settings.json");

	FileCache *file_cache = new FileCache(true);
	file_cache->wwwroot = "./www";
	file_cache->wwwroot_refresh_cache();

	DatabaseManager *dbm = new DatabaseManager();

	create_databases();

	DrogonWebServer *app = new DrogonWebServer();
	CCMSApplication *app_root = new CCMSApplication();

	app->set_root(app_root);

	app_root->load_settings();
	app_root->setup_routes();
	app_root->setup_middleware();

	app->add_listener("127.0.0.1", 8080);
	LOG_INFO << "Server running on 127.0.0.1:8080";

	bool migrate = Platform::get_singleton()->arg_parser.has_arg("-m");

	if (!migrate) {
		settings->load();
		session_manager->load_sessions();

		printf("Initialized!\n");
		app->run();
	} else {
		printf("Running migrations.\n");

		settings->migrate();

		session_manager->migrate();
		user_controller->migrate();

		if (Platform::get_singleton()->arg_parser.has_arg("-u")) {
			printf("Creating test users.\n");
			user_controller->create_test_users();
		}

		app_root->migrate();
	}

	delete app;
	delete app_root;
	delete dbm;
	delete file_cache;
	delete settings;
	delete user_controller;
	delete session_manager;

	PlatformInitializer::free_all();

	return 0;
}