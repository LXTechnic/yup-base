#!/usr/bin/env zsh
main() {
    local file=${1##*/}
    wget -c $1 -O /tmp/${file}
    sudo dpkg -i /tmp/${file}
    rm /tmp/${file}
}

main $*
