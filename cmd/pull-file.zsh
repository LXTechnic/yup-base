#!/usr/bin/env zsh
pull-file() {
    local url=$1
    local file=$2
    local check=$3
    if [[ $check && -e $file && $(file.include $file $check) == 'yes' ]] {
        return
    }
    echo "$(curl -fsSL $url)" >$file
}
