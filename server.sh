#/bin/sh -e
cleanup() {
	#for i in $(jobs -p); do kill -- "$i"; done
	pkill mariadbd
	pkill -9 hitch
	pkill php
	exit 0
}
mariadbd --user=root &
sleep 10
#quark -h $IP -p 80 &
IP=99.72.58.241
php -S $IP:80 &
hitch --frontend=[$IP]:443 --backend=[$IP]:80 /root/kyryl.me.pem &
trap "cleanup" SIGINT SIGTERM
wait $(jobs -p)
