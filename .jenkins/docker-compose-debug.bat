setlocal
cd %~dp0
call load-env
start "%SERVER_NAME% (localhost)" docker compose --file docker-compose-debug.yml up --build
endlocal