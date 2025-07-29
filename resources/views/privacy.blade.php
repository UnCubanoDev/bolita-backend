<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidad - LottoGame</title>
    <style>
        :root {
            --primary: #2e7d32;
            --secondary: #d32f2f;
            --accent: #ffc107;
            --dark: #212121;
            --light: #f5f5f5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }

        header {
            background: linear-gradient(135deg, var(--primary), #1b5e20);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            position: relative;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 0;
        }

        .back-button {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-weight: bold;
        }

        .back-button:hover {
            color: var(--accent);
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--accent);
            text-align: center;
            margin-bottom: 1rem;
        }

        .logo span {
            color: white;
        }

        h1 {
            color: var(--primary);
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2.5rem;
        }

        h2 {
            color: var(--primary);
            margin: 2rem 0 1rem;
            font-size: 1.5rem;
        }

        .content {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        p, ul {
            margin-bottom: 1rem;
        }

        ul {
            padding-left: 2rem;
        }

        strong {
            color: var(--dark);
        }

        footer {
            background-color: var(--dark);
            color: white;
            padding: 2rem 0;
            text-align: center;
            margin-top: 3rem;
        }

        .disclaimer {
            font-size: 0.8rem;
            opacity: 0.7;
            max-width: 800px;
            margin: 1rem auto 0;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 1.8rem;
            }

            .content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <a href="{{ url('/') }}" class="back-button">
                ← Volver
            </a>
            <div class="logo">Lotto<span>Game</span></div>
        </div>
    </header>

    <div class="container">
        <h1>Política de Privacidad</h1>

        <div class="content">
            <p><strong>Última actualización:</strong> {{ date('d/m/Y') }}</p>

            <p>En LottoGame, valoramos y respetamos su privacidad. Esta Política de Privacidad explica cómo recopilamos, usamos, compartimos y protegemos su información personal cuando utiliza nuestra aplicación y servicios.</p>

            <h2>1. Información que Recopilamos</h2>
            <p>Recopilamos los siguientes tipos de información:</p>
            <ul>
                <li><strong>Información de registro:</strong> Nombre completo, dirección de correo electrónico, número de teléfono, fecha de nacimiento.</li>
                <li><strong>Información de pago:</strong> Datos de transacciones, métodos de pago (no almacenamos datos completos de tarjetas).</li>
                <li><strong>Información de dispositivo:</strong> Dirección IP, tipo de dispositivo, sistema operativo, identificadores únicos.</li>
                <li><strong>Datos de uso:</strong> Actividad en la aplicación, preferencias, historial de apuestas.</li>
            </ul>

            <h2>2. Cómo Usamos Su Información</h2>
            <p>Utilizamos su información para:</p>
            <ul>
                <li>Proveer y mejorar nuestros servicios</li>
                <li>Verificar su identidad y edad</li>
                <li>Procesar transacciones y pagos</li>
                <li>Prevenir fraudes y actividades ilegales</li>
                <li>Enviar actualizaciones y notificaciones importantes</li>
                <li>Personalizar su experiencia de usuario</li>
                <li>Cumplir con obligaciones legales</li>
            </ul>

            <h2>3. Compartir Información</h2>
            <p>Podemos compartir su información con:</p>
            <ul>
                <li>Proveedores de servicios de pago</li>
                <li>Socios comerciales necesarios para la operación</li>
                <li>Autoridades legales cuando sea requerido por ley</li>
                <li>Empresas afiliadas para mejorar nuestros servicios</li>
            </ul>
            <p>No vendemos ni alquilamos su información personal a terceros con fines de marketing.</p>

            <h2>4. Seguridad de Datos</h2>
            <p>Implementamos medidas de seguridad técnicas y organizativas para proteger su información, incluyendo:</p>
            <ul>
                <li>Encriptación de datos sensibles</li>
                <li>Protocolos de seguridad avanzados</li>
                <li>Accesos restringidos a información personal</li>
                <li>Revisiones periódicas de seguridad</li>
            </ul>

            <h2>5. Sus Derechos</h2>
            <p>Usted tiene derecho a:</p>
            <ul>
                <li>Acceder a su información personal</li>
                <li>Solicitar corrección de datos inexactos</li>
                <li>Pedir la eliminación de sus datos personales</li>
                <li>Oponerse al procesamiento de sus datos</li>
                <li>Solicitar limitación del tratamiento</li>
                <li>Portabilidad de datos</li>
            </ul>
            <p>Para ejercer estos derechos, contáctenos a través de nuestro formulario de soporte.</p>

            <h2>6. Cookies y Tecnologías Similares</h2>
            <p>Utilizamos cookies y tecnologías similares para:</p>
            <ul>
                <li>Mejorar la funcionalidad de nuestra aplicación</li>
                <li>Analizar el uso de nuestros servicios</li>
                <li>Personalizar contenido y anuncios</li>
            </ul>
            <p>Puede gestionar sus preferencias de cookies en la configuración de su dispositivo.</p>

            <h2>7. Menores de Edad</h2>
            <p>Nuestros servicios están dirigidos exclusivamente a mayores de 18 años. No recopilamos conscientemente información de menores. Si descubrimos que hemos recibido información de un menor, tomaremos medidas para eliminar dicha información.</p>

            <h2>8. Cambios a esta Política</h2>
            <p>Podemos actualizar esta Política de Privacidad ocasionalmente. Le notificaremos sobre cambios significativos a través de nuestra aplicación o por correo electrónico.</p>

            <h2>9. Contacto</h2>
            <p>Si tiene preguntas sobre esta Política de Privacidad, contáctenos en:</p>
            <ul>
                <li><strong>Email:</strong> privacidad@lottogame.com</li>
                <li><strong>Soporte en la app:</strong> Sección de Ayuda</li>
                <li><strong>Correo postal:</strong> [Dirección física de la empresa]</li>
            </ul>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="logo">Lotto<span>Game</span></div>
            <p class="disclaimer">
                LottoGame es una plataforma para apuestas de lotería oficiales. Debes tener al menos 18 años para participar. Juega responsablemente.
            </p>
            <p>© {{ date('Y') }} LottoGame. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
