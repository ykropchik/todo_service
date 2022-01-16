#!/bin/bash
#test
#git reset --hard HEAD
git pull

composer install
php bin/console cache:clear
php bin/console cache:warmup
