setlocal
cd %~dp0
call server-env
call docker stack remove %STACK_NAME%
endlocal