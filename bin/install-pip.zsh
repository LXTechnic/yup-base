#!/usr/bin/env zsh

# if [[ -e /tmp/get-pip.py ]] {
#   rm /tmp/get-pip.py
# }
wget -c https://bootstrap.pypa.io/get-pip.py -O /tmp/get-pip.py -o /tmp/download-get-pip.log
python3 /tmp/get-pip.py
rm /tmp/get-pip.py
