#!/bin/bash
#add this script to the crontab (via crontab -e): 0 * * * * bash sendBeacon.sh
deviceName="DEVICENAME"

## CPU TEMPERATURE READOUT FOR >>RASPBERRY PIs<< !
raspiTemp=$(/opt/vc/bin/vcgencmd measure_temp)
cpuTemp=${raspiTemp:5:4}

## CPU TEMPERATURE READOUT FOR >>linux computers (eg. ubuntu)<< !
#cpuTemp=$(sensors | grep -oP 'Package id 0: .*?\+\K[0-9.]+')

privateip=$(ip addr | grep 'state UP' -A2 | tail -n1 | awk '{print $2}' | cut -f1  -d'/')
theuptime=$(cat /proc/stat | grep btime | awk '{ print $2 }')
payload="" ## if you wand to send additional data e.g.: $(python ~/Adafruit_Python_DHT/examples/dht22.py)

curl -G "https://yourserver.com/beacon/hey.php" \
    --data whois=$deviceName \
    --data cputemp="$cpuTemp" \
    --data privateip="$privateip" \
    --data uptime="$theuptime" \
    --data-urlencode payload="$payload";