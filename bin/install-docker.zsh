#!/usr/bin/env zsh

# 卸载旧版
sudo apt remove docker docker-engine docker.io
# 安装新版
curl -fsSL get.docker.com -o /tmp/get-docker.sh
sudo sh /tmp/get-docker.sh
docker --version

# 加用户进组
sudo useradd -m worker
sudo usermod -a -G docker worker

# 安装docker-compose
# 查看 https://docs.docker.com/compose/install/#install-compose
sudo wget -c "https://github.com/docker/compose/releases/download/1.24.1/docker-compose-$(uname -s)-$(uname -m)" -O /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
docker-compose --version