<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include_once "../../database/functions.php";
    require_once "../../database/secrets.php";

    $idCharacter = $_GET['id'] ?? null;
    $prompt = getPromptCharacter($idCharacter);

    if(!$prompt || !$idCharacter) {
        header("Location: ../index.php"); // Redirigir a la página principal
        exit();
    }

    $characterName = getCharacterName($idCharacter);

    if (!$characterName) {
        $characterName = "Personaje";
    }

    $escapedPrompt = htmlspecialchars($prompt, ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot con <?php echo $characterName; ?></title>
    <style>
        body {
            font-family: 'Comic Sans MS', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color:rgba(255, 244, 223, 0.9);
            color: #5d4b3c;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
        }
        
        h1 {
            color: #865d45;
            text-align: center;
            font-family: 'Indie Flower', cursive, 'Comic Sans MS';
            font-size: 2.2em;
            margin-top: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        #chat-container {
            border: 2px solid #d3b88c;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 500px;
            background-color: rgba(255, 249, 238, 0.9);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        #chat-header {
            background-color: #d3b88c;
            color: #5d4b3c;
            padding: 10px 15px;
            text-align: center;
            border-bottom: 1px solid #c1a578;
        }
        
        #chat-header h2 {
            margin: 0;
            font-size: 1.5em;
        }
        
        #chat-messages {
            flex-grow: 1;
            padding: 15px;
            overflow-y: auto;
            background-color: rgba(255, 249, 238, 0.8);
            background-blend-mode: lighten;
            background-size: 300px;
        }
        
        .message {
            margin-bottom: 15px;
            padding: 10px 15px;
            border-radius: 18px;
            max-width: 80%;
            word-wrap: break-word;
            font-size: 1em;
            position: relative;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .user-message {
            background-color: #cce5d2;
            color: #3c5d44;
            align-self: flex-end;
            margin-left: auto;
            border-bottom-right-radius: 5px;
            margin-right: 10px;
        }
        
        .bot-message {
            background-color: #f2e2c6;
            color: #5d4b3c;
            align-self: flex-start;
            border-bottom-left-radius: 5px;
            margin-left: 10px;
        }
        
        .user-message::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: -10px;
            width: 20px;
            height: 20px;
            background-color: #cce5d2;
            border-bottom-left-radius: 15px;
            z-index: -1;
        }
        
        .bot-message::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: -10px;
            width: 20px;
            height: 20px;
            background-color: #f2e2c6;
            border-bottom-right-radius: 15px;
            z-index: -1;
        }
        
        #user-input-container {
            display: flex;
            padding: 15px;
            background-color: rgba(255, 249, 238, 0.9);
            border-top: 1px solid #e3d0b5;
        }
        
        #user-input {
            flex-grow: 1;
            border: 2px solid #d3b88c;
            border-radius: 20px;
            padding: 10px 15px;
            outline: none;
            font-size: 1em;
            color: #5d4b3c;
            background-color: #fff;
            margin-right: 10px;
            transition: border-color 0.3s;
        }
        
        #user-input:focus {
            border-color: #b8936a;
            box-shadow: 0 0 5px rgba(184, 147, 106, 0.5);
        }
        
        #send-button {
            background-color: #7c9b72;
            color: white;
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s, transform 0.2s;
        }
        
        #send-button:hover {
            background-color: #6a8762;
            transform: scale(1.05);
        }
        
        .home-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 30px;
            color: #865d45;
            text-decoration: none;
            font-size: 1.1em;
            padding: 8px 16px;
            background-color: rgba(255, 249, 238, 0.7);
            border-radius: 20px;
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
            border: 1px solid #d3b88c;
            transition: background-color 0.3s;
        }
        
        .home-link:hover {
            background-color: #f2e2c6;
        }
        
        .loading {
            display: inline-block;
            margin-left: 10px;
        }
        
        .loading:after {
            content: '.';
            animation: dots 1s steps(5, end) infinite;
        }
        
        @keyframes dots {
            0%, 20% {
                content: '.';
            }
            40% {
                content: '..';
            }
            60% {
                content: '...';
            }
            80%, 100% {
                content: '';
            }
        }
        
        /* Solo mostramos el panel de debug cuando hay un parámetro en la URL */
        #debug-container {
            margin-top: 20px;
            border: 1px solid #d3b88c;
            border-radius: 15px;
            padding: 15px;
            background-color: rgba(255, 249, 238, 0.9);
            display: <?php echo isset($_GET['debug']) ? 'block' : 'none'; ?>;
        }
        
        #debug-log {
            height: 200px;
            overflow-y: auto;
            background-color: #5d4b3c;
            color: #b8d8bf;
            font-family: monospace;
            padding: 10px;
            border-radius: 10px;
            margin-top: 10px;
            font-size: 0.9em;
        }
        
        .debug-entry {
            margin-bottom: 5px;
            word-wrap: break-word;
        }
        
        /* Decorative elements */
        .decorative-flower {
            position: fixed;
            font-size: 30px;
            opacity: 0.2;
            z-index: -1;
        }
        
        .flower-1 {
            top: 15%;
            left: 5%;
        }
        
        .flower-2 {
            bottom: 10%;
            right: 5%;
        }
        
        .flower-3 {
            top: 50%;
            right: 8%;
        }
        
        .flower-4 {
            bottom: 40%;
            left: 8%;
        }
        
        /* Responsive design */
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            
            #chat-container {
                height: 400px;
            }
            
            .message {
                max-width: 90%;
            }
        }
    </style>
