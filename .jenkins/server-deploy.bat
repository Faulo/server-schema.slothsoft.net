setlocal
cd %~dp0
call server-env
findstr /C:"127.0.0.1 %STACK_NAME%" C:\Windows\System32\drivers\etc\hosts >nul 2>&1
if %ERRORLEVEL% NEQ 0 (
    echo 127.0.0.1 %STACK_NAME% >> C:\Windows\System32\drivers\etc\hosts
)
call composer -d .. update
call docker compose up --detach --build -f docker-compose-windows.yml
endlocal