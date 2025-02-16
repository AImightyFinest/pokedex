<?php
function english_type_name($french_type_name) {
	switch (trim($french_type_name)) {
		case 'Feu':
			return 'Fire';
		case 'Eau':
			return 'Water';
		case 'Plante':
			return 'Grass';
		case 'Insecte':
			return 'Bug';
		case 'Normal':
			return 'Normal';
		case 'Poison':
			return 'Poison';
		case 'Fée':
			return 'Fairy';
		case 'Vol':
			return 'Flying';
		case 'Combat':
			return 'Fighting';
		case 'Sol':
			return 'Ground';
		case 'Roche':
			return 'Rock';
		case 'Spectre':
			return 'Ghost';
		case 'Acier':
			return 'Steel';
		case 'Psy':
			return 'Psychic';
		case 'Électrik':
			return 'Electric';
		case 'Glace':
			return 'Ice';
		case 'Dragon':
			return 'Dragon';
		case 'Ténèbres':
			return 'Dark';
		case 'Fer':
			return 'Steel';
		default:
			return $french_type_name;
			break;
	}
}

function getPokemons() {
	$pokemons = [];

	$file = fopen("pokemons.csv","r");

	while (($data = fgetcsv($file, null, ",", '"', "\\")) !== FALSE) {
		array_push($pokemons, $data);
	}

	fclose($file);
	return $pokemons;
}

function get_pokemon($name) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://tyradex.app/api/v1/pokemon/" . $name,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
         "Content-Type: application/json",
         "cache-control: no-cache"
        ),
        CURLOPT_RETURNTRANSFER => true,
    ));

    $response = curl_exec($curl);
    $data = json_decode($response, true);
    if (isset($data["status"])) {
        throw new Exception("Pokemon not found");
    }

    $name = isset($data["name"]["fr"]) ? $data["name"]["fr"] : "Unknown";
    $img_url = isset($data["sprites"]["shiny"]) ? $data["sprites"]["shiny"] : "";
    $catch_rate = isset($data["catch_rate"]) ? $data["catch_rate"] : "Unknown";
	$height = isset($data["height"]) ? $data["height"] : "Unknown";
	$weight = isset($data["weight"]) ? $data["weight"] : "Unknown";
	$stats = [
        "hp" => isset($data["stats"]["hp"]) ? $data["stats"]["hp"] : "Unknown",
        "atk" => isset($data["stats"]["atk"]) ? $data["stats"]["atk"] : "Unknown",
        "def" => isset($data["stats"]["def"]) ? $data["stats"]["def"] : "Unknown",
        "spe_atk" => isset($data["stats"]["spe_atk"]) ? $data["stats"]["spe_atk"] : "Unknown",
        "spe_def" => isset($data["stats"]["spe_def"]) ? $data["stats"]["spe_def"] : "Unknown",
        "vit" => isset($data["stats"]["vit"]) ? $data["stats"]["vit"] : "Unknown"
    ];
	$generation = isset($data["generation"]) ? $data["generation"] : "Unknown";

    $types = [];
    if (isset($data["types"])) {
        foreach ($data["types"] as $type) {
            array_push($types, $type["name"]);		
        }
    }


    return ["name" => $name, "type" => $types, "img" => $img_url, "catch_rate" => $catch_rate, "height" => $height, "weight" => $weight, "stats" => $stats, "generation" => $generation];
}

function retrieve_pokemon_data($name) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://tyradex.app/api/v1/pokemon/" . $name,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "cache-control: no-cache"
        ),
        CURLOPT_RETURNTRANSFER => true,
    ));

    $response = curl_exec($curl);
    $data = json_decode($response, true);
    if (isset($err)) {
        throw new Exception("Pokemon not found");
    }

    $name = $data["name"]["fr"];
    $img_url = $data["sprites"]["regular"];
    $catch_rate = isset($data["catch_rate"]) ? $data["catch_rate"] : "Unknown";
    $height = $data["height"];
    $weight = $data["weight"];
    $stats = [
        "hp" => isset($data["stats"]["hp"]) ? $data["stats"]["hp"] : "Unknown",
        "atk" => isset($data["stats"]["atk"]) ? $data["stats"]["atk"] : "Unknown",
        "def" => isset($data["stats"]["def"]) ? $data["stats"]["def"] : "Unknown",
        "spe_atk" => isset($data["stats"]["spe_atk"]) ? $data["stats"]["spe_atk"] : "Unknown",
        "spe_def" => isset($data["stats"]["spe_def"]) ? $data["stats"]["spe_def"] : "Unknown",
        "vit" => isset($data["stats"]["vit"]) ? $data["stats"]["vit"] : "Unknown"
    ];
    $generation = $data["generation"];

    $types = [];
    foreach ($data["types"] as $type) {
        array_push($types, $type["name"]);
    }

    return [$name, $types, $img_url, $catch_rate, $height, $weight, $stats, $generation];
}

function does_pokemon_exist($name) {
	$pokemons = getPokemons();

	foreach ($pokemons as $pokemon) {
		if (strtolower($pokemon[0]) == strtolower($name)) {
			return true;
		}
	}

	return false;
}

function save_pokemon_to_csv($name, $types, $img_url) {
    $file = fopen("pokemons.csv", "a");

    $type1 = isset($types[0]) ? $types[0] : '';
    $type2 = isset($types[1]) ? $types[1] : '';

    fputcsv($file, [$name, $type1, $type2, $img_url], ',', '"', "\\");

    fclose($file);
}

function display_pokemon_row($pokemon) {
    echo "<tr>";
    echo "<td> <a href='pokemon.php?pokemon=" . htmlspecialchars($pokemon[0]) . "'><img src='" . htmlspecialchars($pokemon[3]) . "' alt='" . htmlspecialchars($pokemon[0]) . "'></a></td>";
    echo "<td>" . htmlspecialchars($pokemon[0]) . "</td>";
    echo "<td>";
    if (isset($pokemon[1]) && $pokemon[1]) {
        echo "<span class='type " . strtolower(english_type_name($pokemon[1])) . "'>" . htmlspecialchars($pokemon[1]) . "</span>";
    }
    if (isset($pokemon[2]) && $pokemon[2]) {
        echo "<span class='type " . strtolower(english_type_name($pokemon[2])) . "'>" . htmlspecialchars($pokemon[2]) . "</span>";
    }
    echo "</td>";
    echo "</tr>";
}

function addPokemon($name) {
	try {
		$pokemon_exists = does_pokemon_exist($name);

		if ($pokemon_exists) {
			throw new Exception("Pokemon already exists");
		}

		list($name, $types, $img_url) = retrieve_pokemon_data($name);

		save_pokemon_to_csv($name, $types, $img_url);

	} catch (\Throwable $th) {
		// On intercepte l'exception (l'erreur) et on return son message
		return $th->getMessage();
	}
}
?>
