setlocal
cd %~dp0
call load-env
start "%SERVER_NAME% (localhost)" docker compose --project-name %STACK_NAME% --file docker-compose-debug.yml up --build --force-recreate
endlocal