
#include "app/ccms_root.h"
#include "core/os/platform.h"
#include "core/settings/settings.h"
#include "database/database_manager.h"
#include "database_modules/db_settings/db_settings.h"
#include "web/file_cache.h"
#include "web/http/session_manager.h"
#include "web_backends/drogon/drogon_web_server.h"

#include "rcpp_framework.h"

void create_databases() {
	DatabaseManager *dbm = DatabaseManager::get_singleton();

	uint32_t index = dbm->create_database("sqlite");
	Database *db = dbm->databases[index];
	db->connect("database.sqlite");
}

int main(int argc, char **argv, char **envp) {
	RCPPFramework::create_and_init(argc, argv, envp);
	RCPPFramework::get_singleton()->www_root = "./www";

	create_databases();

	DrogonWebServer *app = new DrogonWebServer();
	RCPPFramework::get_singleton()->manage_object(app);

	CCMSRoot *app_root = new CCMSRoot();
	app_root->setup();

	app->set_root(app_root);

	bool migrate = Platform::get_singleton()->arg_parser.has_arg("-m");

	if (!migrate) {

		RCPPFramework::get_singleton()->load();

		RLOG_MSG("Initialized!\n");
		app->add_listener("127.0.0.1", 8080);
		RLOG_MSG("Server running on 127.0.0.1:8080");
		app->run();
	} else {
		RLOG_MSG("Running migrations.\n");

		RCPPFramework::get_singleton()->migrate();

		bool seed_db = Platform::get_singleton()->arg_parser.has_arg("-s");

		if (seed_db) {
			RLOG_MSG("Seeding database.\n");
		}

		app_root->migrate(true, seed_db);
	}

	RCPPFramework::destroy();

	return 0;
}