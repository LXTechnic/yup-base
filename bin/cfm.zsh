#!/usr/bin/env zsh

local __DIR__=$(cd `dirname $0`; pwd)
php ${__DIR__}/../cfm/cli/cfm $*
