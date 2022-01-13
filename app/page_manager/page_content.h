#ifndef PAGE_CONTENT_H
#define PAGE_CONTENT_H

#include "core/containers/vector.h"
#include "core/string.h"

#include "core/reference.h"

class Request;
class FormValidator;

class PageContent : public Reference {
	RCPP_OBJECT(PageContent, Reference);

public:
	PageContent();
	~PageContent();

protected:
};

#endif