#!/usr/bin/env zsh

if [[ ! -e ~/.pip ]] {
  mkdir ~/.pip
  file.write ~/.pip/pip.conf '[global]'
  file.write ~/.pip/pip.conf 'index-url = https://mirrors.aliyun.com/pypi/simple/'
}

# if [[ -e /tmp/get-pip.py ]] {
#   rm /tmp/get-pip.py
# }
wget -c https://bootstrap.pypa.io/get-pip.py -O /tmp/get-pip.py -o /tmp/download-get-pip.log
python /tmp/get-pip.py
rm /tmp/get-pip.py
