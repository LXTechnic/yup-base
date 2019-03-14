#!/usr/bin/env zsh

local name=node-v10.15.3-linux-x64

wget -c https://nodejs.org/dist/latest-v10.x/${name}.tar.xz -P /tmp/
tar xJf /tmp/${name}.tar.xz -C /tmp
chown worker:worker -R /tmp/${name}
sudo su - worker -c "mkdir ~/local;mv /tmp/${name}/* ~/local/"