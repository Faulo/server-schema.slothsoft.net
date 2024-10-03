setlocal
FOR /F "tokens=*" %%i in ('type .env') do SET %%i
call docker stack remove %STACK_NAME% --detach=false
call docker system prune --all --force
endlocal