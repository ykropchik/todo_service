#!/bin/bash

git push

ssh root@138.197.185.17 'bash /var/www/todo_service_prod/build.sh'
