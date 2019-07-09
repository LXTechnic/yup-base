#!/usr/bin/env zsh

local name=v1.13.8

wget -c https://storage.googleapis.com/kubernetes-release/release/${name}/bin/linux/amd64/kubectl -O /tmp/kubectl

touch-local

rm -f ~/local/bin/kubectl
mv /tmp/kubectl ~/local/bin
chmod +x ~/local/bin/kubectl