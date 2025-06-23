setlocal
cd %~dp0
FOR /F "tokens=*" %%i IN ('type ..\.env') DO SET %%i
start "%SERVER_NAME% (localhost)" podman compose --project-name %STACK_NAME% --file podman-compose-debug.yml up --build
endlocal