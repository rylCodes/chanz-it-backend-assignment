<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word to Number Converter</title>
</head>
<body>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            padding: 20px;
            margin: 20px auto;
            max-width: 600px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            margin: 50px auto 0;
        }

        label {
            font-size: 18px;
        }

        input[type="text"] {
            width: 300px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: auto;
            margin-top: 10px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px
        }

        button:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
            font-size: 18px;
        }

        .result-container {
            background-color: #e6f7ff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .error-container {
            background-color: #ffe6e6;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .result, .converted, .error, .input {
            text-align: center;
            font-size: 18px;
        }

        .input {
            margin-top: 30px;
        }
    </style>

    <div class="container">
        <h1>Word to Number Converter</h1>
        <form action="" method="post">
            <label for="input">Enter Words or Numbers:</label>
            <input type="text" id="input" name="input">
            <button type="submit">Convert</button>
        </form>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $input = $_POST["input"];
                $input = str_replace(",", "", $input);

                require_once 'controller/ConverterController.php';
                $controller = new ConverterController();
                echo "<p class='input'>Input: <strong>$input</strong></p>";
                
                if (is_numeric($input)) {
                    $result = $controller->numberToWords($input);
                    echo "<div class='result-container'>";
                    echo "<p class='result'>Result: <strong>$result</strong></p>";
                    echo "<p class='converted'>PHP—USD: <strong>$" . $controller->convertToUSD($input) . "</strong></p>";
                    echo "</div>";
                        
                } else {
                    $result = $controller->wordsToNumber($input);
                    if ($result === false) {
                        echo "<div class='error-container'>";
                        echo "<p class='error'>Invalid input. Please enter a valid word or number.</p>";
                        echo "</div>";
                    } else {
                        echo "<div class='result-container'>";
                        echo "<p class='result'>Result: <strong>$result</strong></p>";
                        echo "<p class='converted'>PHP—USD: <strong>$" . $controller->convertToUSD(str_replace(',', '', $result)) . "</strong></p>";
                        echo "</div>";                        
                    }
                }
            }
        ?>

    </div>
</body>
</html>
