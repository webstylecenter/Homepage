#!/bin/bash
db_reset() {
  php artisan db:wipe && php artisan migrate:install && php artisan migrate && php artisan db:seed
}

psr_check() {
  composer cs-check
}

psr_fix() {
  composer cs-fix
}

cache_clear() {
  composer dump-autoload && php artisan cache:clear
}

phpunit() {
    ./vendor/bin/phpunit --verbose -c phpunit.xml
}


if [ "$1" == "reset" ]
then
  echo "Starts database reset"
  db_reset
elif [ "$1" == "check" ]
then
  psr_check
elif [ "$1" == "fix" ]
then
  psr_fix
elif [ "$1" == "clear" ]
then
  cache_clear
elif [ "$1" == "test" ]
then
  phpunit
else
  printf "\n\nPlease select one of the available commands:\n- reset: Resets the database to db-seed state\n- check: Runs PSR check\n- fix: Runs PSR automatic fixes\n- clear: Recreated dump autoload and resets cache\n- test: Run PHP Unit tests\n\n"
fi
