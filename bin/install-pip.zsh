#!/usr/bin/env zsh

rm /tmp/get-pip.py
curl https://bootstrap.pypa.io/get-pip.py -o /tmp/get-pip.py
python3 /tmp/get-pip.py