</head>
<body>
    
    <h1>Charlando con <?php echo $characterName; ?></h1>
    
    <div id="chat-container">
        <div id="chat-header">
            <h2>♡ <?php echo $characterName; ?> ♡</h2>
        </div>
        <div id="chat-messages">
            <!-- Mensajes del chat aparecerán aquí -->
        </div>
        <div id="user-input-container">
            <input type="text" id="user-input" placeholder="Escribe un mensaje...">
            <button id="send-button">➤</button>
        </div>
    </div>
    
    <a href="../index.php" class="home-link">← Volver al inicio</a>
    
    <!-- Área de depuración (oculta por defecto) -->
    <div id="debug-container">
        <h3>Panel de depuración</h3>
        <div id="debug-log"></div>
    </div>
</body>
</html>

<script>
    // Configuración de Azure OpenAI
    const AZURE_ENDPOINT = "https://asanc-ma0sf04c-eastus2.cognitiveservices.azure.com/";
    const MODEL_NAME = "gpt-4o-mini";
    const DEPLOYMENT = "gpt-4o-mini";
    const API_VERSION = "2024-04-01-preview";

    // ************ PONER TU API KEY AQUÍ ************
    const API_KEY = "<?php echo AZURE_API_KEY; ?>";
    // ***********************************************

    // Elementos del DOM
    const userInput = document.getElementById('user-input');
    const sendButton = document.getElementById('send-button');
    const chatMessages = document.getElementById('chat-messages');
    const debugLog = document.getElementById('debug-log');

    // Historia de mensajes para el contexto de la conversación
    let messageHistory = [
        { role: "system", content: <?php echo json_encode($prompt); ?> }
    ];

    // Función para registrar información de depuración
    function debug(message, data = null) {
        const timestamp = new Date().toLocaleTimeString();
        let logMessage = `[${timestamp}] ${message}`;
        
        // Crear elemento para el mensaje de depuración
        const debugEntry = document.createElement('div');
        debugEntry.className = 'debug-entry';
        
        // Si hay datos adicionales, mostrarlos
        if (data) {
            console.log(message, data); // También mostramos en la consola del navegador
            try {
                if (typeof data === 'object') {
                    logMessage += `: ${JSON.stringify(data, null, 2)}`;
                } else {
                    logMessage += `: ${data}`;
                }
            } catch (e) {
                logMessage += `: [Objeto no serializable]`;
            }
        } else {
            console.log(message); // También mostramos en la consola del navegador
        }
        
        debugEntry.textContent = logMessage;
        debugLog.appendChild(debugEntry);
        debugLog.scrollTop = debugLog.scrollHeight;
    }

    // Inicialización
    async function init() {
        debug("Inicializando chatbot...");
        debug("Configuración cargada:", {
            endpoint: AZURE_ENDPOINT,
            model: MODEL_NAME,
            deployment: DEPLOYMENT,
            apiVersion: API_VERSION
        });
        
        if (API_KEY === "TU_API_KEY_DE_AZURE_OPENAI") {
            debug("⚠️ ADVERTENCIA: API KEY no configurada. Por favor, edita el archivo JS.");
        } else {
            debug("API Key configurada (primeros 4 caracteres): " + API_KEY.substring(0, 4) + "...");
        }

        // Mostrar un mensaje de carga mientras se genera el saludo
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'message bot-message';
        loadingDiv.innerHTML = 'Iniciando<span class="loading"></span>';
        chatMessages.appendChild(loadingDiv);
        
        try {
            debug("Solicitando mensaje de bienvenida personalizado...");
            
            // Crear un mensaje específico para solicitar un saludo
            const greetingPrompt = [
                { role: "system", content: messageHistory[0].content },
                { role: "user", content: "Preséntate brevemente y salúdame como lo harías normalmente. Solo di el saludo, no continúes la conversación." }
            ];
            
            // Llamar a la API para generar el saludo
            const greeting = await callAzureOpenAI(greetingPrompt);
            
            // Quitar el mensaje de carga
            chatMessages.removeChild(loadingDiv);
            
            // Mostrar el saludo generado
            addMessage(greeting, 'bot');
            
            debug("Saludo personalizado generado: " + greeting.substring(0, 50) + "...");
        } catch (error) {
            debug("⚠️ ERROR al generar saludo:", error);
            
            // Quitar el mensaje de carga
            chatMessages.removeChild(loadingDiv);
            
            // Mostrar un saludo genérico en caso de error
            addMessage("Hola, estoy aquí para hablar contigo. ¿En qué puedo ayudarte?", 'bot');
        }

        // Event listeners
        sendButton.addEventListener('click', sendMessage);
        userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage();
        });
        
        debug("Chatbot listo para conversar.");
    }

    // Enviar mensaje al chatbot
    async function sendMessage() {
        const userMessage = userInput.value.trim();
        if (!userMessage) {
            debug("Mensaje vacío, no se envía nada");
            return;
        }

        debug(`Usuario envía mensaje: "${userMessage}"`);

        // Añadir mensaje del usuario a la interfaz
        addMessage(userMessage, 'user');
        userInput.value = '';

        // Añadir mensaje a la historia
        messageHistory.push({ role: "user", content: userMessage });
        debug("Historia de mensajes actualizada", messageHistory);

        // Mostrar indicador de carga
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'message bot-message';
        loadingDiv.innerHTML = 'Pensando<span class="loading"></span>';
        chatMessages.appendChild(loadingDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;

        try {
            debug("Preparando solicitud a Azure OpenAI...");
            
            // Llamar a la API de Azure OpenAI
            const botResponse = await callAzureOpenAI(messageHistory);
            
            debug(`Respuesta recibida: "${botResponse.substring(0, 50)}${botResponse.length > 50 ? '...' : ''}"`);
            
            // Eliminar indicador de carga
            chatMessages.removeChild(loadingDiv);
            
            // Añadir respuesta del bot a la interfaz
            addMessage(botResponse, 'bot');
            
            // Añadir respuesta a la historia
            messageHistory.push({ role: "assistant", content: botResponse });
            debug("Historia de mensajes actualizada con respuesta del asistente");
        } catch (error) {
            debug("⚠️ ERROR al procesar la solicitud:", error);
            
            // Eliminar indicador de carga
            chatMessages.removeChild(loadingDiv);
            
            // Mostrar mensaje de error
            addMessage(`Error: ${error.message}`, 'bot');
        }
    }

    // Añadir mensaje a la interfaz
    function addMessage(text, sender) {
        debug(`Añadiendo mensaje de ${sender} a la interfaz`);
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}-message`;
        messageDiv.textContent = text;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Llamar a la API de Azure OpenAI
    async function callAzureOpenAI(messages) {
        const url = `${AZURE_ENDPOINT}openai/deployments/${DEPLOYMENT}/chat/completions?api-version=${API_VERSION}`;
        debug(`URL de la API: ${url}`);
        
        const requestBody = {
            messages: messages,
            max_tokens: 800,
            temperature: 0.7,
            top_p: 0.95,
            model: MODEL_NAME
        };
        
        debug("Cuerpo de la solicitud:", requestBody);
        debug("Enviando solicitud a Azure OpenAI...");
        
        try {
            const startTime = Date.now();
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'api-key': API_KEY
                },
                body: JSON.stringify(requestBody)
            });
            
            const elapsedTime = Date.now() - startTime;
            debug(`Respuesta recibida en ${elapsedTime}ms con estado: ${response.status}`);
            
            if (!response.ok) {
                const errorData = await response.json();
                debug("Error detallado de la API:", errorData);
                throw new Error(errorData.error?.message || `Error HTTP ${response.status}`);
            }
            
            const data = await response.json();
            debug("Respuesta completa de la API:", data);
            
            return data.choices[0].message.content;
        } catch (error) {
            debug("Error durante la solicitud:", error);
            throw error;
        }
    }

    // Iniciar la aplicación cuando el DOM esté cargado
    document.addEventListener('DOMContentLoaded', () => init());
</script>