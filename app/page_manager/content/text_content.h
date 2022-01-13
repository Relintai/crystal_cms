#ifndef TEXT_CONTENT_H
#define TEXT_CONTENT_H

#include "../page_content.h"

#include "core/containers/vector.h"
#include "core/string.h"

class Request;
class FormValidator;

class TextContent : public PageContent {
	RCPP_OBJECT(TextContent, PageContent);

public:
	TextContent();
	~TextContent();

protected:
};

#endif