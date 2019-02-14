#!/usr/bin/env zsh

if [[ -d /home/worker/local/bin ]] {
    export PATH="/home/worker/local/bin:$PATH"
}
if [[ -d ~/local/bin ]] {
    chmod +x $HOME/local/bin/*
    export PATH="$HOME/local/bin:$PATH"
}

