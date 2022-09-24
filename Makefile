.PHONY : docs
docs:
	mkdocs build
	php7 phpdoc
