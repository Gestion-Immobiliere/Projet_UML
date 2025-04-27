{{-- <!DOCTYPE html>
<html>
<head>
    <title>Test Reverb</title>
    @vite(['resources/js/echo.js'])
</head>
<body>
    <h1>Test WebSocket avec Reverb</h1>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            console.log('test')
            window.Echo.channel('public-chat')
                .listen('MessageSent', (data) => { 
                    console.log('Message reçu:', data);
                    alert(`${data.sender.name} : ${data.message}`);
                })
                .error(err => console.error('Erreur:', err));
        });
        </script>
</body>
</html> --}}

{{-- <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affichage des messages en temps réel</title>
    <style>
        #messages {
            max-width: 600px;
            margin: 20px auto;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            height: 400px;
            overflow-y: auto;
            background-color: #f9f9f9;
        }
        .message {
            margin-bottom: 10px;
            padding: 8px 12px;
            background-color: #e3f2fd;
            border-radius: 18px;
            display: inline-block;
            max-width: 70%;
        }
        h1 {
            text-align: center;
            color: #333;
        }
    </style>
    @vite(['resources/js/echo.js'])
</head>
<body>
    <div>
        <h1>Messages en temps réel</h1>
        <div id="messages">
            @foreach ($messages as $message)
            <div class="message">
                <strong>{{ $message->sender->name }}:</strong> {{ $message->message }}
            </div>
        @endforeach
        </div>
    </div>

    <script>
        const userId = localStorage.getItem('userId'); // Récupère l'ID de l'utilisateur
    
        if (userId) {
            document.addEventListener('DOMContentLoaded', () => {
            window.Echo.private('chat.' + userId) // Ecoute le canal privé spécifique à l'utilisateur
                .listen('MessageSent', (data) => {
                    console.log('Message reçu:', data);
                    const messagesDiv = document.getElementById('messages');
                    const messageElement = document.createElement('div');
                    messageElement.className = 'message';
                    messageElement.innerText = `${data.sender}: ${data.message}`;
                    messagesDiv.appendChild(messageElement);
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                });
            });
        } else {
            alert('Utilisateur non connecté');
        }
    </script>
</body>
</html> --}}

{{-- <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affichage des messages en temps réel</title>
    <style>
        #messages {
            max-width: 600px;
            margin: 20px auto;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            height: 400px;
            overflow-y: auto;
            background-color: #f9f9f9;
            display: none; /* Masqué par défaut */
        }
        .message {
            margin-bottom: 10px;
            padding: 8px 12px;
            background-color: #e3f2fd;
            border-radius: 18px;
            display: inline-block;
            max-width: 70%;
        }
        #loginPrompt {
            text-align: center;
            margin-top: 50px;
            display: none; /* Masqué par défaut */
        }
        h1 {
            text-align: center;
            color: #333;
        }
    </style>
    @vite(['resources/js/echo.js'])
</head>
<body>
    <div>
        <h1>Messages en temps réel</h1>
        <div id="messages">
            @foreach ($messages as $message)
            <div class="message">
                <strong>{{ $message->sender->name }}:</strong> {{ $message->message }}
            </div>
            @endforeach
        </div>
        <div id="loginPrompt">
            <p>Veuillez vous connecter pour voir les messages</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const userId = localStorage.getItem('userId');
            const messagesDiv = document.getElementById('messages');
            const loginPrompt = document.getElementById('loginPrompt');

            if (userId) {
        
                messagesDiv.style.display = 'block';
                
                window.Echo.private('chat.' + userId)
                    .listen('MessageSent', (data) => {
                        console.log('Message reçu:', data);
                        const messageElement = document.createElement('div');
                        messageElement.className = 'message';
                        messageElement.innerHTML = `<strong>${data.sender.name}:</strong> ${data.message}`;
                        messagesDiv.appendChild(messageElement);
                        messagesDiv.scrollTop = messagesDiv.scrollHeight;
                    });
            } else {
                // Afficher le message de connexion
                loginPrompt.style.display = 'block';
            }
        });
    </script>
</body>
</html> --}}

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affichage des messages en temps réel</title>
    <style>
        /* Style pour les messages */
        #messages {
            max-width: 600px;
            margin: 20px auto;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            height: 400px;
            overflow-y: auto;
            background-color: #f9f9f9;
        }

        /* Style des messages */
        .message {
            margin-bottom: 10px;
            padding: 8px 12px;
            background-color: #e3f2fd;
            border-radius: 18px;
            display: inline-block;
            max-width: 70%;
        }

        /* Messages envoyés par l'utilisateur (gent) */
        .message.sent {
            background-color: #e3f2fd; /* Fond bleu clair pour les messages envoyés par le gent */
            align-self: flex-end; /* Alignement à droite */
        }

        /* Messages reçus de l'autre utilisateur (locataire) */
        .message.received {
            background-color: #333; /* Fond noir pour les messages reçus par le locataire */
            color: #fff; /* Texte en blanc */
            align-self: flex-start; /* Alignement à gauche */
        }

        /* Affichage des messages */
        .messages-container {
            display: flex;
            flex-direction: column;
        }

        /* Style de l'alerte pour l'utilisateur non connecté */
        #loginPrompt {
            text-align: center;
            margin-top: 50px;
            display: none; /* Masqué par défaut */
        }

        h1 {
            text-align: center;
            color: #333;
        }
    </style>
    @vite(['resources/js/echo.js'])
</head>
<body>
    <div>
        <h1>Messages en temps réel</h1>
        <div id="messages" class="messages-container">
            @foreach ($messages as $message)
                <div class="message 
                    {{ $message->sender->id == 2 ? 'sent' : 'received' }}">
                    <strong>{{ $message->sender->name }}:</strong> {{ $message->message }}
                </div>
            @endforeach
        </div>
        <div id="loginPrompt">
            <p>Veuillez vous connecter pour voir les messages</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const userId = localStorage.getItem('userId');
            const messagesDiv = document.getElementById('messages');
            const loginPrompt = document.getElementById('loginPrompt');

            if (userId) {
                messagesDiv.style.display = 'block';
                
                window.Echo.private('chat.' + userId)
                    .listen('MessageSent', (data) => {
                        console.log('Message reçu:', data);

                        const messageElement = document.createElement('div');
                        messageElement.className = 'message';
                        // Applique la classe appropriée en fonction de l'utilisateur
                        messageElement.classList.add(data.sender.id == userId ? 'sent' : 'received');
                        messageElement.innerHTML = `<strong>${data.sender.name}:</strong> ${data.message}`;
                        messagesDiv.appendChild(messageElement);

                        // Scroll automatique pour voir le dernier message
                        messagesDiv.scrollTop = messagesDiv.scrollHeight;
                    });
            } else {
                loginPrompt.style.display = 'block';
            }
        });
    </script>
</body>
</html>
