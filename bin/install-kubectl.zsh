#!/usr/bin/env zsh

local name=v1.13.8

wget -c https://storage.googleapis.com/kubernetes-release/release/v1.13.8/bin/linux/amd64/kubectl -O /tmp/kubectl

touch_local

mv /tmp/kubectl ~/loca/bin
chmod +x ~/local/bin/kubectl