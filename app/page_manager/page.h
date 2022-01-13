#ifndef PAGE_H
#define PAGE_H

#include "core/string.h"

#include "core/resource.h"

class Page : public Resource {
	RCPP_OBJECT(Page, Resource);

public:
	String name;
	String url;
	int page_type;
	int deleted;

	Page();
	~Page();
};

#endif