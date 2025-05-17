<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include_once "../../database/functions.php";

    //Esta página es para crear un personaje nuevo y guardarlo en la base de datos
    //Se hace a través de un formulario
    //El formulario debe crearse y dejar que el usuario puede rellenarlo con los siguientes datos (casi todos opcionales)
        //1. Nombre (obligatorio)
        //2. Edad (opcional)
        //3. LISTA de gustos (likes)
        //4. LISTA de disgustos (dislikes)
        //5. LISTA de hobbies (hobbies)
        //6. LISTA de personalidades (personalities)
    
    //Mi idea para las listas es que se vayan añadiendo espacios de textos y se vaya guardando todo. 
    //Rollo que empiece con un input y se quieren añadir más, se le da a un botón y se añaden más inputs para rellenar
    //Las listas pueden ser lo largas que quieras con los datos que quieran los usuarios.

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $newCharacter = [
            'name' => $_POST['name'],
            'age' => $_POST['age'],
            'likes' => isset($_POST['likes']) ? $_POST['likes'] : [],
            'dislikes' => isset($_POST['dislikes']) ? $_POST['dislikes'] : [],
            'hobbies' => isset($_POST['hobbies']) ? $_POST['hobbies'] : [],
            'personalities' => isset($_POST['personalities']) ? $_POST['personalities'] : []
        ];

        createCharacter($newCharacter);
        header("Location: ../index.php"); // Redirigir a la página principal después de crear el personaje
        exit();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Personaje</title>
    <style>
        body {
            font-family: 'Comic Sans MS', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color:rgba(255, 244, 223, 0.9);
            color: #5d4b3c;
            max-width: 800px;
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
            color: #7c9b72;
            border-bottom: 2px dashed #d3b88c;
            padding-bottom: 8px;
            margin-top: 25px;
        }
        
        form {
            background-color: #fff9ee;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border: 1px solid #e3d0b5;
            margin-top: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #7a6a5b;
            font-size: 1.1em;
        }
        
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px 15px;
            margin-bottom: 15px;
            border: 1px solid #d3b88c;
            border-radius: 15px;
            background-color: #fefaf3;
            font-size: 1em;
            color: #5d4b3c;
            box-sizing: border-box;
        }
        
        input[type="text"]:focus, input[type="number"]:focus {
            outline: none;
            border-color: #b8936a;
            background-color: #ffffff;
            box-shadow: 0 0 5px rgba(184, 147, 106, 0.5);
        }
        
        button {
            background-color: #b8936a;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 15px;
            cursor: pointer;
            font-size: 0.9em;
            margin-bottom: 20px;
            transition: background-color 0.3s, transform 0.2s;
        }
        
        button:hover {
            background-color: #9d7a51;
            transform: scale(1.05);
        }
        
        input[type="submit"] {
            background-color: #7c9b72;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 1.1em;
            margin-top: 20px;
            width: 100%;
            transition: background-color 0.3s;
        }
        
        input[type="submit"]:hover {
            background-color: #6a8762;
        }
        
        .home-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #865d45;
            text-decoration: none;
            font-size: 1em;
        }
        
        .home-link:hover {
            text-decoration: underline;
        }
        
        .input-container {
            background-color: #fefaf3;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px dashed #d3b88c;
        }
        
        .add-button {
            display: inline-flex;
            align-items: center;
            background-color: #b8936a;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 15px;
            cursor: pointer;
            font-size: 0.9em;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }
        
        .add-button:before {
            content: "+";
            margin-right: 5px;
            font-size: 1.2em;
        }
        
        .section-icon {
            font-size: 1.5em;
            margin-right: 10px;
            vertical-align: middle;
        }
        
        /* Decorative elements */
        .decorative-flower {
            position: fixed;
            font-size: 24px;
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
    
    <h1>✿Crear un nuevo personaje✿</h1>
    <form action="create.php" method="POST">
        <div class="input-container">
            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" required placeholder="Escribe el nombre...">

            <label for="age">Edad:</label>
            <input type="number" id="age" name="age" placeholder="Escribe la edad...">
        </div>

        <h2> Likes<span class="section-icon">🩷</span></h2>
        <div class="input-container" id="likes-container">
            <input type="text" name="likes[]" placeholder="¿Qué le gusta?"><br>
        </div>
        <button type="button" class="add-button" onclick="addInput('likes-container')">Añadir Like</button>

        <h2> Dislikes<span class="section-icon">👎🏻</span></h2>
        <div class="input-container" id="dislikes-container">
            <input type="text" name="dislikes[]" placeholder="¿Qué no le gusta?"><br>
        </div>
        <button type="button" class="add-button" onclick="addInput('dislikes-container')">Añadir Dislike</button>

        <h2> Hobbies<span class="section-icon">🎮</span></h2>
        <div class="input-container" id="hobbies-container">
            <input type="text" name="hobbies[]" placeholder="¿Qué actividades disfruta?"><br>
        </div>
        <button type="button" class="add-button" onclick="addInput('hobbies-container')">Añadir Hobby</button>

        <h2> Personalidades<span class="section-icon">🤔</span></h2>
        <div class="input-container" id="personalities-container">
            <input type="text" name="personalities[]" placeholder="Describe su personalidad..."><br>
        </div>
        <button type="button" class="add-button" onclick="addInput('personalities-container')">Añadir Personalidad</button>

        <input type="submit" value="✿Crear Personaje✿">
    </form>
    
    <a href="../index.php" class="home-link">← Volver al inicio</a>
</body>
</html>

<script>
    function addInput(containerId) {
        var container = document.getElementById(containerId);
        var input = document.createElement("input");
        input.type = "text";
        input.name = containerId.replace("-container", "") + "[]";
        input.placeholder = containerId.replace("-container", "").charAt(0).toUpperCase() + containerId.replace("-container", "").slice(1) + " " + (container.children.length + 1);
        container.appendChild(input);
        container.appendChild(document.createElement("br"));
    }
</script>