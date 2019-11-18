FROM nginx:alpine
RUN apk update && apk add --update git php7 php7-fpm php7-json php7-pdo php7-pdo_mysql php7-session curl mariadb-client

COPY nginx.conf /etc/nginx/conf.d/default.conf
COPY entry.sh /home/
RUN chmod +x /home/entry.sh

CMD /home/entry.sh