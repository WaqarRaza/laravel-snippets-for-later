#Supervisor Commands

``supervisorctl reread``

``supervisorctl restart all``

# File to set Laravel queue
```text
[program:laravel-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/project/artisan queue:work --tries=3
autostart=true
autorestart=true
user=root
numprocs=3
redirect_stderr=true
stdout_logfile=/var/www/laravel/storage/logs/worker.log
```
#### Number of threads
numprocs=3 

# File to run node
```text
[program:run-socket-server]
directory=/var/www/socket-test
command=node server
autostart=true
autorestart=true
stderr_logfile=/var/www/socket-test/server.log
```