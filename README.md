# 🔐 Generador de Contraseñas Seguras

¡Bienvenido al Generador de Contraseñas Seguras! Esta aplicación web está diseñada para ayudar a los usuarios a crear contraseñas robustas y personalizadas, manteniendo un historial de las contraseñas generadas durante la sesión. Nuestro objetivo es simplificar la creación de contraseñas seguras mediante una interfaz intuitiva que permite ajustar los parámetros de generación según tus necesidades.

---

### 🗂️ Índice

| Sección                                      | Descripción                                                                 |
|----------------------------------------------|-----------------------------------------------------------------------------|
| [📄 Descripción del Proyecto](#descripción-del-proyecto) | Una visión general del Generador de Contraseñas Seguras.                    |
| [✨ Características Principales](#características-principales) | Funcionalidades clave que hacen única a la aplicación.                        |
| [🛠️ Tecnologías Utilizadas](#tecnologías-utilizadas) | Herramientas y tecnologías empleadas en el desarrollo del proyecto.           |
| [⚙️ Instalación](#instalación)              | Guía paso a paso para configurar la aplicación en tu entorno local.          |
| &nbsp;&nbsp;↳ [Prerequisitos](#prerrequisitos)       | Requisitos necesarios para la instalación.                                   |
| &nbsp;&nbsp;↳ [Pasos de Instalación](#pasos-de-instalación) | Procedimiento detallado de instalación.                                       |
| [💻 Uso](#uso)                              | Cómo utilizar la aplicación una vez instalada.                               |
| [🗂️ Estructura del Proyecto](#estructura-del-proyecto) | Organización de los archivos y directorios principales.                     |
| [🤝 Contribución](#contribución)             | Cómo contribuir al desarrollo de la aplicación.                              |

---

## 📌 Descripción del Proyecto

El **Generador de Contraseñas Seguras** es una aplicación web desarrollada con Laravel que permite a los usuarios crear contraseñas robustas y personalizadas. Ofrece opciones para ajustar la longitud de la contraseña y los tipos de caracteres a incluir, proporcionando un historial de las contraseñas generadas durante la sesión del usuario.

> **Objetivo**: Empoderar a los usuarios con una herramienta fácil de usar para crear contraseñas seguras, ayudándoles a proteger sus cuentas en línea de manera efectiva.

---

## 🚀 Características Principales

| Funcionalidad                | Descripción                                                                 |
|------------------------------|-----------------------------------------------------------------------------|
| **Generación Personalizable** | Crea contraseñas con longitud ajustable según tus necesidades.               |
| **Opciones de Caracteres**    | Incluye caracteres especiales, números, letras mayúsculas y minúsculas.      |
| **Visualización Inmediata**   | Muestra la contraseña generada al instante para copiarla fácilmente.         |
| **Historial de Contraseñas**  | Guarda un historial de contraseñas generadas en la sesión del usuario.       |
| **Interfaz Intuitiva**        | Diseñada con Tailwind CSS para una experiencia de usuario agradable.         |
| **Seguridad**                 | Implementa prácticas de seguridad para proteger la información del usuario.  |

---

## 🛠️ Tecnologías Utilizadas

| Categoría            | Herramienta                 |
|----------------------|-----------------------------|
| **Framework Web**    | <img src="https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel Badge"/>                     |
| **Lenguaje Backend** | <img src="https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white" alt="PHP Badge"/>                          |
| **CSS Framework**    | <img src="https://img.shields.io/badge/tailwindcss-%2338B2AC.svg?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS Badge"/>                 |
| **Base de Datos**    | <img src="https://img.shields.io/badge/mysql-%2300f.svg?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL Badge"/>                       |

---

## ⚙️ Instalación

Sigue estos pasos para configurar el proyecto en tu máquina local.

### Prerrequisitos

| Requisito     | Versión Recomendada        |
|---------------|----------------------------|
| **PHP**       | v8.2 o superior             |
| **Composer**  | Última versión              |
| **Node.js**   | v14 o superior              |
| **npm**       | Última versión              |
| **MySQL**     | v5.7 o superior             |
| **Git**       | Última versión              |

### Pasos de Instalación

1. **Clona el repositorio:**

```bash
git clone https://github.com/AlejandraTech/generador-contrasenas.git
cd generador-contrasenas
```

2. **Instala las dependencias de PHP:**

```bash
composer install
```

3. **Instala las dependencias de Node.js:**

```bash
npm install
```

4. **Configura las variables de entorno:**

```bash
cp .env.example .env
php artisan key:generate
```

5. **Configura la base de datos:**

Actualiza las credenciales de la base de datos en el archivo `.env`.

6. **Ejecuta las migraciones:**

```bash
php artisan migrate
```

7. **Compila los assets del frontend:**

```bash
npm run dev
```

8. **Inicia el servidor local:**

```bash
php artisan serve
```

9. **Accede a la aplicación:**

Abre tu navegador y navega a `http://localhost:8000`.

---

## 💻 Uso

Una vez que la aplicación esté en funcionamiento, puedes:

1. Acceder a la página principal y configurar las opciones de la contraseña.
2. Ajustar la longitud y los tipos de caracteres a incluir.
3. Hacer clic en "Generar Contraseña".
4. Copiar la contraseña generada o generar una nueva.
5. Acceder al historial de contraseñas desde el enlace proporcionado.

---

## 🗂️ Estructura del Proyecto

- `app/Http/Controllers/PasswordController.php`: Lógica para generar contraseñas y manejar el historial.
- `resources/views/`: Vistas Blade para la interfaz de usuario.
- `routes/web.php`: Definición de las rutas de la aplicación.

---

¡Disfruta generando contraseñas seguras y mantén tu información protegida! 🔒
