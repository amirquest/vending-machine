#!/bin/bash

CYAN='\033[0;36m'
RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m' # No Color
FEATURE_PREFIX="Feature/TEC-"
BUGFIX_PREFIX="Bugfix/TEC-"
HOTFIX_PREFIX="Hotfix/TEC-"
RELEASE_PREFIX="RELEASE/VERSION-"

DEVELOP_BRANCH="develop"
MASTER_BRANCH="master"

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
	print "usage: git flow <subcommand>\n\n" "danger"
	echo "Available subcommands are:"
	print " feature, f " "info"
	echo "Manage your feature branches."
	print " bugfix, b  " "info"
	echo "Manage your bugfix branches."
	print " hotfix, h  " "info"
	echo "Manage your hotfix branches."
	echo
	print " release, r  " "info"
	echo "Manage your release branches."
	echo
	echo "Try 'git flow <subcommand> help' for details."
}

if [[ $# -eq 0 ]] ; then
    usage
    exit 1
fi

if [ "$1" == "feature"  -o "$1" == "f" ] ; then
  BRANCH_NAME="$2";
  if [ -z "$BRANCH_NAME" ]; then
      git branch;
      exit 0;
  fi
    print "Creating feature branch...\n" "info"

    BRANCH=$FEATURE_PREFIX$BRANCH_NAME

    echo

    git checkout "$DEVELOP_BRANCH" && git pull -r origin "$DEVELOP_BRANCH"

    git checkout -b "$BRANCH" || die "Could not create feature branch '$BRANCH'."

    echo
    print "Summary of actions:\n" "info"
    echo "- A new remote tracking branch '$BRANCH' was created"
    echo "- You are now on branch '$BRANCH'"
    echo
    exit 0;
fi

if [ "$1" == "bugfix" -o "$1" == "b" ] ; then
  BRANCH_NAME="$2";
  if [ -z "$BRANCH_NAME" ]; then
      git branch;
      exit 0;
  fi
    print "Creating bugfix branch...\n" "info"

    BRANCH=$BUGFIX_PREFIX$BRANCH_NAME

    echo

    git checkout "$DEVELOP_BRANCH" && git pull -r origin "$DEVELOP_BRANCH"

    git checkout -b "$BRANCH" || die "Could not create bugfix branch '$BRANCH'."

    echo
    print "Summary of actions:\n" "info"
    echo "- A new remote tracking branch '$BRANCH' was created"
    echo "- You are now on branch '$BRANCH'"
    echo
fi

if [ "$1" == "hotfix" -o "$1" == "h" ] ; then
  BRANCH_NAME="$2";
  if [ -z "$BRANCH_NAME" ]; then
      git branch;
      exit 0;
  fi
    print "Creating hotfix branch...\n" "info"

    BRANCH=$HOTFIX_PREFIX$BRANCH_NAME

    echo

    git checkout "$MASTER_BRANCH" && git pull -r origin "$MASTER_BRANCH"

    git checkout -b "$BRANCH" || die "Could not create hotfix branch '$BRANCH'."

    echo
    print "Summary of actions:\n" "info"
    echo "- A new remote tracking branch '$BRANCH' was created"
    echo "- You are now on branch '$BRANCH'"
    echo
fi

if [ "$1" == "release" -o "$1" == "r" ] ; then
  VERSION="$2";
  if [ -z "$VERSION" ]; then
      git branch;
      exit 0;
  fi
    print "Creating release branch...\n" "info"

    BRANCH=$RELEASE_PREFIX$VERSION

    echo

    git checkout "$MASTER_BRANCH" && git pull -r origin "$MASTER_BRANCH"

    git checkout -b "$BRANCH" || die "Could not create hotfix branch '$BRANCH'."

    echo
    print "Summary of actions:\n" "info"
    echo "- A new remote tracking branch '$BRANCH' was created"
    echo "- You are now on branch '$BRANCH'"
    echo
fi
