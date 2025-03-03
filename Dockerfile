FROM ubuntu:14.04

CMD php artisan serve --host=0.0.0.0 --port=8181

EXPOSE 8181
