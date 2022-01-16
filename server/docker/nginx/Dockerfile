# ARG PHP_IMAGE_NAME

FROM php:ykropchik as php-stateless

FROM nginx:1.19.7-alpine

RUN mkdir app

COPY docker/nginx/conf.d/default.conf.template /etc/nginx/conf.d/default.template
COPY docker/nginx/nginx.conf /etc/nginx/

COPY --from=php-stateless /app/public /app/public

CMD sh -c "envsubst \"`env | awk -F = '{printf \" \\\\$%s\", $1}'`\" < /etc/nginx/conf.d/default.template > /etc/nginx/conf.d/default.conf && nginx -g 'daemon off;'"
