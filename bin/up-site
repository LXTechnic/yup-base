#!/bin/zsh

# if [[ ! $(id -u) = 0 ]] {
# 	echo "!!! need be root, your id -u is: $(id -u)"
# 	exit;
# }

if [[ ! $1 ]] {
    echo "example: up-site <site name>"
    echo "available sites:"
    ls /etc/nginx/sites-available
    exit
}

linkfile="/etc/nginx/sites-enabled/$1"

if [[ ! -f ${linkfile} ]] {
    sudo ln -s /etc/nginx/sites-available/$1 $linkfile
    echo "$1 is enabled"
} else {
    sudo rm $linkfile
    echo "$1 is disabled"
}

sudo service nginx reload