#!/bin/bash

dir="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
queue="$dir/queue.pid"


if [ -e $queue ]; then
    pid=$(< $queue)
    result=$(ps -p "$pid" | awk 'NR>1' | awk '{print $1}')
    if [ "$result" != '' ]; then
        echo "killing $pid"
        kill -9 "$pid"
    fi
fi
