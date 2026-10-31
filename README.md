# Práctica 7: Web App con Dockerfile y Docker Compose

Aplicación web "¡Hola Mundo!" construida en **PHP con Apache** que se conecta a una base de datos **MySQL 8.0**, orquestada mediante **Dockerfile** y **Docker Compose**.

## Estructura del Proyecto

```text
practica-webapp-docker/
├── Dockerfile              # Construcción personalizada de la imagen PHP con extensiones MySQL
├── docker-compose.yml      # Definición y orquestación de servicios (app y bd)
├── .dockerignore
├── .gitignore
├── README.md
└── src/
    └── index.php           # Código fuente de la aplicación web y prueba de conexión a MySQL
```

## Servicios Configurados

1. **`app` (Servidor Web y Aplicación)**:
   - Basado en una imagen construida desde `Dockerfile` (`php:8.2-apache` + `mysqli` + `pdo_mysql`).
   - Mapeo de volumen local `./src` a `/var/www/html` para permitir recarga en vivo de cambios.
   - Expuesto en el puerto `8080` del host (`http://localhost:8080`).
   - Depende del servicio `db` mediante condición de salud (`service_healthy`).

2. **`db` (Base de Datos MySQL)**:
   - Imagen oficial `mysql:8.0`.
   - Inicializa la base de datos `practica_db` y el usuario `practica_user`.
   - Puerto `3306` expuesto.
   - Volumen persistente nombrado `mysql_data` para resguardar los datos.
   - Chequeo de salud (`healthcheck`) para garantizar que MySQL esté listo antes de conectar la aplicación.

## Instrucciones de Ejecución

1. **Construir y levantar los contenedores:**
   ```bash
   docker compose up -d --build
   ```

2. **Probar la aplicación:**
   Abre tu navegador e ingresa a:
   [http://localhost:8080](http://localhost:8080)

   Verás el mensaje **"¡Hola Mundo!"**, el estado de conexión exitosa a MySQL, la versión de MySQL y el contador de visitas registradas en la base de datos.

3. **Detener los servicios:**
   ```bash
   docker compose down
   ```
