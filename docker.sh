#!/bin/bash

CYAN='\033[0;36m'
RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m' # No Color
APP="vending-machine-app"
DOCKER_FILE="docker-compose.yml"

# prints colored text
print() {
    if [ "$2" == "info" ] ; then
        COLOR="96m"
    elif [ "$2" == "success" ] ; then
        COLOR="92m"
    elif [ "$2" == "warning" ] ; then
        COLOR="93m"
    elif [ "$2" == "danger" ] ; then
        COLOR="91m"
    else #default color
        COLOR="0m"
    fi

    STARTCOLOR="\e[$COLOR"
    ENDCOLOR="\e[0m"

    printf "$STARTCOLOR%b$ENDCOLOR" "$1"
}

usage() {
	print "usage: docker <subcommand>\n\n" "danger"
	echo "Available subcommands are:"

	print "up        " "info"
	echo "Start services (use --force to remove existing containers)"

	print "stop      " "info"
	echo "Stop services"

	print "exe       " "info"
    echo "Execute container bash"

	print "sh, bash " "info"
	echo "Up and run '$APP' container bash (use --force to remove existing containers)"

	echo
	echo "Try 'docker <subcommand> help' for details."
}

if [[ $# -eq 0 ]] ; then
    usage
    exit 1
fi

has_force_flag() {
    for arg in "$@"; do
        if [ "$arg" == "--force" ]; then
            return 0
        fi
    done
    return 1
}

start_services() {
    if has_force_flag "$@"; then
        print "Removing existing containers...\n" "warning"
        docker rm $(docker ps -aq) --force
        print "." "warning"
        sleep 1
        print "." "warning"
        sleep 1
        print "." "warning"
        sleep 1
        print "." "warning"
        sleep 1
        print ".\n" "warning"
        sleep 1
    fi

    print "Starting services...\n" "info"
    docker compose -f $DOCKER_FILE up -d || die "Could not run services."
}

if [ "$1" == "up" ] ; then
    start_services "$@"

    exit 0;
fi

if [ "$1" == "exe"  ] ; then
    print "Executing container bash...\n" "info"

    docker compose exec $APP bash || die "Could not execute app"

    exit 0;
fi

if [ "$1" == "sh" -o "$1" == "bash" ] ; then
    print "Starting Services and executing application bash...\n" "info"

    start_services "$@"

    docker compose -f $DOCKER_FILE exec $APP bash || die "Could not execute app"

    exit 0;
fi

if [ "$1" == "stop" -o "$1" == "s" ] ; then
    print "Stopping services...\n" "info"

    docker compose -f $DOCKER_FILE stop

    exit 0;
fi

if [ "$1" == "down" ] ; then
    print "Removing services...\n" "info"

    docker compose -f $DOCKER_FILE down

    exit 0;
fi

if [ "$1" == "mongo" ] ; then
    print "executing mongo bash...\n" "info"

    docker compose -f $DOCKER_FILE  exec $MONGO mongosh -u "root" -p "root"

    exit 0;
fi
