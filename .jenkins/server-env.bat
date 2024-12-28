FOR /F "tokens=*" %%i IN ('type ..\.env') DO SET %%i
FOR /F "tokens=*" %%i IN ('docker info --format {{.OSType}}') DO SET DOCKER_OS_TYPE=%%i
call docker pull faulo/farah:%PHP_VERSION%
FOR /F "tokens=*" %%i IN ('docker image inspect faulo/farah:%PHP_VERSION% --format={{.Config.WorkingDir}}') DO SET DOCKER_WORKDIR=%%i
