#!/usr/bin/env zsh
main() {
    dpkg -i $1
}

main $*
