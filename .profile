dbuild() {
    docker-compose build web
}

dstart() {
    docker-compose up -d web
}

dstop() {
    docker-compose down --remove-orphans
}
