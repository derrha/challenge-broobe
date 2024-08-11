# Desafio Broobe 2024

Este desafio tecnico de una aplicacion web construida con Laravel, utilizando Vite para la gestión de activos y Tailwind CSS para el diseño. A continuación, se detalla cómo configurar y ejecutar el proyecto.

## Requisitos

- PHP 8.1 o superior
- Composer
- Node.js y npm 
- Laragon 

## Instalaciónl

### 1. Clonar el Repositorio

Primero, clona el repositorio desde GitHub dentro del entorno de desarrollo de Laragon:

```bash
git clone https://github.com/derrha/challenge-broobe.git
```

### 2. Ejecutar el proyecto

Para instalar las dependencias de PHP del proyecto, necesitarás ingresar al entorno de desarrollo que provee Laragon. 

1. **Abre laragon:**

2. **Click en "Iniciar Todo".**

2. **Moverse dentro del directorio del proyecto:**
   ```bash
   cd ./challenge-broobe
    ```
3. **Instalar dependencias:**
    ```bash
   composer install
    ```
   ```bash
   npm install
    ```
4. **Compilar archivos:**
    ```bash
   npm run build 
    ```
5. **Crear Base de datos:**
    - Dentro de Laragon abrimos nuestra base de datos por defecto
    - Allí creamos una nueva base de datos llamada "broobe-db"
      
6. **Ejecutar las migraciones junto con los datos de prueba:**

   ```bash
   php artisan migrate:fresh --seed
    ```
7. **Ejecutar el servidor:**

   ```bash
   php artisan serve
    ```
   
## USO
**Acceder a la Aplicación:**
- Abre tu navegador y ve a http://localhost (o el puerto configurado en Laragon) para acceder a la aplicación.
  
**Correr Métricas:**

- Navega a la sección de "Correr Métricas" para ejecutar el proceso de métricas.
  
**Historial de Métricas:**

- Navega a la sección de "Historial" para ver las métricas guardadas.
