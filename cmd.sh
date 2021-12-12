#!/bin/bash

usage() {
  cat 1>&2 <<EOF
laradock app handler
USAGE:
    sh app.sh [-h] run|stop|test|watch|bash
POSITIONAL ARGUMENTS:
    up                        docker-compose up nginx mysql
    stop                      docker-compose stop
    test                      docker-compose exec workspace php artisan test
    watch                     docker-compose exec workspace npm run watch
    bash                      docker-compose exec workspace bash
FLAGS:
    -h, --help                Prints help information
EOF
}

die() {
  err_msg="$1"
  echo "$err_msg" >&2
  exit 1
}

handle() {
  while test $# -gt 0; do
    key="$1"
    case "$key" in
    up)
      exec docker-compose up nginx mysql
      exit 0
      ;;
    stop)
      exec docker-compose stop
      exit 0
      ;;
    test)
      exec docker-compose exec workspace /var/www/vendor/bin/phpunit --printer=Codedungeon\\PHPUnitPrettyResultPrinter\\Printer
      exit 0
      ;;
    watch)
      exec docker-compose exec workspace npm run watch
      exit 0
      ;;
    bash)
      exec docker-compose exec workspace bash
      exit 0
      ;;
    -h | --help)
      usage
      exit 0
      ;;
    *)
      die "Got an unexpected argument: $1"
      ;;
    esac
    shift
  done
}

main() {
  cd .laradock
  handle "$@"
  exit 0
}

main "$@" || exit 1
