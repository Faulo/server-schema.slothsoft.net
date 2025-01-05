setlocal
cd %~dp0
call load-env
call docker stack remove %STACK_NAME%
endlocal