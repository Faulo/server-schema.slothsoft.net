setlocal
FOR /F "tokens=*" %%i in ('type .env') do SET %%i
call composer-%PHP_VERSION% update
call docker stack deploy %STACK_NAME% --detach=true --prune --resolve-image=always -c=docker-compose.yml
call docker service update --force %STACK_NAME%_%STACK_NAME%
endlocal