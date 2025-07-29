<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preguntas Frecuentes - LottoGame</title>
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
            font-size: 2rem;
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

        .faq-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 3rem;
        }

        .faq-item {
            border-bottom: 1px solid #eee;
        }

        .faq-question {
            padding: 1.5rem;
            background-color: #f9f9f9;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .faq-question:hover {
            background-color: #f0f0f0;
        }

        .faq-question::after {
            content: '+';
            font-size: 1.5rem;
            color: var(--primary);
        }

        .faq-question.active::after {
            content: '-';
        }

        .faq-answer {
            padding: 0 1.5rem;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .faq-answer.show {
            padding: 1.5rem;
            max-height: 500px;
        }

        .contact-section {
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, var(--secondary), #0d47a1);
            color: white;
            border-radius: 10px;
        }

        .contact-button {
            display: inline-block;
            background-color: var(--accent);
            color: var(--dark);
            padding: 0.8rem 1.5rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .contact-button:hover {
            background-color: #ffd54f;
            transform: translateY(-2px);
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

            .faq-question {
                padding: 1rem;
                font-size: 0.9rem;
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
        <h1>Preguntas Frecuentes</h1>

        <div class="faq-container">
            <!-- Sección de Cuentas y Registro -->
            <div class="faq-item">
                <div class="faq-question">¿Cómo puedo registrarme en la plataforma?</div>
                <div class="faq-answer">
                    <p>Para registrarte necesitas descargar nuestra aplicación móvil y completar el formulario con tus datos personales. Requerimos información básica como nombre completo, número de teléfono y correo electrónico.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">¿Qué métodos de pago aceptan?</div>
                <div class="faq-answer">
                    <p>Aceptamos transferencias bancarias, tarjetas de débito/crédito internacionales, y métodos de pago populares en Cuba como Transfermóvil y EnZona. También trabajamos con agentes autorizados en diferentes localidades.</p>
                </div>
            </div>

            <!-- Sección de Apuestas -->
            <div class="faq-item">
                <div class="faq-question">¿Qué tipos de apuestas ofrecen para la Bolita?</div>
                <div class="faq-answer">
                    <p>Ofrecemos todos los juegos tradicionales:
                        <ul>
                            <li>Fijo (1 número)</li>
                            <li>Corrido (1 número del Pick4)</li>
                            <li>Parle (2 números)</li>
                            <li>Pick 3</li>
                            <li>Apuestas especiales para los sorteos de NY, GA y FL</li>
                        </ul>
                    </p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">¿Cuáles son los horarios de apuestas?</div>
                <div class="faq-answer">
                    <p>Los horarios varían según el estado:
                        <ul>
                            <li><strong>Florida:</strong> Cierre a las 12:45 PM (hora de Cuba)</li>
                            <li><strong>New York:</strong> Cierre a las 1:30 PM (hora de Cuba)</li>
                            <li><strong>Georgia:</strong> Cierre a las 12:30 PM (hora de Cuba)</li>
                        </ul>
                        Las apuestas para el día siguiente se abren inmediatamente después del sorteo actual.
                    </p>
                </div>
            </div>

            <!-- Sección de Pagos -->
            <div class="faq-item">
                <div class="faq-question">¿Cómo y cuándo recibo mis ganancias?</div>
                <div class="faq-answer">
                    <p>Los pagos se realizan dentro de las 24 horas siguientes a la publicación de los resultados oficiales. Puedes recibir tus ganancias mediante:
                        <ul>
                            <li>Transferencia bancaria</li>
                            <li>Retiro en efectivo con nuestros agentes</li>
                            <li>Recarga a tu billetera electrónica</li>
                        </ul>
                        Los pagos mayores a $500 pueden requerir verificación adicional.
                    </p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">¿Qué comisiones cobran por los pagos?</div>
                <div class="faq-answer">
                    <p>No cobramos comisiones por pagos electrónicos. Para retiros en efectivo con agentes, aplica una comisión del 2% sobre el monto retirado.</p>
                </div>
            </div>

            <!-- Sección Técnica -->
            <div class="faq-item">
                <div class="faq-question">¿Qué hago si tengo problemas con la aplicación?</div>
                <div class="faq-answer">
                    <p>Puedes contactar a nuestro soporte técnico por WhatsApp (+1 xxx-xxx-xxxx), Telegram (@Bolitasoporte) o correo electrónico (soporte@LottoGame.com). Atendemos 24/7.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">¿Es seguro apostar con ustedes?</div>
                <div class="faq-answer">
                    <p>Absolutamente. Utilizamos tecnología de encriptación bancaria para proteger tus datos y transacciones. Todos los sorteos se basan en los resultados oficiales de las loterías estatales de Florida, New York y Georgia.</p>
                </div>
            </div>

            <!-- Sección Legal -->
            <div class="faq-item">
                <div class="faq-question">¿Tienen alguna restricción de edad?</div>
                <div class="faq-answer">
                    <p>Sí, solo pueden registrarse y apostar personas mayores de 18 años. Podemos solicitar identificación para verificar la edad en cualquier momento.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">¿Qué pasa si hay un error en mi apuesta?</div>
                <div class="faq-answer">
                    <p>Todas las apuestas son verificadas automáticamente. Si detectas algún error, debes reportarlo inmediatamente a nuestro soporte antes del cierre de apuestas. Una vez cerrado el sorteo, no se pueden hacer modificaciones.</p>
                </div>
            </div>
        </div>

        <div class="contact-section">
            <h2>¿No encontraste tu respuesta?</h2>
            <p>Nuestro equipo de soporte está disponible 24/7 para ayudarte con cualquier pregunta.</p>
            <a href="https://wa.me/1XXXXXXXXXX" class="contact-button">Contactar por WhatsApp</a>
        </div>
    </div>

    <footer>
        <div class="container">
            <p class="disclaimer">
                LottoGame es una plataforma independiente de apuestas basadas en los sorteos oficiales de lotería. No estamos afiliados a las loterías estatales de Florida, New York o Georgia. Juega responsablemente.
            </p>
            <p>© {{ date('Y') }} LottoGame. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                question.classList.toggle('active');
                answer.classList.toggle('show');
            });
        });
    </script>
</body>
</html>
