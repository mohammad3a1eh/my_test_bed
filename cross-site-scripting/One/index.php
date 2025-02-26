<?php 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["name"])) {
    $name = $_POST["name"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {

    header("Content-Type: application/json");
    $data = file_get_contents("php://input");
    error_log("Raw JSON Input: " . $data);
    $data = json_decode($data, true);
    
    $name = $data["name"];
    $response = array("message" => "Hello $name (JSON)");
    echo json_encode($response);
    die();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crros-site scripting</title>

    <style>
    body {
        background-color: #0d1117;
        color: #c9d1d9;
        font-family: Arial, sans-serif;
    }
    
    h2 {
        color: #58a6ff;
    }

    form, div {
        background-color: #161b22;
        padding: 16px;
        border-radius: 6px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin: 20px 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input {
        width: 50%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #30363d;
        border-radius: 4px;
        background-color: #0d1117;
        color: #c9d1d9;
    }

    button {
        background-color: #238636;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        width: 200px;
    }

    button:hover {
        background-color: #2ea043;
    }

    pre {
        background-color: #161b22;
        padding: 10px;
        border-radius: 6px;
        max-width: 400px;
        overflow-x: auto;
    }

    .name {
        color: #58a6ff;
        font-weight: bold;
    }
</style>


</head>
<body>
    <h2>Send Data</h2>
    <form action="index.php" method="post">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name">
        <button type="submit">Send (urlencoded)</button>
    </form>

    <form action="index.php" method="post" enctype="multipart/form-data">
        <label for="name_multipart">Name:</label>
        <input type="text" name="name" id="name_multipart">
        <button type="submit">Send (multipart)</button>
    </form>

    <h2>Send JSON Data (JS)</h2>
    <div>
        <label for="name_json">Name:</label>
        <input type="text" name="name" id="name_json">
        <button onclick="sendJSON()">Send (JSON)</button>
    </div>
    

    <script>
        function sendJSON() {
            var name = document.getElementById("name_json").value;
            var data = { "name": name };

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "index.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    document.getElementsByClassName("name")[0].innerHTML = response.message
                }
            };
            xhr.send(JSON.stringify(data));
        }

        function getParameterByName(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return "";
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }

        var redirectParam = getParameterByName("redirect");

        if (redirectParam && !redirectParam.startsWith("javascript:")) {
            windwo.location = redirectParam;
        }

        window.addEventListener("message", function (e) {
            if (e.origin && e.data) {
                let t;
                try {
                    t = JSON.parse(e.data);
                } catch (e) { }

                if (t && typeof t === "object" && t.name) {
                    // Empty
                }
                if (t && typeof t === "object" && t.goto) {
                    window.location = t.goto;
                }
            }
        });

    </script>

    <h2>Result</h2>
    <div class="name"><?php echo (isset($name)) ? $name : "" ?></div>
    <?php echo (isset($_GET["no_space"]) && strstr($_GET["no_space"], " ") === FALSE) ? $_GET["no_space"] : "" ?>
</body>
</html>