tree -I j* -L 1 -d -H . $1 | sed -e '/<hr>/,+7d' | sed "s|\\./|\\$1/|" > "${1}/index.html"
