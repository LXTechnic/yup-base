#!/usr/bin/env zsh
main() {
    local target=$1
    [[ ! $target ]] && target='.'
    local br=`git -C $target branch | grep '*'`;
    br=${br/* /}
    git -C $target fetch --all
    git -C $target reset --hard origin/${br}
}

main $*
