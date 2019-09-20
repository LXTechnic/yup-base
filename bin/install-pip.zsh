#!/usr/bin/env zsh

if [[ -e /tmp/get-pip.py ]] {
  rm /tmp/get-pip.py
}
curl https://bootstrap.pypa.io/get-pip.py -o /tmp/get-pip.py
python3 /tmp/get-pip.py
rm /tmp/get-pip.py
