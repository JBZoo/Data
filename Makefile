#
# JBZoo Data
#
# This file is part of the JBZoo CCK package.
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


install: ##@Project Install all 3rd party dependencies
	$(call title,"Install all 3rd party dependencies")
	@composer install --optimize-autoloader


test-all: ##@Project Run all project tests at once
	@make test-composer
	@make codestyle
	@make test
