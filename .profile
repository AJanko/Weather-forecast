dbuild() {
    docker-compose build web
}

dstart() {
    docker-compose up -d web
}

dstop() {
    docker-compose down --remove-orphans
}

getip() {
    ifconfig | grep "inet " | grep -Fv 127.0.0.1 | awk '{print $2}'
}

wTest() {
    php api/bin/console bq:test
}

wTrain () {
    php api/bin/console ow:train $*
}
