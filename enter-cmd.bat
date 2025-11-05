@FOR /F "tokens=*" %%i IN ('type .env') DO SET %%i
@call cmd.exe