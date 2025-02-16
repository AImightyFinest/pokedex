<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="static/main.css">
    <title>Document</title>
</head>
<body>
    <div class="pokedex">
        <h1>Pokédex</h1>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    require 'pokemons.php';

                    if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['_method'])) {
                        // On recupere la potentielle erreur
                        $err_msg = addPokemon($_POST["name"]);
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'DELETE') {
                        $pokemon_name = $_POST['pokemon_name'];
                        remove_pokemon_from_team($pokemon_name);
                    }

                    $pokemons = getPokemons();

                    foreach ($pokemons as $pokemon) {
                        display_pokemon_row($pokemon);
                    }
                ?>  
            </tbody>
        </table>
        <form class="add-pokemon" action="index.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name">

            <button type="submit">Add Pokemon</button>
        </form>
        <?php
            // Si on a une erreur, on l'affiche
            if (isset($err_msg)) {
                echo "<p class='error'>$err_msg</p>";
            }

            function remove_pokemon_from_team($pokemon_name) {
                $file = 'pokemons.csv';
                $temp_file = 'temp.csv';

                if (!file_exists($file)) {
                    echo "File not found.";
                    return;
                }

                $input = fopen($file, 'r');
                if ($input === false) {
                    echo "Failed to open input file.";
                    return;
                }

                $output = fopen($temp_file, 'w');
                if ($output === false) {
                    fclose($input);
                    echo "Failed to open temp file.";
                    return;
                }

                while (($data = fgetcsv($input, 0, ',', '"', '\\')) !== FALSE) {
                    if ($data[0] !== $pokemon_name) {
                        fputcsv($output, $data, ',', '"', '\\');
                    }
                }

                fclose($input);
                fclose($output);

                // Remplacer l'ancien fichier par le nouveau
                rename($temp_file, $file);
            }
        ?>
    </div>  
</body>
</html>
