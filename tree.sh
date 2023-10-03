tree -I j* -L 1 -d -H . ./apps | sed -e '/<hr>/,+7d' | sed 's|\./|\./apps/|' > ./apps/index.html
