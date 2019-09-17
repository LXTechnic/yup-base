#!/usr/bin/env zsh

local name=node-v12.10.0-linux-x64

sudo rm -rf /tmp/${name}
sudo rm -rf /tmp/${name}.tar.xz

wget -c https://nodejs.org/dist/latest-v12.x/${name}.tar.xz -P /tmp/
tar xJf /tmp/${name}.tar.xz -C /tmp
sudo chown root:root -R /tmp/${name}
sudo cp -rf /tmp/${name}/* /usr/local/

echo > ~/.npmrc
npm config set registry https://registry.npm.taobao.org