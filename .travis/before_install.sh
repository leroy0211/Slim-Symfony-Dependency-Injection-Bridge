#!/usr/bin/env bash

set -ev

composer global require friendsofphp/php-cs-fixer

export PATH="${PATH}:${HOME}/.composer/vendor/bin"