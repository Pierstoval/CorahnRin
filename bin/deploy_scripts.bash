#!/bin/bash

set -e

# bin/ directory
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Project directory
cd ${DIR}/../

export APP_ENV=prod
export APP_DEBUG=0

# Used to dump a new autoloader because classmap will make autoload fail if some new classes are created between deploys
composer dump-autoload --no-dev

composer install --no-dev --no-scripts --prefer-dist --optimize-autoloader --apcu-autoloader --classmap-authoritative --no-progress --no-ansi --no-interaction

php bin/console cache:clear --no-warmup
php bin/console cache:warmup

php bin/console doctrine:migrations:migrate --no-interaction

php bin/console doctrine:schema:validate || echo 'Doctrine schema not valid, please make sure it is correct.'

yarn install

yarn run build
