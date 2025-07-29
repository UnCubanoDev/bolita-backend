<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LottoGame - Apuestas de Lotería en NY, GA y FL</title>
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
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--accent);
        }

        .logo span {
            color: white;
        }

        .cta-button {
            display: inline-block;
            background-color: var(--accent);
            color: var(--dark);
            padding: 0.8rem 1.5rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 8px rgba(0,0,0,0.15);
            background-color: #ffd54f;
        }

        .hero {
            padding: 4rem 0;
            text-align: center;
            background: url('https://images.unsplash.com/photo-1508317469940-e3de49ba902e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') no-repeat center center/cover;
            position: relative;
            color: white;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 2rem;
        }

        .features {
            padding: 4rem 0;
            background-color: white;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
            color: var(--primary);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: var(--light);
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .states {
            padding: 4rem 0;
            background-color: #f9f9f9;
        }

        .states-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .state-card {
            display: flex;
            flex-direction: column;
        }

        .state-image {
            height: 0;
            padding-top: 56.25%; /* Relación de aspecto 16:9 (ajusta según necesites) */
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            background-color: #f5f5f5;
        }
        /* .state-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .state-image {
            height: 150px;
            background-size: cover;
            background-position: center;
        } */

        .ny { background-image: url('https://tuboliteros.com/media/tiradas/254604.png'); }
        .ga { background-image: url('https://tuboliteros.com/media/tiradas/ga-lottery-30a.png'); }
        .fl { background-image: url('https://tuboliteros.com/media/tiradas/file_0000000021e061f8a895964054a9f72d.png'); }

        .state-info {
            padding: 1.5rem;
        }

        .state-info h3 {
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .download {
            padding: 4rem 0;
            text-align: center;
            background: linear-gradient(135deg, var(--primary), #1b5e20);
            color: white;
        }

        .download h2 {
            margin-bottom: 1.5rem;
        }

        .app-buttons {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            margin-top: 2rem;
            flex-wrap: wrap;
        }

        .app-button {
            display: flex;
            align-items: center;
            background: black;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }

        .app-button img {
            height: 30px;
            margin-right: 10px;
        }

        footer {
            background-color: var(--dark);
            color: white;
            padding: 2rem 0;
            text-align: center;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: 1rem 0;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
        }

        .disclaimer {
            font-size: 0.8rem;
            opacity: 0.7;
            max-width: 800px;
            margin: 2rem auto 0;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.2rem;
            }

            .hero p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <div class="logo">Lotto<span>Game</span></div>
                <a href="#download" class="cta-button">Descargar Ahora</a>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Apuestas de Lotería en NY, GA y FL</h1>
                <p>La forma más fácil y segura de participar en los sorteos de lotería más populares de estos estados. ¡Descarga la app y podrías ser el próximo ganador!</p>
                <a href="#download" class="cta-button">Empieza a Ganar</a>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <h2 class="section-title">Por Qué Elegir LottoGame</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🏆</div>
                    <h3>Grandes Premios</h3>
                    <p>Participa en los sorteos con los premios más grandes de New York, Georgia y Florida.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>Seguridad Garantizada</h3>
                    <p>Plataforma 100% segura con encriptación de última generación para proteger tus datos.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Rápido y Fácil</h3>
                    <p>Haz tus apuestas en segundos desde tu teléfono y recibe notificaciones de los resultados.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="states">
        <div class="container">
            <h2 class="section-title">Loterías Disponibles</h2>
            <div class="states-grid">
                <div class="state-card">
                    <div class="state-image ny"></div>
                    <div class="state-info">
                        <h3>New York</h3>
                        <p>Participa en los sorteos de Pick3, Fijo, Corrido y Parle. ¡Los premios más grandes del noreste!</p>
                    </div>
                </div>
                <div class="state-card">
                    <div class="state-image ga"></div>
                    <div class="state-info">
                        <h3>Georgia</h3>
                        <p>Participa en los sorteos de Pick3, Fijo, Corrido y Parle de Georgia.</p>
                    </div>
                </div>
                <div class="state-card">
                    <div class="state-image fl"></div>
                    <div class="state-info">
                        <h3>Florida</h3>
                        <p>Accede a Florida Lotto, Fijo, Corrido, Pick 3 y Parle directamente desde la app.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="download" id="download">
        <div class="container">
            <h2>Descarga la App LottoGame Hoy</h2>
            <p>Disponible para Android. ¡Empieza a jugar en minutos!</p>
            <div class="app-buttons">
                <a href="#" class="app-button">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/7a/Google_Play_2022_logo.svg/512px-Google_Play_2022_logo.svg.png?20220726170516" alt="Google Play">
                </a>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="logo">Lotto<span>Game</span></div>
            <div class="footer-links">
                {{-- <a href="{{ route('terms') }}">Términos y Condiciones</a>
                <a href="{{ route('privacy') }}">Política de Privacidad</a>
                <a href="{{ route('support') }}">Soporte</a>
                <a href="{{ route('faq') }}">Preguntas Frecuentes</a> --}}
            </div>
            <p class="disclaimer">
                LottoGame es una plataforma para apuestas de lotería oficiales. Debes tener al menos 18 años para participar. Juega responsablemente. LottoGame no garantiza ganar premios y no está afiliado a las loterías estatales de New York, Georgia o Florida.
            </p>
            <p>© {{ date('Y') }} LottoGame. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>
