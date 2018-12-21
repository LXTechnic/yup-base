#!/usr/bin/env zsh
main() {
    local br=`git -C $1 branch | grep '*'`;
    br=${br/* /}
    git -C $1 fetch --all
    git -C $1 reset --hard origin/${br}
}

main $*
