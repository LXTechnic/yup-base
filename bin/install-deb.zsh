#!/usr/bin/env zsh
main() {
    local file=${1##*/}
    wget -c $1 -O /tmp/${file} -o /var/log/install-deb.log
    sudo dpkg -i /tmp/${file}
    rm /tmp/${file}
    >/var/log/install-deb.log < /dev/null
}

main $*
