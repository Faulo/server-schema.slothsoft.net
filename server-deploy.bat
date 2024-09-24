setlocal
FOR /F "tokens=*" %%i in ('type .env') do SET %%i
docker stack deploy %STACK_NAME% --detach=true --prune --resolve-image=always -c=docker-compose.yml
docker service update --force %STACK_NAME%_%STACK_NAME%
endlocal