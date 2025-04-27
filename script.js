$(document).ready(function() {
    // Función para cargar todos los Pokémon al inicio
    cargarPokemon();
    
    // Evento para el botón de búsqueda
    $('#searchButton').click(function() {
        cargarPokemon();
    });
    
    // También permitir búsqueda al presionar Enter en el campo de texto
    $('#pokemonSearch').keypress(function(e) {
        if (e.which === 13) {
            cargarPokemon();
        }
    });
    
    // Función para cargar Pokémon según los filtros
    function cargarPokemon() {
        // Obtener valores de los filtros
        const nombre = $('#pokemonSearch').val();
        const tipo = $('#typeFilter').val();
        const generacion = $('#generationFilter').val();
        
        // Mostrar indicador de carga
        $('#pokemonGrid').html('<div class="loading">Cargando...</div>');
        
        // Realizar petición AJAX
        $.ajax({
            url: 'buscar.php',
            type: 'POST',
            data: {
                nombre: nombre,
                tipo: tipo,
                generacion: generacion
            },
            success: function(response) {
                // Limpiar el grid
                $('#pokemonGrid').empty();
                
                // Convertir la respuesta a objeto si es string
                const resultados = typeof response === 'string' ? JSON.parse(response) : response;
                
                // Verificar si hay resultados
                if (resultados.length > 0) {
                    // Ocultar mensaje de no resultados
                    $('#noResults').hide();
                    
                    // Mostrar cada Pokémon
                    resultados.forEach(function(pokemon) {
                        // Crear tarjeta para el Pokémon
                        const card = $('<div class="pokemon-card"></div>');
                        
                        // Añadir imagen
                        card.append(`<img src="${pokemon.sprite_url}" alt="${pokemon.name}">`);
                        
                        // Añadir nombre
                        card.append(`<h3>${pokemon.name}</h3>`);
                        
                        // Añadir tipos
                        const tiposDiv = $('<div class="pokemon-types"></div>');
                        tiposDiv.append(`<span class="type-badge ${pokemon.type1}">${pokemon.type1}</span>`);
                        
                        // Añadir segundo tipo si existe
                        if (pokemon.type2) {
                            tiposDiv.append(`<span class="type-badge ${pokemon.type2}">${pokemon.type2}</span>`);
                        }
                        
                        card.append(tiposDiv);
                        
                        // Añadir tarjeta al grid
                        $('#pokemonGrid').append(card);
                    });
                } else {
                    // Mostrar mensaje de no resultados
                    $('#noResults').show();
                }
            },
            error: function() {
                $('#pokemonGrid').html('<div class="error">Error al cargar los datos</div>');
            }
        });
    }
});
