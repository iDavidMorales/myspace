<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuentra Amigos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #e9eff2; /* Color de fondo más suave */
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        #users-container {
            width: 100%; /* Ocupa el 100% del ancho */
            max-width: 1200px; /* Límite máximo para pantallas grandes */
            background: #ffffff; /* Fondo blanco para el contenedor */
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
            overflow: auto; /* Permite el scroll si es necesario */
        }

        h2 {
            margin-bottom: 20px;
            color: #1877f2; /* Azul de Facebook */
            font-size: 2em; /* Aumentar tamaño del título */
        }

        #user-list {
            display: flex;
            flex-wrap: wrap; /* Permite que las tarjetas se ajusten en varias filas */
            justify-content: space-between; /* Espacio entre las tarjetas */
        }

        .user-card {
            display: inline-block; /* Tarjetas como bloques en línea */
            width: calc(20% - 10px); /* Hasta cinco tarjetas por fila */
            border: 2px solid #e9eff2; /* Bordes suaves */
            background-color: #f1f5f9; /* Color de fondo de las tarjetas */
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            transition: box-shadow 0.3s, transform 0.3s;
            text-align: left; /* Alineación del texto */
        }

        .user-card:hover {
            box-shadow: 0 8px 30px rgba(24, 123, 242, 0.2); /* Sombra más pronunciada en hover */
            transform: translateY(-3px);
        }

        .user-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 15px; /* Margen inferior para separar imagen del texto */
            border: 2px solid #1877f2; /* Borde de la imagen */
        }

        .user-info h3 {
            margin: 0;
            font-size: 1.2em;
            color: #1877f2; /* Azul de Facebook */
        }

        .user-info p {
            margin: 5px 0;
            color: #555;
            overflow: hidden; /* Evita que el texto se desborde */
            text-overflow: ellipsis; /* Muestra puntos suspensivos si hay desbordamiento */
            white-space: nowrap; /* Evita el salto de línea */
        }

        .user-info a {
            color: #1c1e21; /* Color del enlace */
            text-decoration: none;
            font-weight: bold;
            white-space: nowrap; /* Evita el salto de línea */
        }

        .user-info a:hover {
            text-decoration: underline;
        }

        .view-profile-btn, .message-btn, .refresh-btn {
            background-color: #42b72a; /* Verde de Facebook */
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px; /* Separar botones del texto */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Sombra suave */
        }

        .view-profile-btn:hover, .message-btn:hover, .refresh-btn:hover {
            background-color: #36a420; /* Color más oscuro en hover */
        }

        .view-profile-btn i, .message-btn i, .refresh-btn i {
            margin-right: 5px;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .user-card {
                width: calc(100% - 10px); /* Tarjetas en una sola columna en pantallas pequeñas */
            }
        }
    </style>
</head>
<body>
    <div id="users-container">
        <h2>Encuentra Nuevos Amigos</h2>
        <div class="button-container">
            <button class="refresh-btn" onclick="fetchUsers()">
                <i class="fas fa-sync-alt"></i> Actualizar
            </button>
        </div>
        <div id="user-list"></div>
    </div>

    <script>
        async function fetchUsers() {
            const userList = document.getElementById('user-list');
            userList.innerHTML = '<p>Cargando usuarios...</p>'; // Mensaje de carga

            try {
                const response = await fetch('https://routicket.com/api/get_users/');
                if (!response.ok) {
                    throw new Error('Error en la respuesta de la API');
                }
                const data = await response.json();
                const users = data.results || [];

                userList.innerHTML = ''; // Limpiar lista de usuarios

                if (users.length === 0) {
                    userList.innerHTML = '<p>No hay usuarios disponibles.</p>';
                    return;
                }

                users.forEach(user => {
                    // Crear estructura HTML para cada usuario
                    const userCard = document.createElement('div');
                    userCard.className = 'user-card';

                    userCard.innerHTML = `
                        <img src="${user.picture.large}" alt="Foto de ${user.nombre}">
                        <div class="user-info">
                            <h3>${user.nombre}</h3>
                            <p>Ciudad: ${user.ciudad || 'No especificada'}</p>
                            <p>Email: <a href="mailto:${user.email}">${user.email}</a></p>
                        </div>
                        <button class="view-profile-btn" onclick="window.open('https://routicket.com/inicio/profile.php?id_profile=${user.user_id}', '_blank')">
                            <i class="fas fa-user"></i> Ver Perfil
                        </button>
                        <button class="message-btn" onclick="window.open('https://routicket.com/plugins/box/index.php?user=${user.user_id}', '_blank')">
                            <i class="fas fa-comment"></i> Mandar Mensaje
                        </button>
                    `;

                    userList.appendChild(userCard);
                });
            } catch (error) {
                console.error('Error al obtener usuarios:', error);
                userList.innerHTML = '<p>No se pudo cargar la lista de usuarios.</p>';
            }
        }

        document.addEventListener('DOMContentLoaded', fetchUsers);
    </script>
</body>
</html>
