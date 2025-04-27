CREATE DATABASE pokedex;
USE pokedex;
-- Table structure for `pokemon`
-- --------------------------------------------------------
-- DROP TABLE IF EXISTS `pokemon`;
-- --------------------------------------------------------

CREATE TABLE pokemon (
    id INT PRIMARY KEY,
    name VARCHAR(50),
    type1 VARCHAR(20),
    type2 VARCHAR(20),
    generation INT,
    sprite_url VARCHAR(255)
);

INSERT INTO pokemon (id, name, type1, type2, generation, sprite_url)
VALUES (1, 'Bulbasaur', 'Grass', 'Poison', 1, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/1.png');
INSERT INTO pokemon (id, name, type1, type2, generation, sprite_url)
VALUES (2, 'Charmander', 'Fire', NULL, 1, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/4.png');
INSERT INTO pokemon (id, name, type1, type2, generation, sprite_url)
VALUES (3, 'Squirtle', 'Water', NULL, 1, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/7.png');
INSERT INTO pokemon (id, name, type1, type2, generation, sprite_url)
VALUES (4, 'Pikachu', 'Electric', NULL, 1, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/25.png');
INSERT INTO pokemon (id, name, type1, type2, generation, sprite_url)
VALUES (5, 'Eevee', 'Normal', NULL, 1, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/133.png');

