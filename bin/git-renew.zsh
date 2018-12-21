#!/usr/bin/env zsh
main() {
    local target=$1
    [[ ! $target ]] && target='.'
    local br=`git -C $1 branch | grep '*'`;
    br=${br/* /}
    git -C $1 fetch --all
    git -C $1 reset --hard origin/${br}
}

main $*
