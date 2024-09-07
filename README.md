# 🔐 Generador de Contraseñas 💻

Este proyecto es una **aplicación web** desarrollada con **Laravel** que permite a los usuarios generar contraseñas seguras de manera personalizada y mantener un historial de las contraseñas generadas durante la sesión.

## 🌟 Características Principales

-   **Generación Personalizable**: Crea contraseñas con longitud ajustable según tus necesidades.
-   **Opciones de Caracteres**: Incluye caracteres especiales, números, letras mayúsculas y minúsculas.
-   **Visualización Inmediata**: Muestra la contraseña generada al instante para que puedas copiarla fácilmente.
-   **Historial de Contraseñas**: Guarda un historial de contraseñas generadas en la sesión del usuario para futuras referencias.
-   **Interfaz Intuitiva**: Diseñada con **Tailwind CSS** para una experiencia de usuario agradable y responsive.

## 🛠️ Tecnologías Utilizadas

-   **Laravel 11** 🚀: Framework PHP para desarrollar la aplicación.
-   **PHP 8.2** 💻: Lenguaje de programación backend.
-   **Tailwind CSS** 🎨: Framework de diseño para una interfaz moderna y adaptativa.
-   **Almacenamiento en Sesión** 🗃️: Para gestionar el historial de contraseñas generadas.

## 🏗️ Funcionamiento

1. **Página Principal** 🏠: Accede a un formulario donde puedes ajustar las opciones de la contraseña:

    - Longitud
    - Inclusión de caracteres especiales
    - Inclusión de números
    - Inclusión de letras mayúsculas
    - Inclusión de letras minúsculas

2. **Generación de Contraseña** 🔄: Envía el formulario y la aplicación generará una contraseña basada en los criterios seleccionados utilizando el método `generatePassword` en `PasswordController`.

3. **Visualización del Resultado** 👀: La contraseña generada se muestra en una nueva página para que puedas copiarla fácilmente.

4. **Almacenamiento en Sesión** 💾: La contraseña generada se guarda en la sesión del usuario mediante el método `savePassword` en `PasswordController`.

5. **Historial de Contraseñas** 📜: Las contraseñas generadas se almacenan en la sesión. Los usuarios pueden acceder a este historial y eliminar contraseñas individuales si lo desean.

6. **Navegación** 🔍: Desde cualquier página, los usuarios pueden generar una nueva contraseña o ver el historial de contraseñas generadas.

## 🗂️ Estructura del Proyecto

-   `app/Http/Controllers/PasswordController.php`: Contiene la lógica para generar contraseñas y manejar el historial.
-   `resources/views/`: Almacena las vistas Blade para la interfaz de usuario (form.blade.php, result.blade.php, history.blade.php).
-   `routes/web.php`: Define las rutas de la aplicación.

## 🚀 Instalación y Configuración

1. **Clona el Repositorio**:

    ```bash
    git clone https://github.com/tu-usuario/generador-contrasenas.git
    cd generador-contrasenas
    ```

2. **Instala las Dependencias**:

    ```bash
    composer install
    npm install
    ```

3. **Copia el Archivo de Configuración**:

    ```bash
    cp .env.example .env
    ```

4. **Genera la Clave de la Aplicación**:

    ```bash
    php artisan key:generate
    ```

5. **Compila los Assets**:

    ```bash
    npm run dev
    ```

6. **Inicia el Servidor de Desarrollo**:
    ```bash
    php artisan serve
    ```

Ahora puedes acceder a la aplicación en [http://localhost:8000](http://localhost:8000).

## 📝 Uso

1. Accede a la página principal y configura las opciones de la contraseña.
2. Haz clic en **"Generar Contraseña"**.
3. Copia la contraseña generada o genera una nueva.
4. Accede al historial de contraseñas desde el enlace proporcionado.

---

¡Disfruta generando contraseñas seguras y mantén tu información protegida! 🔒
