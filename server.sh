#/bin/sh -e
cleanup() {
	#for i in $(jobs -p); do kill -- "$i"; done
	pkill mysqld
	pkill -9 hitch
	pkill php
	exit 0
}
mysqld --user=root &
sleep 10
#quark -h 99.26.104.141 -p 80 &
php -S 99.26.104.141:80 &
hitch --frontend=[99.26.104.141]:443 --backend=[99.26.104.141]:80 /root/kyryl.tk.pem &
trap "cleanup" SIGINT SIGTERM EXIT
wait $(jobs -p)
