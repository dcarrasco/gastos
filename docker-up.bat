docker-machine start default
rem FOR /f "tokens=*" %%i IN ('docker-machine env') DO %%i
docker-compose up -d
