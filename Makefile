MKDOCS ?= mkdocs
PHP ?= php

.PHONY : docs
docs:
	${MKDOCS} build
	${PHP} phpdoc
