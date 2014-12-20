.DEFAULT: help

# Find pharcc installation path
PHARCC_GLOBAL = $(shell pharcc --version > /dev/null 2>&1 && echo 1 || echo 0)
PHARCC_LOCAL = $(shell php pharcc.phar --version > /dev/null 2>&1 && echo 1 || echo 0)
ifeq ($(PHARCC_LOCAL), 1)
	PHARCC_COMMAND = "php pharcc.phar build"
else ifeq ($(PHARCC_GLOBAL), 1)
	PHARCC_COMMAND = "pharcc build"
else
	PHARCC_COMMAND = ""
endif

build: clean check test phar

check:
	@vendor/bin/phpcs --standard=psr2  bin/projectlint src/ tests/

clean:
	@-rm -rf build/coverage
	@-rm -f projectlint.phar

help:
	@echo ""
	@echo "ProjectLint Helper"
	@echo "=================="
	@echo ""
	@echo "Please use 'make <target>' where <target> is one of:"
	@echo ""
	@echo "  build     Shortcut for 'clean', 'check', 'test' and 'phar' targets in this order"
	@echo "  check     Checks compliance with Coding Standard"
	@echo "  clean     Deletes all development and build artifacts"
	@echo "  init      Initializes project"
	@echo "  phar      Generates a phar file for ProjectLint"
	@echo "  sign      Generates a signed phar file for ProjectLint"
	@echo "  test      Runs unit tests"
	@echo "  test-cov  Runs unit tests with code coverage enabled"
	@echo "  help      Display this message"
	@echo ""

init:
	@composer install

phar:
	@if [ $(PHARCC_GLOBAL) -eq 1 ]; then \
		echo "Found phparcc command installed on this system. Will use it to build projectlint.phar."; \
	elif [ $(PHARCC_LOCAL) -eq 1 ]; then \
		echo "Found phparcc.phar in project directory. Will use it to build projectlint.phar."; \
	else \
		echo >&2 "Building Phar file requires pharcc but it's not installed."; \
		echo >&2 "Aborting."; \
		exit 1; \
	fi

	@"$(PHARCC_COMMAND)"
	@-rm -rf build/output
	@mkdir -p build/output
	@mv projectlint.phar build/output

sign: phar
	@-rm -f build/output/projectlint.phar.asc
	@gpg --armor --detach-sig build/output/projectlint.phar

test:
	@vendor/bin/phpunit

test-cov:
	@-rm -rf build/coverage
	@vendor/bin/phpunit --coverage-html=build/coverage
