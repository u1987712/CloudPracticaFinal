<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include_once "connection.php";

    function getAllCharacters() {
        $conn = connect();
        $characters = array();
    
        $sql = "SELECT Id, name, age FROM Characters";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['Id'];
            $characters[] = [
                'id' => $id,
                'name' => $row['name'],
                'age' => $row['age'],
                'likes' => getLikesCharacter($id, $conn),
                'dislikes' => getDislikesCharacter($id, $conn),
                'hobbies' => getHobbiesCharacter($id, $conn),
                'personalities' => getPersonalitiesCharacter($id, $conn),
            ];
        }
    
        return $characters;
    }
    
    function getCharacterName($id) {
        $conn = connect();
        
        $sql = "SELECT name FROM Characters WHERE Id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && isset($result['name'])) {
            return $result['name'];
        }
        
        return null;
    }

    function getLikesCharacter($id, $conn) {
        $likes = array();
    
        $sql = "SELECT likes FROM Likes WHERE idCharacter = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $likes[] = $row['likes'];
        }
    
        return $likes;
    }
    
    function getDislikesCharacter($id, $conn) {
        $dislikes = array();
    
        $sql = "SELECT dislikes FROM Dislikes WHERE idCharacter = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $dislikes[] = $row['dislikes'];
        }
    
        return $dislikes;
    }
    
    function getHobbiesCharacter($id, $conn) {
        $hobbies = array();
    
        $sql = "SELECT hobbies FROM Hobbies WHERE idCharacter = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $hobbies[] = $row['hobbies'];
        }
    
        return $hobbies;
    }
    
    function getPersonalitiesCharacter($id, $conn) {
        $personalities = array();
    
        $sql = "SELECT personalities FROM Personalities WHERE idCharacter = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $personalities[] = $row['personalities'];
        }
    
        return $personalities;
    }    

    function createCharacter($character) {
        $conn = connect();
    
        $prompt = generatePrompt($character);
        $sql = "INSERT INTO Characters (name, age, prompt) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$character['name'], $character['age'], $prompt]);
    
        $characterId = $conn->lastInsertId(); // Correct for PDO
    
        // Save likes
        foreach ($character['likes'] as $like) {
            saveLike($characterId, $like, $conn);
        }
    
        // Save dislikes
        foreach ($character['dislikes'] as $dislike) {
            saveDislike($characterId, $dislike, $conn);
        }
    
        // Save hobbies
        foreach ($character['hobbies'] as $hobby) {
            saveHobby($characterId, $hobby, $conn);
        }
    
        // Save personalities
        foreach ($character['personalities'] as $personality) {
            savePersonality($characterId, $personality, $conn);
        }
    }
    
    function saveLike($characterId, $like, $conn) {
        $sql = "INSERT INTO Likes (idCharacter, likes) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$characterId, $like]);
    }
    
    function saveDislike($characterId, $dislike, $conn) {
        $sql = "INSERT INTO Dislikes (idCharacter, dislikes) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$characterId, $dislike]);
    }
    
    function saveHobby($characterId, $hobby, $conn) {
        $sql = "INSERT INTO Hobbies (idCharacter, hobbies) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$characterId, $hobby]);
    }
    
    function savePersonality($characterId, $personality, $conn) {
        $sql = "INSERT INTO Personalities (idCharacter, personalities) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$characterId, $personality]);
    }    

    function generatePrompt($character) {
        $prompt = "Eres un personaje llamado " . $character['name'] . ".\n";
        $prompt .= "Edad: " . $character['age'] . "\n";
        $prompt .= "Gustos: " . implode(", ", $character['likes']) . "\n";
        $prompt .= "Disgustos: " . implode(", ", $character['dislikes']) . "\n";
        $prompt .= "Hobbies: " . implode(", ", $character['hobbies']) . "\n";
        $prompt .= "Personalidades: " . implode(", ", $character['personalities']) . "\n";
        $prompt .= "La idea es que eres un personaje de ficción que debes actuar según las características que te he dado. No puedes perder ninguna personalidad y debes ceñirte a tu forma de ser.\n";
        return $prompt;
    }

    function getPromptCharacter($id) {
        $conn = connect();
    
        $sql = "SELECT prompt FROM Characters WHERE Id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $row['prompt'];
    }

?>