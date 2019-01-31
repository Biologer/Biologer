#!/bin/bash

dir="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
queue="$dir/queue.pid"
run=false

if [ -e $queue ]; then
    pid=$(< $queue)
    result=$(ps -p "$pid" | awk 'NR>1' | awk '{print $1}')
    if [ "$result" == '' ]; then
        run=true
    fi
else
    run=true
fi

if [ $run == true ]; then
    number=$(/usr/local/bin/php $dir/artisan queue:work --tries 3 > /dev/null & echo $!)
    echo "$number" > $queue
fi
