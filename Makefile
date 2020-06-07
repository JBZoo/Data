#
# JBZoo Toolbox - Data
#
# This file is part of the JBZoo Toolbox project.
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
# @package    Data
# @license    MIT
# @copyright  Copyright (C) JBZoo.com, All rights reserved.
# @link       https://github.com/JBZoo/Data
#

ifneq (, $(wildcard ./vendor/jbzoo/codestyle/src/init.Makefile))
    include ./vendor/jbzoo/codestyle/src/init.Makefile
endif

update: ##@Project Install/Update all 3rd party dependencies
	$(call title,"Install/Update all 3rd party dependencies")
	@echo "Composer flags: $(JBZOO_COMPOSER_UPDATE_FLAGS)"
	@composer update $(JBZOO_COMPOSER_UPDATE_FLAGS)


test-all: ##@Project Run all project tests at once
	@make test
	@make codestyle
	@if [ $(XDEBUG_OFF) = "yes" ]; then                     \
       make test-performance;                               \
    else                                                    \
      echo "Performance test works only if XDEBUG_OFF=yes"; \
    fi;


XDEBUG_OFF ?= no
test-performance: ##@Project Run benchmarks and performance tests
	$(call title,"Run benchmark tests")
	@php `pwd`/vendor/bin/phpbench run         \
        --tag=jbzoo                            \
        --store                                \
        --warmup=2                             \
        --stop-on-error                        \
        -vvv
	$(call title,"Build reports - CLI")
	@php `pwd`/vendor/bin/phpbench report      \
        --uuid=tag:jbzoo                       \
        --report=jbzoo-time                    \
        --precision=2                          \
        -vvv
	$(call title,"Build reports - Env")
	@php `pwd`/vendor/bin/phpbench report      \
        --uuid=tag:jbzoo                       \
        --report=jbzoo-env                     \
        -vvv
	$(call title,"Build reports - HTML")
	@php `pwd`/vendor/bin/phpbench report      \
        --uuid=tag:jbzoo                       \
        --report=jbzoo-time                    \
        --output=jbzoo-html-time               \
        --mode=time                            \
        -vvv
	$(call title,"Build reports - Markdown")
	@php `pwd`/vendor/bin/phpbench report      \
        --uuid=tag:jbzoo                       \
        --report=jbzoo-time                    \
        --output=jbzoo-md                      \
        --precision=2                          \
        -vvv
	@cat `pwd`/build/phpbench.md
