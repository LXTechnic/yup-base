#!/usr/bin/env zsh
main() {
    git add * && git stash && git stash drop
}

main $*
