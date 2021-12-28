ARG PHP_IMAGE_NAME

FROM $PHP_IMAGE_NAME as php-stateless

FROM nginx:1.19.7-alpine

COPY docker/nginx/conf.d/default.conf.template /etc/nginx/conf.d/default.template
COPY docker/nginx/nginx.conf /etc/nginx/

COPY --from=php-stateless /app/public /app/public

CMD sh -c "envsubst \"`env | awk -F = '{printf \" \\\\$%s\", $1}'`\" < /etc/nginx/conf.d/default.template > /etc/nginx/conf.d/default.conf && nginx -g 'daemon off;'"
