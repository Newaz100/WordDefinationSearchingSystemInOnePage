<!DOCTYPE html>
<html>
<head>
    <title>Dictionary</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="container">
    <h2>DICTIONARY</h2>
    <form method="post">
        <label>What Word Do You Want To Search?</label><br><br>
        <input type="text" name="word" ><br><br>
        <input type="submit" value="Submit">
    </form>
</div>

</body>
</html>

<?php
$conn = new mysqli('localhost', 'root', '', 'dictionarydb');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['word'])) {
    $word = $_POST['word'];
    if (empty($word)) {
        echo "<h3 class='error-message'>Error: Please enter a word to search.</h3>";
    } else {
        $sql = "SELECT Defination FROM word_def WHERE Word = '$word'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<div class='definition-box'>"; 
            echo "<h3>Definition of " .$word. ":</h3>";
            echo "<p>" .$row['Defination'] ."</p>";
            echo "</div>";
        } else {
            echo "<h3>The word '". $word. "' was not found in the dictionary.</h3>";
            echo "<form method='post'>
                    <div class='container'>
                    <label>Do you want to add the definition for this word?</label>
                    <input type='hidden' name='new_word' value='".$word. "'>
                    <input type='radio' name='add_def' value='yes'> Yes<br>
                    <input type='radio' name='add_def' value='no'> No<br><br>
                    
                    <input type='submit' value='Submit'>
                    </div>
                  </form>";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_def'])) {
    if ($_POST['add_def'] == 'yes' && isset($_POST['new_word'])) {
        $new_word = $_POST['new_word'];
        
        echo "<form method='post'>
                <div class='container'>
                <label>Enter the definition for '" . $new_word. "':</label><br>
                <textarea name='definition' required></textarea><br><br>
                <input type='hidden' name='addingWord' value='" . $new_word . "'>
                <input type='submit' value='Add Definition'>
                </div>
              </form>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addingWord']) && isset($_POST['definition'])) {
    $addingWord = $_POST['addingWord'];
    $definition = $_POST['definition'];

    
    if (empty($addingWord) || empty($definition)) {
        echo "<h3 class='error-message'>Error: Both the word and its definition must be filled out.</h3>";
    }
    elseif (preg_match('/\d/', $addingWord) || preg_match('/\d/', $definition)) {
        echo "<h3 class='error-message'>Error: The word and definition should not contain numeric data.</h3>";
        } else {
        $sql = "INSERT INTO word_def (Word, Defination) VALUES ('$addingWord', '$definition')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<h3 class='success-message'>The word '" . $addingWord . "' and its definition were successfully added to the dictionary.</h3>";
        } 

    }
}
?>


