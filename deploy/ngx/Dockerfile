FROM nginx:latest

ENV NGINX_ROOT /usr/src/app/public
ENV NGINX_FPM_HOST dara-app
ENV NGINX_FPM_PORT 9000

RUN rm -f /etc/nginx/conf.d/default.conf
RUN rm -f /etc/nginx/nginx.conf

COPY  nginx.conf /etc/nginx/nginx.conf
COPY  fpm-template.conf /etc/nginx/fpm.tmpl
COPY  --chmod=755 entrypoint.sh /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]
