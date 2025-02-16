<?php
require 'pokemons.php';
$pokemon = get_pokemon($_GET["pokemon"]);
if (!$pokemon) {
    echo "Pokémon not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="static/main.css">
    <title><?php echo htmlspecialchars($pokemon['name']); ?> - Pokédex</title>
</head>
<body>
    <div class="pokedex">
        <h1 class="pokemon-name"><?php echo htmlspecialchars($pokemon['name']); ?></h1>
        <img class="pokemon-img" src="<?php echo htmlspecialchars($pokemon['img'] ?? ''); ?>" alt="<?php echo htmlspecialchars($pokemon['name']); ?>">
        

        <p class="pokemon-info">Type:</p>
        <?php echo htmlspecialchars(implode(', ', (array)($pokemon['type'] ?? []))); ?></p>
        <div class="pokemon-info-group">
            <p class="pokemon-info">Catchrate:</p>
            <?php echo htmlspecialchars(implode(', ', (array)($pokemon['catch_rate']?? []))); ?></p>
            <p class="pokemon-info">Height:</p>
<?php echo htmlspecialchars(implode(', ', (array)($pokemon['height'] ?? []))); ?></p> 
            <p class="pokemon-info">Weight:</p>
<?php echo htmlspecialchars(implode(', ', (array)($pokemon['weight'] ?? []))); ?></p>
            <p class="pokemon-info">Generation:</p>
<?php echo htmlspecialchars(implode(', ', (array)($pokemon['generation'] ?? []))); ?></p>
        </div>
        
        <p class="pokemon-info">Stats:</p>
        <div class="pokemon-stats-group">
            <ul class="pokemon-stats">
                <li>Attack: <?php echo htmlspecialchars($pokemon['stats']['atk'] ?? 'N/A'); ?></li>
                <li>Defense: <?php echo htmlspecialchars($pokemon['stats']['def'] ?? 'N/A'); ?></li>
                <li>Speed: <?php echo htmlspecialchars($pokemon['stats']['vit'] ?? 'N/A'); ?></li>
            </ul>
            <ul class="pokemon-stats">
                <li>Special Attack: <?php echo htmlspecialchars($pokemon['stats']['spe_atk'] ?? 'N/A'); ?></li>
                <li>Special Defense: <?php echo htmlspecialchars($pokemon['stats']['spe_def'] ?? 'N/A'); ?></li>
                <li>HP: <?php echo htmlspecialchars($pokemon['stats']['hp'] ?? 'N/A'); ?></li>
            </ul>
        </div>
        
        <!-- Bouton de suppression -->
        <form action="index.php" method="post" onsubmit="return confirm('Are you sure you want to remove this Pokémon from your team?');">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="pokemon_name" value="<?php echo htmlspecialchars($pokemon['name']); ?>">
            <button type="submit" class="delete-button">Remove</button>
        </form>
    </div>
</body>
</html>