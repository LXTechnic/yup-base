#!/usr/bin/env zsh

# auditbeat, filebeat, heartbeat, metricbeat
main() {
    dpkg -i $1
}

main $*
