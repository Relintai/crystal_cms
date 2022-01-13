#ifndef PAGE_H
#define PAGE_H

#include "core/string.h"

#include "core/resource.h"

class Page : public Resource {
	RCPP_OBJECT(Page, Resource);

public:
	int rank_id;
	String name;
	String url;
	int sort_order;
	int permissions;

	Page();
	~Page();
};

#endif