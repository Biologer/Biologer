#!/bin/bash

dir="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
queue="$dir/queue.pid"


if [ -e $queue ]; then
    pid=$(< $queue)
    result=$(ps -p "$pid" --no-heading | awk '{print $1}')
    if [ "$result" == '' ]; then
        kill -9 "$pid"
    fi
fi
