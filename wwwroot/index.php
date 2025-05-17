<?php 
    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include_once "../database/functions.php";

    //A prior esta página es el menú principal
    //Desde aquí, yo había pensado en que se abra a dos páginas distintas:
        //1. pages/create.php -- Crear un personaje nuevo y que se guarde en la base de datos
        //2. pages/chat.php -- Chatear con un personaje en específico ya seleccionado
    
    //Entonces, en la página esta del menú principal ya pondría un selector de personajes.
    //La idea es que al darle clic encima de un botón de personaje (que eso ya te lo dejo hacer a ti)
    //se ponga en el enlace de arriba una id del personaje y luego el programa ya se configure para hablar con el personaje seleccionado

    $availableCharacters = getAllCharacters();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal</title>
    <style>
        body {
            font-family: 'Comic Sans MS', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: rgba(255, 244, 223, 0.9);
            color: #5d4b3c;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
        }
        
        h1, h2 {
            color: #865d45;
            text-align: center;
            font-family: 'Indie Flower', cursive, 'Comic Sans MS';
        }
        
        h1 {
            font-size: 2.5em;
            margin-top: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            border-bottom: 2px dashed #d3b88c;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        
        .character-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .character-card {
            background-color: #fff9ee;
            border-radius: 15px;
            padding: 15px;
            width: 220px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border: 1px solid #e3d0b5;
            transition: transform 0.3s ease;
            text-align: center;
        }
        
        .character-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
            background-color: #fefaf3;
        }
        
        .character-card h3 {
            color: #865d45;
            margin-top: 0;
            font-size: 1.5em;
            border-bottom: 1px solid #e3d0b5;
            padding-bottom: 8px;
        }
        
        .character-card p {
            color: #7a6a5b;
            margin: 10px 0;
            font-size: 1.1em;
        }
        
        .chat-button {
            display: inline-block;
            background-color: #b8936a;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            text-decoration: none;
            margin-top: 10px;
            transition: background-color 0.3s;
            font-size: 0.9em;
            border: none;
            cursor: pointer;
        }
        
        .chat-button:hover {
            background-color: #9d7a51;
        }
        
        .create-button {
            display: block;
            background-color: #7c9b72;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            border: none;
            font-size: 1.1em;
            cursor: pointer;
            margin: 30px auto;
            text-align: center;
            text-decoration: none;
            width: fit-content;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .create-button:hover {
            background-color: #6a8762;
            transform: scale(1.05);
        }
        
        a {
            text-decoration: none;
            color: inherit;
        }
        
        @media (max-width: 600px) {
            .character-card {
                width: 100%;
            }
        }
        
        /* Cute decorative elements */
        .decorative-flower {
            position: fixed;
            width: 100px;
            opacity: 0.3;
            z-index: -1;
        }
        
        .flower-1 {
            top: 10%;
            left: 5%;
        }
        
        .flower-2 {
            bottom: 20%;
            right: 5%;
        }
    </style>
</head>
<body>
    
    <h1>✧･ﾟ Menú Principal ･ﾟ✧</h1>
    <h2> •Selecciona un personaje para chatear•</h2>
    
    <div class="character-container">
        <?php 
            foreach ($availableCharacters as $character) {
                echo "<div class='character-card'>";
                echo "<h3>" . htmlspecialchars($character['name']) . "</h3>";
                echo "<p>Edad: " . htmlspecialchars($character['age']) . " años</p>";
                echo "<a href='pages/chat.php?id=" . htmlspecialchars($character['id']) . "' class='chat-button'>Chatear</a>";
                echo "</div>";
            }
        ?>
    </div>

    <a href="pages/create.php" class="create-button">✿Crear un nuevo personaje✿</a>
</body>
</html>