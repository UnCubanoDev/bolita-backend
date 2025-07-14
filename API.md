# Documentación de la API

Esta API permite gestionar usuarios, apuestas y juegos. A continuación se detallan los endpoints disponibles y cómo utilizarlos.

## Autenticación

### Iniciar sesión

-   **Endpoint:** `POST /login`
-   **Descripción:** Permite a los usuarios iniciar sesión.
-   **Cuerpo de la solicitud:**
    ```json
    {
        "email": "usuario@example.com",
        "password": "password123"
    }
    ```
-   **Respuesta exitosa:**
    ```json
    {
        "token": "1|abcdef1234567890"
    }
    ```

### Registrar un nuevo usuario

-   **Endpoint:** `POST /register`
-   **Descripción:** Permite a los usuarios registrarse en la plataforma.
-   **Cuerpo de la solicitud:**
    ```json
    {
        "name": "Juan Pérez",
        "email": "usuario@example.com",
        "password": "password123",
        "password_confirmation": "password123",
        "referrer_code": "ABC12345" // Opcional
    }
    ```
-   **Respuesta exitosa:**
    ```json
    {
        "token": "1|abcdef1234567890"
    }
    ```

## Usuarios

### Obtener información del usuario

-   **Endpoint:** `GET /user`
-   **Descripción:** Obtiene la información del usuario autenticado.
-   **Autenticación:** Requiere un token de autenticación en el encabezado `Authorization`.

### Actualizar información del usuario

-   **Endpoint:** `PUT /user`
-   **Descripción:** Permite actualizar la información del usuario autenticado.
-   **Cuerpo de la solicitud:**
    ```json
    {
        "name": "Juan Pérez",
        "email": "nuevo_email@example.com",
        "phone": "123456789"
    }
    ```

## Apuestas

### Crear una nueva apuesta

-   **Endpoint:** `POST /bets`
-   **Descripción:** Crea una nueva apuesta para el usuario autenticado.
-   **Cuerpo de la solicitud:**
    ```json
    {
        "game_id": 1, // ID del juego
        "type": "pick3", // Tipo de apuesta
        "session_time": "morning", // Sesión de la apuesta
        "bet_details": [
            {
                "number": "123",
                "amount": 10.0
            }
        ]
    }
    ```
-   **Respuesta exitosa:**
    ```json
    {
        "id": 1,
        "user_id": 1,
        "game_id": 1,
        "type": "pick3",
        "session_time": "morning",
        "bet_details": [
            {
                "number": "123",
                "amount": 10.0
            }
        ],
        "status": "pending",
        "total_amount": 10.0,
        "created_at": "2023-10-01T12:00:00Z",
        "updated_at": "2023-10-01T12:00:00Z"
    }
    ```

### Obtener todas las apuestas

-   **Endpoint:** `GET /bets`
-   **Descripción:** Obtiene todas las apuestas realizadas por el usuario autenticado.
-   **Autenticación:** Requiere un token de autenticación en el encabezado `Authorization`.

### Obtener una apuesta específica

-   **Endpoint:** `GET /bets/{bet}`
-   **Descripción:** Obtiene una apuesta específica por su ID.
-   **Autenticación:** Requiere un token de autenticación en el encabezado `Authorization`.

### Obtener apuestas activas

-   **Endpoint:** `GET /bets/active`
-   **Descripción:** Obtiene las apuestas activas del usuario autenticado.
-   **Autenticación:** Requiere un token de autenticación en el encabezado `Authorization`.

## Juegos

### Obtener todos los juegos

-   **Endpoint:** `GET /games`
-   **Descripción:** Obtiene todos los juegos disponibles.

### Obtener un juego específico

-   **Endpoint:** `GET /games/{game}`
-   **Descripción:** Obtiene un juego específico por su ID.

### Obtener resultados de un juego

-   **Endpoint:** `GET /games/{game}/results`
-   **Descripción:** Obtiene los resultados de un juego específico.

## Encabezados de Autenticación

Para las rutas que requieren autenticación, incluye el siguiente encabezado en tus solicitudes:

Authorization: Bearer {token}

Reemplaza `{token}` con el token que recibiste al iniciar sesión o registrarte.

## Notas

-   Asegúrate de manejar correctamente los errores y las respuestas de la API.
-   Los campos requeridos deben ser validados antes de enviar la solicitud.
-   Asegúrate de validar los datos de entrada antes de insertarlos en la base de datos.
-   Asegúrate de manejar los errores de validación de entrada y de la base de datos.
-   Asegúrate de manejar los errores de autenticación y de autorización.
-   Asegúrate de manejar los errores de conexión y de respuesta de la API.
-   Asegúrate de manejar las excepciones y errores de manejo de la API.
-   Asegúrate de manejar la seguridad de la API, incluyendo la autenticación y autorización.
-   Asegúrate de documentar las rutas y los parámetros de la API de manera clara y concisa.
-   Asegúrate de probar la API con diferentes casos de uso y escenarios.
