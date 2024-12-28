setlocal
cd %~dp0
call server-env
start "Debug Server" docker compose --file docker-compose-debug.yml up --build
endlocal