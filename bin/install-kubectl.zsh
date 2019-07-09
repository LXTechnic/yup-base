#!/usr/bin/env zsh

local name=1.13.8
if [[ $1 ]] {
  name=$1
}

wget -c https://storage.googleapis.com/kubernetes-release/release/v${name}/bin/linux/amd64/kubectl -O /tmp/kubectl

touch-local

rm -f ~/local/bin/kubectl
mv /tmp/kubectl ~/local/bin
chmod +x ~/local/bin/kubectl