setlocal
cd %~dp0
call load-env
start "Debug Server" docker compose --file docker-compose-debug.yml up --build
endlocal