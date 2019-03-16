#!/usr/bin/env bash

set -ev

mkdir --parents "${HOME}/bin"

wget "http://cs.sensiolabs.org/download/php-cs-fixer-v2.phar" --output-document="${HOME}/bin/php-cs-fixer"
chmod u+x "${HOME}/bin/php-cs-fixer"
