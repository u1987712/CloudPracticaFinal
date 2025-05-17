<?php
    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // PHP Data Objects(PDO) Sample Code:
    function connect() {
        $serverName = "chatbotdbudg.database.windows.net";
        $database = "chatbotDB";
        $username = "asanchezsa04";
        $password = "Pinguino123!";
    
        try {
            $conn = new PDO("sqlsrv:server=$serverName;Database=$database", $username, $password);
            // Enable exceptions for errors
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }


    /*try {
        $conn = new PDO("sqlsrv:server = tcp:chatbotdbudg.database.windows.net,1433; Database = chatbotDB", "asanchezsa04", "Pinguino123!");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e) {
        print("Error connecting to SQL Server.");
        die(print_r($e));
    }

    // SQL Server Extension Sample Code:
    $connectionInfo = array("UID" => "asanchezsa04", "pwd" => "Pinguino123!", "Database" => "chatbotDB", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
    $serverName = "tcp:chatbotdbudg.database.windows.net,1433";
    $conn = sqlsrv_connect($serverName, $connectionInfo);
    ?>*/

?>