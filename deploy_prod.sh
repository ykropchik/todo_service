#!/bin/bash

git push

ssh root@138.197.185.17 "cd /var/www/todo_service_prod/server; bash build.sh"
