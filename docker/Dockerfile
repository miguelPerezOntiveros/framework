FROM nginx:alpine
RUN apk update && apk add --update git php7 php7-fpm php7-json php7-pdo php7-pdo_mysql php7-session mariadb openrc curl mariadb-client

COPY nginx.conf /etc/nginx/conf.d/default.conf
COPY *.sh /home/
RUN chmod +x /home/*.sh && /home/setup.sh

CMD /home/entry.sh