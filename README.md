# 🔐 Generador de Contraseñas Seguras

Bienvenido al **Generador de Contraseñas Seguras**, una aplicación web desarrollada con **Laravel** que permite a los usuarios generar contraseñas seguras y personalizadas. Además, proporciona un historial de las contraseñas generadas, asociado a la cuenta de cada usuario.

---

### 🗂️ Índice

| Sección                                      | Descripción                                                                 |
|----------------------------------------------|-----------------------------------------------------------------------------|
| [📄 Descripción del Proyecto](#descripción-del-proyecto) | Una visión general del Generador de Contraseñas Seguras.                      |
| [✨ Características Principales](#características-principales) | Funcionalidades clave para la creación y gestión de contraseñas.              |
| [🛠️ Tecnologías Utilizadas](#tecnologías-utilizadas) | Herramientas y tecnologías empleadas en el desarrollo del proyecto.           |
| [⚙️ Instalación](#instalación)              | Guía paso a paso para configurar la aplicación en tu entorno local.          |
| &nbsp;&nbsp;↳ [Prerequisitos](#prerrequisitos)       | Requisitos necesarios para la instalación.                                   |
| &nbsp;&nbsp;↳ [Pasos de Instalación](#pasos-de-instalación) | Procedimiento detallado de instalación.                                       |
| [💻 Uso](#uso)                              | Cómo utilizar la aplicación una vez instalada.                               |

---

## 📌 Descripción del Proyecto

El **Generador de Contraseñas Seguras** es una aplicación que facilita la creación de contraseñas robustas y personalizadas. Su objetivo es ofrecer una herramienta sencilla pero eficaz para la generación y almacenamiento de contraseñas seguras, ayudando a los usuarios a proteger sus cuentas y datos.

> **Objetivo**: Proporcionar una solución segura y fácil de usar para la creación y gestión de contraseñas, adaptada a las necesidades individuales de los usuarios.

---

## ✨ Características Principales

| Funcionalidad                      | Descripción                                                                         |
|------------------------------------|-------------------------------------------------------------------------------------|
| **Generación Personalizable**      | Crea contraseñas con opciones de longitud y tipos de caracteres.                     |
| **Autenticación de Usuarios**      | Sistema de registro e inicio de sesión para gestionar contraseñas de forma segura.   |
| **Historial de Contraseñas**       | Almacena y muestra un historial de las contraseñas generadas por cada usuario.       |
| **Interfaz Intuitiva**             | Diseñada con **Tailwind CSS** para una experiencia de usuario agradable y responsive. |
| **Seguridad de Datos**             | Implementación de protocolos de seguridad para proteger las contraseñas generadas.   |

---

## 🛠️ Tecnologías Utilizadas

| Categoría            | Herramienta                 |
|----------------------|-----------------------------|
| **Framework Web**    | <img src="https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-thebadge&logo=laravel&logoColor=white" alt="Laravel Badge"/>                     |
| **PHP**              | <img src="https://img.shields.io/badge/php-%23777BB4.svg?style=for-thebadge&logo=php&logoColor=white" alt="PHP Badge"/>                               |
| **CSS**              | <img src="https://img.shields.io/badge/tailwindcss-%23426D92.svg?style=for-thebadge&logo=tailwindcss&logoColor=white" alt="TailwindCSS Badge"/>       |
| **Base de Datos**    | <img src="https://img.shields.io/badge/mysql-%2300f0f0.svg?style=for-thebadge&logo=mysql&logoColor=black" alt="MySQL Badge"/>                         |

---

## ⚙️ Instalación

Sigue los pasos a continuación para configurar el proyecto en tu máquina local.

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

2. **Instala las dependencias de Laravel y Node.js:**

    ```bash
    composer install
    npm install
    ```

3. **Configura las variables de entorno:**

    Copia el archivo `.env.example` a `.env` y genera una clave de aplicación:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Configura la base de datos:**

    Edita el archivo `.env` con tus credenciales de base de datos.

5. **Ejecuta las migraciones:**

    ```bash
    php artisan migrate
    ```

6. **Compila los assets del frontend:**

    ```bash
    npm run dev
    ```

7. **Inicia el servidor local:**

    ```bash
    php artisan serve
    ```

8. **Accede a la aplicación:**

    Abre tu navegador y navega a `http://localhost:8000`.

---

## 💻 Uso

1. **Registro/Inicio de Sesión**: Regístrate o inicia sesión para acceder a las funcionalidades.
2. **Generación de Contraseña**: En la página principal, configura las opciones y genera una nueva contraseña.
3. **Visualización y Almacenamiento**: La contraseña generada se muestra y se guarda automáticamente en tu historial.
4. **Historial de Contraseñas**: Visualiza y gestiona las contraseñas generadas anteriormente.
5. **Cierre de Sesión**: Cierra sesión para proteger tu información.

---

## 👩‍💻 Autor

Este proyecto fue creado por [**@AlejandraTech**](https://github.com/AlejandraTech).

---

¡Genera contraseñas seguras y mantén tu información protegida con nuestro Generador de Contraseñas! 🔒
