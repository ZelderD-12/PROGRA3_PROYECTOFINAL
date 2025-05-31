<?php
include '../Base de Datos/operaciones.php';
$edificios = saberEdificios();
$salones = saberSalones();

function transformarUbicaciones($ubicacionesBD)
{
    $ubicacionesPlanas = [];

    if (isset($ubicacionesBD['ubicaciones'])) {
        foreach ($ubicacionesBD['ubicaciones'] as $ubicacion) {
            // Normaliza los valores vacíos a 0 o ""
            $idEdificio = !empty($ubicacion['idEdificio']) ? (int)$ubicacion['idEdificio'] : 0;
            $edificio   = !empty($ubicacion['edificio']) ? $ubicacion['edificio'] : '';
            $idPuerta   = !empty($ubicacion['idPuerta']) ? (int)$ubicacion['idPuerta'] : 0;
            $puerta     = !empty($ubicacion['puerta']) ? $ubicacion['puerta'] : '';
            $nivel      = !empty($ubicacion['nivel']) ? (int)$ubicacion['nivel'] : 0;
            $idSalon    = !empty($ubicacion['idSalon']) ? (int)$ubicacion['idSalon'] : 0;
            $salon      = !empty($ubicacion['salon']) ? $ubicacion['salon'] : '';

            $ubicacionesPlanas[] = [
                'idEdificio' => $idEdificio,
                'edificio'   => $edificio,
                'idPuerta'   => $idPuerta,
                'puerta'     => $puerta,
                'nivel'      => $nivel,
                'idSalon'    => $idSalon,
                'salon'      => $salon
            ];
        }
    }

    return $ubicacionesPlanas;
}

// Obtener datos del edificio (ejemplo con ID 1)
$idEdificio = 1;
$ubicacionesBD = obtenerUbicacionesPorEdificio($idEdificio);
$ubicacionesTransformadas = transformarUbicaciones($ubicacionesBD);

// Preparar los datos para JavaScript
$datosParaJS = [
    'ubicaciones' => $ubicacionesTransformadas,
    'usuarios' => [] // Array vacío por ahora
];


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="../../CSS/style2.css">
    <link rel="stylesheet" href="../../CSS/tabla.css">
    <script>
        const edificiosBD = <?php echo json_encode($edificios); ?>;
        const salonesBD = <?php echo json_encode($salones); ?>;
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        .datos-usuario-container {
            font-family: Arial, sans-serif;
            max-width: 750px;
            margin: 0 auto;
            font-size: 12px;
            line-height: 1.3;
            padding: 10px;
        }

        .perfil-usuario {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .foto-perfil img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }

        .info-personal {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .campo-dato {
            margin-bottom: 4px;
        }

        label {
            font-weight: bold;
        }
    </style>



    <script>
        // Verificar autenticación
        document.addEventListener('DOMContentLoaded', function() {
            if (!sessionStorage.getItem('loggedIn')) {
                window.location.href = "../../index.php";
                return;
            }

            // Animación de carga para los elementos
            const elementos = document.querySelectorAll('.animated-load');
            elementos.forEach((elemento, index) => {
                elemento.style.animationDelay = `${index * 0.1}s`;
            });

            // Mostrar datos del usuario
            const usuario = JSON.parse(sessionStorage.getItem('usuario') || '{}');
            if (usuario.Nombres_Usuario) {
                document.getElementById('nombre-usuario').textContent = usuario.Nombres_Usuario;
                // Mostrar mensaje según el tipo de usuario
                const tipoUsuario = usuario.Id_Tipo_Usuario;
                const mensajeDiv = document.getElementById('mensaje-tipo');
                const panelBotones = document.getElementById('panel-botones');

                switch (tipoUsuario) {
                    case 1: // Administrador
                        mensajeDiv.innerHTML = "<strong>Eres un Administrador</strong>. Tienes acceso completo al sistema.";
                        panelBotones.innerHTML = `
                <h3>Panel de Administrador</h3>
                <div class="botones-container">
                    <!-- Gestor de Usuarios (desplegable) -->
                    <div class="dropdown">
                        <button class="dropdown-btn" onclick="toggleDropdown('gestorUsuarios')">
                            <span><i class="fas fa-users-cog"></i> Gestor de Usuarios</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div id="gestorUsuarios" class="dropdown-content">
                            <a href="#" class="dropdown-item" onclick="mostrarInformacionAdmin('Vista Catedráticos')">
                                <i class="fas fa-chalkboard-teacher"></i> Vista Catedráticos
                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarInformacionAdmin('Estudiantes')">
                                <i class="fas fa-user-graduate"></i> Vista Estudiantes
                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarInformacionAdmin('Administrador')">
                                <i class="fas fa-user-shield"></i> Vista Administradores
                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarInformacionAdmin('Desarrolladores')">
                                <i class="fas fa-code"></i> Vista Desarrolladores
                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarInformacionAdmin('Servicios')">
                                <i class="fas fa-cogs"></i> Vista Servicios
                            </a>
                        </div>
                    </div>
                    
                    <!-- Administrar Usuarios (desplegable) -->
                    <div class="dropdown">
                        <button class="dropdown-btn" onclick="toggleDropdown('administrarUsuarios')">
                            <span><i class="fas fa-user-edit"></i> Administrar Usuarios</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div id="administrarUsuarios" class="dropdown-content">
                            <a href="#" class="dropdown-item" onclick="gestionarUsuarios('eliminar')">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </a>
                            <a href="../Registro/IngresoDatos.php" class="dropdown-item" onclick="gestionarUsuarios('agregar')">
                                <i class="fas fa-user-plus"></i> Agregar
                            </a>
                            <a href="#" class="dropdown-item" onclick="gestionarUsuarios('buscar')">
                                <i class="fas fa-search"></i> Buscar
                            </a>
                        </div>
                    </div>
                    
                    <!-- Cursos Asignados -->
                    <button onclick="mostrarCursosAsignados()" class="btn-dev">
                        <i class="fas fa-book"></i> Cursos Asignados
                    </button>
                    
                    <!-- Asistencias Generales -->
                    <button onclick="abrirAsistenciasGenerales()" class="btn-dev">
                        <i class="fas fa-user-check"></i> Asistencias Generales
                    </button>
                    
                    <!-- Estadísticas -->
                    <button onclick="abrirEstadisticas()" class="btn-dev">
                        <i class="fas fa-chart-bar"></i> Estadísticas
                    </button>
                    
                    <!-- Reportes (desplegable) -->
                    <div class="dropdown">
                        <button class="dropdown-btn" onclick="toggleDropdown('generadorReportes')">
                            <span><i class="fas fa-chart-line"></i> Reportes</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div id="generadorReportes" class="dropdown-content">
                            <a href="#" class="dropdown-item" onclick="mostrarReporte('historicoEntrada')">
                                <i class="fas fa-history"></i> Reporte Histodico
                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarReporte('fechaEntrada')">
                                <i class="fas fa-calendar-alt"></i>  Reporte Historico por Fecha

                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarReporte('historicoSalon')">
                                <i class="fas fa-door-open"></i> Reporte por salón

                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarReporte('fechaSalon')">
                                <i class="fas fa-clipboard-list"></i> Reporte por salón por Fecha
                            </a>
                        </div>
                    </div>
                    
                    <!-- Configuración (desplegable) -->
                    <div class="dropdown">
                        <button class="dropdown-btn" onclick="toggleDropdown('configuracion')">
                            <span><i class="fas fa-cog"></i> Configuración</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div id="configuracion" class="dropdown-content">
                            <a href="#" class="dropdown-item" onclick="cambiarConfiguracion('Contraseñia')">
                                <i class="fas fa-language"></i> Restablecer contraseñia
                            </a>
                            
                              <a href="#" class="dropdown-item" data-opcion="datos" onclick="cambiarConfiguracion('datos')">
                                 <i class="fas fa-user-edit"></i> Datos Usuario
                                    </a>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            `;

                        break;

                    case 2: // Docente
                        mensajeDiv.innerHTML = "<strong>Eres un Docente</strong>. Puedes gestionar cursos y calificaciones.";
                        panelBotones.innerHTML = `
                <h3>Panel de Docente</h3>
                <div class="botones-container">
                    <!-- Cursos Asignados (misma función que en Administrador) -->
                    <button onclick="mostrarCursosAsignados()" class="btn-dev">
                        <i class="fas fa-book"></i> Cursos Asignados
                    </button>
                    
                    <!-- Confirmar Asistencia -->
                    <button onclick="confirmarAsistencia()" class="btn-dev">
                        <i class="fas fa-clipboard-check"></i> Confirmar Asistencia
                    </button>
                    
                    <!-- Reportes (misma función que en Administrador) -->
                    <div class="dropdown">
                        <button class="dropdown-btn" onclick="toggleDropdown('generadorReportes')">
                            <span><i class="fas fa-chart-line"></i> Reportes</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div id="generadorReportes" class="dropdown-content">
                            <a href="#" class="dropdown-item" onclick="mostrarReporte('historicoEntrada')">
                                <i class="fas fa-history"></i> Reporte Histodico
                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarReporte('fechaEntrada')">
                                <i class="fas fa-calendar-alt"></i>  Reporte Historico por Fecha

                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarReporte('historicoSalon')">
                                <i class="fas fa-door-open"></i> Reporte por salón

                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarReporte('fechaSalon')">
                                <i class="fas fa-clipboard-list"></i> Reporte por salón por Fecha
                            </a>
                        </div>
                    </div>
                    
                    <!-- Configuración (desplegable) -->
                    <div class="dropdown">
                        <button class="dropdown-btn" onclick="toggleDropdown('configuracion')">
                            <span><i class="fas fa-cog"></i> Configuración</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div id="configuracion" class="dropdown-content">
                            <a href="#" class="dropdown-item" onclick="cambiarConfiguracion('Contraseñia')">
                                <i class="fas fa-language"></i> Restablecer contraseñia
                            </a>
                            
                              <a href="#" class="dropdown-item" data-opcion="datos" onclick="cambiarConfiguracion('datos')">
                                 <i class="fas fa-user-edit"></i> Datos Usuario
                                    </a>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            `;

                        break;

                    case 3: // Estudiante
                        mensajeDiv.innerHTML = "<strong>Eres un Estudiante</strong>. Accede a tus cursos y horarios.";
                        panelBotones.innerHTML = `
                <h3>Panel de Estudiante</h3>
                <div class="botones-container">
                    <!-- Ver Asistencia -->
                    <button onclick="verAsistencia()" class="btn-dev">
                        <i class="fas fa-user-check"></i> Ver Asistencia
                    </button>
                    
                    <!-- Datos Usuario -->
                    <button onclick="cambiarConfiguracion('datos')" class="btn-dev">
                        <i class="fas fa-user-edit"></i> Datos Usuario
                    </button>
                </div>
            `;
                        break;

                    case 4: // Desarrollador
                        mensajeDiv.innerHTML = "<strong>Eres un Desarrollador</strong>. Gestiona matrículas y documentos.";
                        panelBotones.innerHTML = `
                <h3>Panel de Desarrollador</h3>
                <div class="botones-container">
                    <!-- Gestor de Usuarios (misma función que en Administrador) -->
                    <div class="dropdown">
                        <button class="dropdown-btn" onclick="toggleDropdown('gestorUsuarios')">
                            <span><i class="fas fa-users-cog"></i> Gestor de Usuarios</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div id="gestorUsuarios" class="dropdown-content">
                            <a href="#" class="dropdown-item" onclick="mostrarInformacionAdmin('Vista Catedráticos')">
                                <i class="fas fa-chalkboard-teacher"></i> Vista Catedráticos
                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarInformacionAdmin('Estudiantes')">
                                <i class="fas fa-user-graduate"></i> Vista Estudiantes
                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarInformacionAdmin('Administrador')">
                                <i class="fas fa-user-shield"></i> Vista Administradores
                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarInformacionAdmin('Desarrolladores')">
                                <i class="fas fa-code"></i> Vista Desarrolladores
                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarInformacionAdmin('Servicios')">
                                <i class="fas fa-cogs"></i> Vista Servicios
                            </a>
                        </div>
                    </div>
                    
                    <!-- Administrar Usuarios (misma función que en Administrador) -->
                    <div class="dropdown">
                        <button class="dropdown-btn" onclick="toggleDropdown('administrarUsuarios')">
                            <span><i class="fas fa-user-edit"></i> Administrar Usuarios</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div id="administrarUsuarios" class="dropdown-content">
                            <a href="#" class="dropdown-item" onclick="gestionarUsuarios('eliminar')">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </a>
                            <a href="../Registro/IngresoDatos.php" class="dropdown-item" onclick="gestionarUsuarios('agregar')">
                                <i class="fas fa-user-plus"></i> Agregar
                            </a>
                            <a href="#" class="dropdown-item" onclick="gestionarUsuarios('buscar')">
                                <i class="fas fa-search"></i> Buscar
                            </a>
                        </div>
                    </div>
                    
                    <!-- Reportes (misma función que en Administrador) -->
                    <div class="dropdown">
                        <button class="dropdown-btn" onclick="toggleDropdown('generadorReportes')">
                            <span><i class="fas fa-chart-line"></i> Reportes</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                       <div id="generadorReportes" class="dropdown-content">
                            <a href="#" class="dropdown-item" onclick="mostrarReporte('historicoEntrada')">
                                <i class="fas fa-history"></i> Reporte Histodico
                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarReporte('fechaEntrada')">
                                <i class="fas fa-calendar-alt"></i>  Reporte Historico por Fecha

                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarReporte('historicoSalon')">
                                <i class="fas fa-door-open"></i> Reporte por salón

                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarReporte('fechaSalon')">
                                <i class="fas fa-clipboard-list"></i> Reporte por salón por Fecha
                            </a>
                        </div>
                    </div>
                    
                    <!-- Asistencias Generales (misma función que en Administrador) -->
                    <button onclick="abrirAsistenciasGenerales()" class="btn-dev">
                        <i class="fas fa-user-check"></i> Asistencias Generales
                    </button>
                    
                    <!-- Estadísticas (misma función que en Administrador) -->
                    <button onclick="abrirEstadisticas()" class="btn-dev">
                        <i class="fas fa-chart-bar"></i> Estadísticas
                    </button>
                    
                    <!-- Configuración (desplegable) -->
                    <div class="dropdown">
                        <button class="dropdown-btn" onclick="toggleDropdown('configuracion')">
                            <span><i class="fas fa-cog"></i> Configuración</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div id="configuracion" class="dropdown-content">
                            <a href="#" class="dropdown-item" onclick="cambiarConfiguracion('Contraseñia')">
                                <i class="fas fa-language"></i> Restablecer contraseñia
                            </a>
                            
                              <a href="#" class="dropdown-item" data-opcion="datos" onclick="cambiarConfiguracion('datos')">
                                 <i class="fas fa-user-edit"></i> Datos Usuario
                                    </a>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            `;
                        break;

                    case 5: // Servicios
                        mensajeDiv.innerHTML = "<strong>Eres un invitado de servicio</strong>. Acceso limitado al sistema.";
                        panelBotones.innerHTML = `
                <h3>Panel de Servicios</h3>
                <div class="botones-container">
                    <!-- Ver Asistencia -->
                    <button onclick="verAsistencia()" class="btn-dev">
                        <i class="fas fa-user-check"></i> Ver Asistencia
                    </button>
                    
                    <!-- Configuración (desplegable) -->
                    <div class="dropdown">
                        <button class="dropdown-btn" onclick="toggleDropdown('configuracion')">
                            <span><i class="fas fa-cog"></i> Configuración</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div id="configuracion" class="dropdown-content">
                            <a href="#" class="dropdown-item" onclick="cambiarConfiguracion('Contraseñia')">
                                <i class="fas fa-language"></i> Restablecer contraseñia
                            </a>
                            
                              <a href="#" class="dropdown-item" data-opcion="datos" onclick="cambiarConfiguracion('datos')">
                                 <i class="fas fa-user-edit"></i> Datos Usuario
                                    </a>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            `;


                        break;

                    default:
                        mensajeDiv.innerHTML = "<strong>Tipo de usuario no reconocido</strong>. Contacta al administrador.";
                        document.body.style.backgroundColor = "#f5f5f5"; // Gris claro
                }
            } else {
                // Si no hay datos en sessionStorage, obtenerlos del servidor
                fetch('get_user.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) throw new Error(data.error);
                        document.getElementById('nombre-usuario').textContent = data.Nombres_Usuario;
                        sessionStorage.setItem('usuario', JSON.stringify(data));

                        // Recargar la página para mostrar los botones correctos
                        window.location.reload();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        window.location.href = "../../index.php";
                    });
            }
        });

        // Función para la configuración (nueva)
        function cambiarConfiguracion(tipo) {
            switch (tipo) {
                case 'Contraseñia':
                    document.getElementById('info-content').innerHTML = `
                <h3>Restablecer contraseñia</h3>
                <p>Cambie su contraseñia.</p>
            `;
                    break;
                case 'datos':
                    document.getElementById('info-content').innerHTML = `
                <h3>Datos Usuario</h3>
                <p>Actualice sus datos personales.</p>
            `;
                    break;
            }

        }

        // Cerrar todos los dropdowns
        function closeAllDropdowns() {
            const dropdowns = document.querySelectorAll('.dropdown-content');
            dropdowns.forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }

        // Función para mostrar/ocultar menús desplegables
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);

            // Si el dropdown actual ya está visible, solo lo cerramos
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            } else {
                // Primero cerramos todos los dropdowns
                closeAllDropdowns();
                // Luego abrimos el actual
                dropdown.classList.add('show');
            }
        }


        //Mostrar Tablas
        function mostrarInformacionAdmin(tipo) {
            // Muestra información específica para administradores
            document.getElementById('info-content').innerHTML = `
        <h3>Información de ${tipo}</h3>
        <p>Aquí se mostraría la información específica para ${tipo}.</p>
        <div class="usuarios-container">
            <table class="usuarios-tabla">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Carnet</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Celular</th>
                        <th>Tipo</th>
                        <th>Carrera</th>
                        <th>Seccion</th>
                    </tr>
                </thead>
                <tbody id="tabla-usuarios-body">
                    <tr>
                        <td colspan="9" style="text-align:center;">(Datos aquí...)</td>
                    </tr>
                </tbody>
            </table>
        </div>
    `;

            // Hacer la petición al archivo PHP
            fetch('../Base de Datos/obtener_usuarios.php?tipo=' + encodeURIComponent(tipo))
                .then(response => response.text())
                .then(data => {
                    document.getElementById('tabla-usuarios-body').innerHTML = data;
                })
                .catch(error => {
                    console.error('Error al obtener los datos:', error);
                    document.getElementById('tabla-usuarios-body').innerHTML = "<tr><td colspan='9' style='text-align:center;'>Error al cargar datos</td></tr>";
                });
        }
        /*----------------------AQUI SE MUESTRA LO DE OPERACIONES----------------------------------------------- */
        // Función para cambiar configuración
        function cambiarConfiguracion(opcion) {
            // Obtener el área de contenido principal
            const infoContent = document.getElementById('info-content');

            // Limpiar contenido anterior
            infoContent.innerHTML = '';


            if (opcion === 'datos') {
                // Recuperar los datos del usuario desde sessionStorage
                const usuarioData = JSON.parse(sessionStorage.getItem('usuario'));
                if (usuarioData) {
                    // Primero mostramos los datos que ya tenemos
                    infoContent.innerHTML = `
                    <div class="datos-usuario-container">
                    <h3>Datos Usuario</h3>
                    
                    <div class="perfil-usuario">
                        <div class="foto-perfil">
                            <img src="data:image/png;base64,${usuarioData.Foto_Usuario}" alt="Foto de perfil">
                        </div>
                        <div class="campo-dato">
                                <label>Carnet:</label>
                                <span>${usuarioData.Carnet_Usuario}</span>
                            </div>
                        <div class="info-personal">
                            <div class="campo-dato">
                                <label>Nombre y apellido:</label>
                                <span>${usuarioData.Nombres_Usuario} ${usuarioData.Apellidos_Usuario}</span>
                            </div>
                             
                            
                            <div class="campo-dato">
                                <label>Correo:</label>
                                <span>${usuarioData.Correo_Electronico_Usuario}</span>
                            </div>
                            <div class="campo-dato">
                                <label>Celular:</label>
                                <span>${usuarioData.Numero_De_Telefono_Usuario}</span>
                            </div>
                            <div class="campo-dato">
                                <label>Tipo:</label>
                                <span>${usuarioData.Tipo_Usuario}</span>
                            </div>
                            <div class="campo-dato">
                                <label>Carrera:</label>
                                <span>${usuarioData.Nombre_Carrera}</span>
                            </div>
                            
                            <div class="campo-dato">
                                <label>Sección:</label>
                                <span>${usuarioData.Seccion_Usuario}</span>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                <button id="boton-adicional" class="btn-adicional">
                        <i class="fas fa-file-pdf"></i> Generar PDF
                    </button>
            `;

                    // Configurar evento para el botón adicional
                    document.getElementById('boton-adicional').addEventListener('click', function() {
                        generarPDFAdmin();
                    });



                } else {
                    infoContent.innerHTML = `
                <div class="datos-usuario-container">
                    <h3>Datos Usuario</h3>
                    <p>No se encontraron datos del usuario. Por favor, inicie sesión nuevamente.</p>
                    <button id="boton-adicional" class="btn-adicional">
                        <i class="fas fa-file-pdf"></i> Generar PDF
                    </button>
                </div>
            `;

                    // Configurar evento para el botón adicional
                    document.getElementById('boton-adicional').addEventListener('click', function() {
                        generarPDFAdmin();
                    });
                }
            } else if (opcion === 'Contraseñia') {

                // Recuperar los datos del usuario desde sessionStorage
                const usuarioData = JSON.parse(sessionStorage.getItem('usuario'));
                if (usuarioData) {
                    infoContent.innerHTML = `
        <div class="restablecer-password-container">
            <h3>Restablecer contraseña</h3>
            <p>Cambie su contraseña.</p>
            
            <form id="form-restablecer-password" class="form-password">
                <div class="campo-password">
                    <label for="carnet">Carnet:</label>
                    <input type="text" id="carnet" name="carnet" required value="${usuarioData.Carnet_Usuario}" disabled>
                </div>
                
                <div class="campo-password">
                    <label for="metodo-verificacion">Método de verificación:</label>
                    <select id="metodo-verificacion" name="metodo-verificacion" required>
                        <option value="">Seleccione una opción</option>
                        <option value="correo">Por correo electrónico</option>
                        <option value="telefono">Por teléfono</option>
                        <option value="ambos">Ambos métodos</option>
                    </select>
                </div>
                
                <div id="campos-dinamicos">
                    <!-- Los campos aparecerán aquí dinámicamente -->
                </div>
                
                <div class="campo-password">
                    <label for="password-nueva">Contraseña:</label>
                    <input type="password" id="password-nueva" name="password-nueva" required>
                </div>
                
                <div class="campo-password">
                    <label for="password-confirmar">Confirmar contraseña:</label>
                    <input type="password" id="password-confirmar" name="password-confirmar" required>
                </div>
                
                <div class="botones-password">
                    <button type="submit" id="btn-cambiar-password" class="btn-cambiar">
                        <i class="fas fa-key"></i> Cambiar Contraseña
                    </button>
                    <button type="button" id="btn-cancelar-password" class="btn-cancelar">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
            </form>
        </div>
    `
                };


                // Selección de elementos del DOM
                const metodoSelect = document.getElementById('metodo-verificacion');
                const camposDinamicos = document.getElementById('campos-dinamicos');

                // Variable global para almacenar datos del SP
                let datosUsuarioSP = null;

                // Función para renderizar campos dinámicos con valores precargados
                function renderizarCampos(valor) {
                    let contenido = '';

                    if (valor === 'correo' || valor === 'ambos') {
                        const correoValor = datosUsuarioSP?.correo || '';
                        contenido += `
            <div class="campo-password">
                <label for="correo">Correo electrónico:</label>
                <input type="email" id="correo" name="correo" required value="${correoValor}" readonly>
            </div>
        `;
                    }

                    if (valor === 'telefono' || valor === 'ambos') {
                        const telefonoValor = datosUsuarioSP?.celular || '';
                        contenido += `
            <div class="campo-password">
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" required value="${telefonoValor}" readonly>
            </div>
        `;
                    }

                    camposDinamicos.innerHTML = contenido;
                }

                // ✅ Paso 4: Listener para el select
                metodoSelect.addEventListener('change', function() {
                    const valor = this.value;
                    renderizarCampos(valor);
                });


                fetch(`../Base de Datos/buscar_datos_carnet.php?carnet=${encodeURIComponent(usuarioData.Carnet_Usuario)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.correo || data.celular) {
                            datosUsuarioSP = data;
                            console.log("Datos encontrados:", datosUsuarioSP);

                            // Si ya hay una opción seleccionada, forzar renderizado
                            const selectedValue = metodoSelect.value;
                            if (selectedValue) {
                                renderizarCampos(selectedValue);
                            }
                        } else {
                            console.warn("No se encontró información para este carnet.");
                        }
                    })
                    .catch(error => {
                        console.error("Error al consultar datos:", error);
                    });

                // Capturar el formulario de restablecimiento de contraseña
                const formRestablecer = document.getElementById('form-restablecer-password');

                formRestablecer.addEventListener('submit', function(event) {
                    event.preventDefault(); // Prevenir envío tradicional

                    // Obtener los datos
                    const carnet = document.getElementById('carnet').value;
                    const password = document.getElementById('password-nueva').value;
                    const confirmPassword = document.getElementById('password-confirmar').value;
                    const email = document.getElementById('correo').value;

                    // Validar coincidencia de contraseñas
                    if (password !== confirmPassword) {
                        alert("Las contraseñas no coinciden.");
                        return;
                    }

                    // Enviar datos vía fetch al archivo PHP
                    fetch('../Base de Datos/actualizar_password.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `carnet=${encodeURIComponent(carnet)}&password=${encodeURIComponent(password)}&confirm_password=${encodeURIComponent(confirmPassword)}&email=${encodeURIComponent(email)}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert("✅ EXITOSO: " + data.message);

                                // Mostrar mensaje por defecto en el contenedor principal
                                document.getElementById('info-content').innerHTML = `
    <div class="info-placeholder">
        <i class="fas fa-info-circle"></i>
        <p>Selecciona una opción del panel izquierdo para ver la información</p>
    </div>
`;

                            } else {
                                alert("❌ ERROR: " + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error en la solicitud:', error);
                            alert("Error en la comunicación con el servidor.");
                        });
                });


            }

            // Cerrar el dropdown si está abierto
            cerrarDropdowns();
        }

        // Función para generar PDF
        function generarPDFAdmin() {
            const element = document.querySelector('.datos-usuario-container');

            const opt = {
                margin: 0, // sin márgenes para aprovechar todo el espacio
                filename: 'datos-usuario.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2,
                    useCORS: true,
                    scrollY: 0 // evita capturar con desplazamiento
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };


            // Convertir y descargar
            html2pdf().set(opt).from(element).save();
        }


        // Función auxiliar para cerrar dropdowns
        function cerrarDropdowns() {
            const dropdowns = document.querySelectorAll('.dropdown-content');
            dropdowns.forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
        /*---------------------------------------------------------------------- */
        function mostrarCursosAsignados() {
            // Función para mostrar cursos asignados (compartida entre admin y docente)
            document.getElementById('info-content').innerHTML = `
        <h3>Cursos Asignados</h3>
        <p>Listado de cursos asignados.</p>
    `;
        }

        function confirmarAsistencia() {
            // Función específica para docentes
            alert('Confirmando asistencia...');
        }
        //----------------------------------------------------------------------------------------------------------------
        function cargarCombobox(tipoReporte) {
            let opciones = [];

            if (tipoReporte === 'historicoEntrada' || tipoReporte === 'fechaEntrada') {
                opciones = edificiosBD.map(edificio => ({
                    id: edificio.idEdificio, // <-- nombre correcto
                    nombre: edificio.Edificio // <-- nombre correcto
                }));
            } else if (tipoReporte === 'historicoSalon' || tipoReporte === 'fechaSalon') {
                opciones = salonesBD.map(salon => ({
                    id: salon.idSalon,
                    nombre: salon.Area
                }));
            } else {
                return;
            }

            const comboBox = document.getElementById('report-options-select');
            if (!comboBox) return;

            comboBox.innerHTML = '';

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Seleccione una opción';
            defaultOption.disabled = true;
            defaultOption.selected = true;
            comboBox.appendChild(defaultOption);

            opciones.forEach(opcion => {
                const option = document.createElement('option');
                option.value = opcion.id;
                option.textContent = opcion.nombre;
                comboBox.appendChild(option);
            });

            comboBox.onchange = function() {
                window.selectedCombo = {
                    id: this.value,
                    nombre: this.options[this.selectedIndex].text
                };
                // Guardar el edificio seleccionado globalmente
                window.edificioSeleccionado = {
                    id: parseInt(this.value),
                    nombre: this.options[this.selectedIndex].text
                };
                console.log('Edificio seleccionado:', window.edificioSeleccionado);
            };
        }

        // Función principal para mostrar reportes
        let fechaSeleccionada = ""; // Variable global para la fecha

        function mostrarReporte(tipo) {
            const baseCSSPath = '../../CSS/avl-tree.css';
            const baseJSPath = '../../Javascript/avl-tree.js';

            let contenido = '';
            let containerId = '';
            let fechaSelector = '';
            let fechaHoy = new Date().toISOString().slice(0, 10);

            // Solo los reportes por fecha muestran el selector
            if (tipo === 'fechaEntrada' || tipo === 'fechaSalon') {
                fechaSelector = `
            <label for="fecha-reporte">Fecha:</label>
            <input type="date" id="fecha-reporte" onchange="fechaSeleccionada = this.value;" value="${fechaHoy}">
        `;
            } else {
                // Para los históricos, la fecha es la de hoy automáticamente
                fechaSeleccionada = fechaHoy;
            }

            switch (tipo) {
                case 'historicoEntrada':
                    containerId = 'avl-tree-historico';
                    contenido = `
                <h3>Reporte Histórico de Ingresos</h3>
                <p>Visualización del árbol AVL con datos históricos de ingresos (fecha: ${fechaHoy}).</p>
                <div class="report-options">
                    <label for="report-options-select">Selecciona una opción:</label>
                    <select id="report-options-select"></select>
                    <button id="draw-tree-btn" onclick="dibujarArbol('${containerId}', '${tipo}')">Dibujar</button>
                </div>
                <div style="height: 24px;"></div>
                <div id="${containerId}" class="avl-tree-container"></div>
            `;
                    break;
                case 'fechaEntrada':
                    containerId = 'avl-tree-fecha';
                    contenido = `
                <h3>Reporte por Fecha de Ingresos</h3>
                <p>Visualización del árbol AVL con datos por fecha de ingresos.</p>
                <div class="report-options">
                    ${fechaSelector}
                    <label for="report-options-select">Selecciona una opción:</label>
                    <select id="report-options-select"></select>
                    <button id="draw-tree-btn" onclick="dibujarArbol('${containerId}', '${tipo}')">Dibujar</button>
                </div>
                <div style="height: 24px;"></div>
                <div id="${containerId}" class="avl-tree-container"></div>
            `;
                    break;
                case 'historicoSalon':
                    containerId = 'avl-tree-salon-historico';
                    contenido = `
                <h3>Reporte Histórico por Salón</h3>
                <p>Visualización del árbol AVL con datos históricos por salón (fecha: ${fechaHoy}).</p>
                <div class="report-options">
                    <label for="report-options-select">Selecciona una opción:</label>
                    <select id="report-options-select"></select>
                    <button id="draw-tree-btn" onclick="dibujarArbol('${containerId}', '${tipo}')">Dibujar</button>
                </div>
                <div style="height: 24px;"></div>
                <div id="${containerId}" class="avl-tree-container"></div>
            `;
                    break;
                case 'fechaSalon':
                    containerId = 'avl-tree-salon-fecha';
                    contenido = `
                <h3>Reporte por Fecha y Salón</h3>
                <p>Visualización del árbol AVL con datos por fecha y salón.</p>
                <div class="report-options">
                    ${fechaSelector}
                    <label for="report-options-select">Selecciona una opción:</label>
                    <select id="report-options-select"></select>
                    <button id="draw-tree-btn" onclick="dibujarArbol('${containerId}', '${tipo}')">Dibujar</button>
                </div>
                <div style="height: 24px;"></div>
                <div id="${containerId}" class="avl-tree-container"></div>
            `;
                    break;
                default:
                    contenido = `<h3>Reporte no encontrado</h3><p>El tipo de reporte solicitado no está disponible.</p>`;
                    break;
            }

            document.getElementById('info-content').innerHTML = contenido;

            // Cargar las opciones del combo box dinámicamente según el tipo de reporte
            cargarCombobox(tipo);

            if (containerId) {
                cargarRecursosAVL(baseCSSPath, baseJSPath, () => {
                    console.log('Recursos AVL cargados.');
                });
            }
        }

        // Nueva función para manejar el evento del botón "Dibujar"
        async function dibujarArbol(containerId, tipo) {
            if (tipo === 'historicoEntrada' && !window.edificioSeleccionado) {
                alert('Por favor, selecciona un edificio.');
                return;
            }
            // Espera los datos si la función es async
            let datos;
            if (tipo === 'historicoSalon' || tipo === 'fechaSalon') {
                datos = await obtenerDatosParaReporte(tipo);
            } else {
                datos = obtenerDatosParaReporte(tipo);
            }
            const arbol = construirArbolDesdeDatos(datos, tipo);
            dibujarArbolAVLCompleto(containerId, arbol);
        }

        // Función mejorada para cargar recursos
        function cargarRecursosAVL(cssPath, jsPath, callback) {
            let recursosCargados = 0;
            const totalRecursos = 2; // CSS y JS

            function recursoCargado() {
                recursosCargados++;
                if (recursosCargados === totalRecursos) {
                    callback();
                }
            }

            // Cargar CSS
            const linkExistente = document.querySelector(`link[href="${cssPath}"]`);
            if (!linkExistente) {
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = cssPath;
                link.onload = recursoCargado;
                link.onerror = () => {
                    console.error(`Error al cargar CSS: ${cssPath}`);
                    recursoCargado();
                };
                document.head.appendChild(link);
            } else {
                recursoCargado();
            }

            // Cargar JS
            const scriptExistente = document.querySelector(`script[src="${jsPath}"]`);
            if (!scriptExistente) {
                const script = document.createElement('script');
                script.src = jsPath;
                script.onload = recursoCargado;
                script.onerror = () => {
                    console.error(`Error al cargar JS: ${jsPath}`);
                    document.getElementById('info-content').innerHTML += `
                <div class="error-message">
                    <p>No se pudo cargar la visualización del árbol AVL.</p>
                    <p>El archivo JavaScript no se encontró en: ${jsPath}</p>
                </div>
            `;
                    recursoCargado();
                };
                document.body.appendChild(script);
            } else {
                recursoCargado();
            }
        }

        // Función para obtener datos según el tipo de reporte
        function obtenerDatosParaReporte(tipo) {
            switch (tipo) {
                case 'historicoEntrada':
                    return obtenerDatosHistorico();
                case 'fechaEntrada':
                    return obtenerDatosPorFecha();
                case 'historicoSalon':
                    return obtenerDatosSalonHistorico();
                case 'fechaSalon':
                    return obtenerDatosSalonPorFecha();
                default:
                    return [];
            }
        }

        // Función para construir la estructura del árbol desde los datos
        function construirArbolDesdeDatos(datos, tipo) {
            const ubicaciones = datos.ubicaciones || [];
            const usuarios = datos.usuarios || [];
            let arbol = cargarUbicacionesArbol(ubicaciones);
            arbol = agregarUsuariosArbol(arbol, usuarios);

            // Solo filtra por edificio si es reporte por edificio
            if ((tipo === 'historicoEntrada' || tipo === 'fechaEntrada') && window.edificioSeleccionado && arbol.hijos) {
                const edificioNodo = arbol.hijos.find(e => e.id == window.edificioSeleccionado.id);
                if (edificioNodo) {
                    return edificioNodo;
                }
            }
            // Para reportes por salón, retorna el árbol completo
            return {
                valor: "Ubicaciones",
                nivel: 0,
                hijos: arbol.hijos
            };
        }

        function cargarUbicacionesArbol(datos) {
            // Crea el árbol de ubicaciones: Edificio > Puerta > Nivel > Salón
            const arbol = {
                hijos: []
            };
            datos.forEach(item => {
                // Busca o crea el edificio
                let edificio = arbol.hijos.find(e => e.id === item.idEdificio);
                if (!edificio) {
                    edificio = {
                        id: item.idEdificio,
                        valor: item.edificio,
                        nivel: 0,
                        data: {
                            foto: IMG_PATHS.edificio
                        },
                        hijos: []
                    };
                    arbol.hijos.push(edificio);
                }

                // Si no hay puerta, solo es edificio
                if (!item.idPuerta || item.idPuerta === 0) return;

                // Busca o crea la puerta
                let puerta = edificio.hijos.find(p => p.id === item.idPuerta);
                if (!puerta) {
                    puerta = {
                        id: item.idPuerta,
                        valor: item.puerta,
                        nivel: 1,
                        data: {
                            foto: IMG_PATHS.door
                        },
                        hijos: []
                    };
                    edificio.hijos.push(puerta);
                }

                // Si no hay salón, solo es puerta (no crear nivel ni salón)
                if (!item.idSalon || item.idSalon === 0) return;

                // Busca o crea el nivel
                let nivel = puerta.hijos.find(n => n.id === item.nivel);
                if (!nivel) {
                    nivel = {
                        id: item.nivel,
                        valor: `Nivel ${item.nivel}`,
                        nivel: 2,
                        data: {
                            foto: IMG_PATHS.nivel(item.nivel)
                        },
                        hijos: []
                    };
                    puerta.hijos.push(nivel);
                }

                // Crea el salón solo si tiene nombre y id
                let salon = nivel.hijos.find(s => s.id === item.idSalon);
                if (!salon && item.salon) {
                    salon = {
                        id: item.idSalon,
                        valor: item.salon, // nombre real del salón
                        nivel: 3,
                        data: {
                            foto: IMG_PATHS.classroom
                        },
                        hijos: []
                    };
                    nivel.hijos.push(salon);
                }
            });
            return arbol;
        }

        function agregarUsuariosArbol(arbol, usuarios) {
            usuarios.forEach(usuario => {
                // Buscar el edificio
                const edificio = arbol.hijos.find(e => e.id === usuario.idEdificio);
                if (!edificio) return;

                // Si el usuario está a nivel de edificio (no tiene puerta)
                if (!usuario.idPuerta || usuario.idPuerta === 0) {
                    edificio.hijos.push({
                        id: usuario.idUsuario,
                        valor: usuario.nombre,
                        nivel: 1,
                        data: {
                            ...usuario,
                            foto: usuario.foto || IMG_PATHS.user
                        },
                        hijos: []
                    });
                    return;
                }

                // Buscar la puerta
                const puerta = edificio.hijos.find(p => p.id === usuario.idPuerta);
                if (!puerta) return;

                // Si el usuario está a nivel de puerta (no tiene salón)
                if (!usuario.idSalon || usuario.idSalon === 0) {
                    // Si tiene nivel, buscar el nivel
                    if (usuario.nivel && usuario.nivel !== 0) {
                        const nivel = puerta.hijos.find(n => n.id === usuario.nivel);
                        if (nivel) {
                            nivel.hijos.push({
                                id: usuario.idUsuario,
                                valor: usuario.nombre,
                                nivel: 3,
                                data: {
                                    ...usuario,
                                    foto: usuario.foto || IMG_PATHS.user
                                },
                                hijos: []
                            });
                            return;
                        }
                    }
                    // Si no tiene nivel, va en la puerta
                    puerta.hijos.push({
                        id: usuario.idUsuario,
                        valor: usuario.nombre,
                        nivel: 2,
                        data: {
                            ...usuario,
                            foto: usuario.foto || IMG_PATHS.user
                        },
                        hijos: []
                    });
                    return;
                }

                // Buscar el nivel
                const nivel = puerta.hijos.find(n => n.id === usuario.nivel);
                if (!nivel) return;

                // Buscar el salón
                const salon = nivel.hijos.find(s => s.id === usuario.idSalon);
                if (!salon) return;

                // Usuario en el salón
                salon.hijos.push({
                    id: usuario.idUsuario,
                    valor: usuario.nombre,
                    nivel: 4,
                    data: {
                        ...usuario,
                        foto: usuario.foto || IMG_PATHS.user
                    },
                    hijos: []
                });
            });
            return arbol;
        }
        // Configuración mejorada del árbol
        const TREE_CONFIG = {
            NODE_RADIUS: 35,
            VERTICAL_SPACING: 120,
            HORIZONTAL_SPACING: 60,
            NODE_SPACING: 5, // Espacio adicional entre nodos
            LEVEL_HEIGHT: 100,
            IMAGE_SIZE: 50,
            LINE_WIDTH: 3,
            NODE_COLORS: {
                default: '#4CAF50',
                hover: '#3e8e41',
                stroke: '#388E3C'
            }
        };

        function dibujarArbolAVLCompleto(containerId, arbol) {
            const container = document.getElementById(containerId);
            if (!container) {
                console.error(`Contenedor ${containerId} no encontrado`);
                return;
            }

            // Limpiar el contenedor
            container.innerHTML = '';

            // --- NUEVO: Validar si todos los nodos son vacíos ---
            // Si no hay hijos o todos los hijos y subhijos tienen id 0, mostrar mensaje
            function esArbolVacio(nodo) {
                if (!nodo.hijos || nodo.hijos.length === 0) return true;
                // Si todos los hijos tienen id 0 y sus hijos también son vacíos
                return nodo.hijos.every(hijo =>
                    (hijo.id === 0) &&
                    (!hijo.hijos || hijo.hijos.length === 0 || esArbolVacio(hijo))
                );
            }

            if (!arbol.hijos || arbol.hijos.length === 0 || esArbolVacio(arbol)) {
                container.innerHTML = `
            <div class="arbol-vacio" style="text-align:center; padding:40px 0;">
                <i class="fas fa-exclamation-circle" style="font-size: 48px; color: #f44336;"></i>
                <p style="font-size: 18px; color: #444; margin: 12px 0 8px 0;">
                    No tiene rutas esta ubicación.<br>
                    Por favor seleccione un nuevo edificio o salón.
                </p>
            </div>
        `;
                return;
            }

            // Crear elemento SVG para el árbol con scroll
            const svgWrapper = document.createElement('div');
            svgWrapper.style.width = '100%';
            svgWrapper.style.height = '100%';
            svgWrapper.style.overflow = 'auto';
            svgWrapper.style.backgroundColor = '#f9f9f9';

            const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('width', '100%');
            svg.setAttribute('height', '100%');
            svg.style.display = 'block';
            svg.style.minWidth = '1000px';
            svg.style.minHeight = '600px';

            // Grupo principal
            const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
            g.setAttribute('transform', 'translate(80, 80)'); // Margen izquierdo aumentado
            svg.appendChild(g);
            svgWrapper.appendChild(svg);
            container.appendChild(svgWrapper);

            // Calcular posiciones with more space
            function calcularPosiciones(nodo, nivel, posX, espacioDisponible) {
                if (!nodo) return;

                const posY = nivel * (TREE_CONFIG.VERTICAL_SPACING + TREE_CONFIG.NODE_SPACING);
                nodo.x = posX;
                nodo.y = posY;

                if (nodo.hijos && nodo.hijos.length > 0) {
                    const totalHijos = nodo.hijos.length;
                    const espacioRequerido = Math.max(
                        (TREE_CONFIG.HORIZONTAL_SPACING + TREE_CONFIG.NODE_SPACING) * (totalHijos - 1),
                        espacioDisponible / totalHijos
                    );

                    const startX = posX - (espacioRequerido * (totalHijos - 1)) / 2;

                    nodo.hijos.forEach((hijo, index) => {
                        const childX = startX + index * espacioRequerido;
                        calcularPosiciones(hijo, nivel + 1, childX, espacioRequerido);
                    });
                }
            }

            // Calcular posiciones comenzando desde el centro
            calcularPosiciones(arbol, 0, (container.offsetWidth - 160) / 2, container.offsetWidth - 160);

            // Dibujar conexiones más gruesas
            function dibujarConexiones(nodo, g) {
                if (!nodo || !nodo.hijos) return;

                nodo.hijos.forEach(hijo => {
                    if (hijo.x !== undefined && hijo.y !== undefined) {
                        const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                        line.setAttribute('x1', nodo.x);
                        line.setAttribute('y1', nodo.y);
                        line.setAttribute('x2', hijo.x);
                        line.setAttribute('y2', hijo.y);
                        line.setAttribute('stroke', '#555');
                        line.setAttribute('stroke-width', TREE_CONFIG.LINE_WIDTH);
                        line.setAttribute('stroke-linecap', 'round');
                        g.appendChild(line);
                        dibujarConexiones(hijo, g);
                    }
                });
            }

            dibujarConexiones(arbol, g);

            // Función mejorada para manejar imágenes
            async function cargarImagenSegura(ruta) {
                const rutasPosibles = [
                    ruta,
                    `/${ruta}`,
                    `../${ruta}`,
                    `../../${ruta}`,
                    IMG_PATHS.user,
                    'https://via.placeholder.com/100?text=Usuario'
                ];
                for (const posibleRuta of rutasPosibles) {
                    try {
                        const existe = await verificarImagen(posibleRuta);
                        if (existe) return posibleRuta;
                    } catch (e) {}
                }
                return IMG_PATHS.user;
            }

            async function verificarImagen(url) {
                return new Promise((resolve) => {
                    const img = new Image();
                    img.onload = () => resolve(true);
                    img.onerror = () => resolve(false);
                    img.src = url;
                });
            }

            // Función mejorada para expandir imágenes
            function expandirImagen(event, imgSrc) {
                event.stopPropagation();

                const overlay = document.createElement('div');
                overlay.className = 'image-overlay';

                const expandedImg = document.createElement('img');
                expandedImg.src = imgSrc;
                expandedImg.className = 'expanded-image';

                overlay.appendChild(expandedImg);
                document.body.appendChild(overlay);

                overlay.addEventListener('click', () => {
                    document.body.removeChild(overlay);
                });
            }

            // Dibujar nodos mejorados con imágenes específicas para edificios y puertas
            // En la función dibujarNodos, modifica esta parte:
            async function dibujarNodos(nodo, g) {
                if (!nodo || nodo.x === undefined || nodo.y === undefined) return;

                const nodeGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
                nodeGroup.setAttribute('class', 'avl-node-group');
                nodeGroup.setAttribute('transform', `translate(${nodo.x}, ${nodo.y})`);

                // --- NODO DE USUARIO ---
                if (nodo.data && nodo.data.idUsuario) {
                    // Círculo base
                    const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                    circle.setAttribute('r', TREE_CONFIG.NODE_RADIUS);
                    circle.setAttribute('stroke', TREE_CONFIG.NODE_COLORS.stroke);
                    circle.setAttribute('stroke-width', '2');
                    circle.setAttribute('class', 'node-circle');
                    circle.setAttribute('fill', '#2196F3'); // Color azul para nodos de usuario
                    nodeGroup.appendChild(circle);

                    // Imagen de usuario
                    const imageSize = TREE_CONFIG.IMAGE_SIZE;
                    const imagenUrl = await cargarImagenSegura(nodo.data.foto);
                    const image = document.createElementNS('http://www.w3.org/2000/svg', 'image');
                    image.setAttribute('href', imagenUrl);
                    image.setAttribute('width', imageSize);
                    image.setAttribute('height', imageSize);
                    image.setAttribute('x', -imageSize / 2);
                    image.setAttribute('y', -imageSize / 2);
                    image.setAttribute('class', 'node-image');
                    image.setAttribute('clip-path', `circle(${imageSize/2}px at ${imageSize/2}px ${imageSize/2}px)`);
                    image.style.cursor = 'pointer';
                    image.addEventListener('click', (e) => {
                        e.stopPropagation();
                        mostrarInfoUsuario(e, nodo.data, imagenUrl);
                    });
                    nodeGroup.appendChild(image);

                    // Nombre debajo (solo nombres, sin apellidos)
                    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                    text.setAttribute('text-anchor', 'middle');
                    text.setAttribute('dominant-baseline', 'hanging');
                    text.setAttribute('y', TREE_CONFIG.NODE_RADIUS + 5);
                    text.setAttribute('fill', '#333');
                    text.setAttribute('font-size', '12px');
                    text.setAttribute('font-weight', 'bold');
                    text.textContent = nodo.data.nombre || nodo.valor; // Solo nombres
                    nodeGroup.appendChild(text);

                    // Hora debajo del nombre
                    const horaText = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                    horaText.setAttribute('text-anchor', 'middle');
                    horaText.setAttribute('dominant-baseline', 'hanging');
                    horaText.setAttribute('y', TREE_CONFIG.NODE_RADIUS + 22);
                    horaText.setAttribute('fill', '#666');
                    horaText.setAttribute('font-size', '11px');
                    horaText.textContent = nodo.data.hora || '';
                    nodeGroup.appendChild(horaText);

                    g.appendChild(nodeGroup);

                    // Dibujar hijos recursivamente
                    if (nodo.hijos) {
                        for (const hijo of nodo.hijos) {
                            await dibujarNodos(hijo, g);
                        }
                    }
                    return; // IMPORTANTE: no sigas con el bloque general
                }

                // --- NODOS NORMALES (edificio, puerta, nivel, salón, etc) ---
                const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                circle.setAttribute('r', TREE_CONFIG.NODE_RADIUS);
                circle.setAttribute('stroke', TREE_CONFIG.NODE_COLORS.stroke);
                circle.setAttribute('stroke-width', '2');
                circle.setAttribute('class', 'node-circle');
                circle.setAttribute('fill', TREE_CONFIG.NODE_COLORS.default); // Fondo verde
                nodeGroup.appendChild(circle);

                // Evento para mostrar el recuadro con el nombre del nodo
                nodeGroup.addEventListener('click', function(e) {
                    mostrarInfoNodoArbol(e, nodo.valor);
                });

                // Imagen del nodo
                let imagenUrl = 'imagenes/IMG/users/user1.png';
                if (nodo.data && nodo.data.foto) {
                    imagenUrl = await cargarImagenSegura(nodo.data.foto);
                } else if (nodo.valor.includes("Edificio")) {
                    imagenUrl = await cargarImagenSegura("imagenes/IMG/objetos/edificio.jpeg");
                } else if (nodo.valor.includes("Puerta")) {
                    imagenUrl = await cargarImagenSegura("imagenes/IMG/objetos/door.jpg");
                } else if (nodo.valor.includes("Salón")) {
                    imagenUrl = await cargarImagenSegura("imagenes/IMG/objetos/classroom.jpg");
                } else if (/^Nivel\s*\d+/i.test(nodo.valor)) {
                    // Si es un nodo de nivel
                    const nivelMatch = nodo.valor.match(/^Nivel\s*(\d+)/i);
                    if (nivelMatch) {
                        imagenUrl = await cargarImagenSegura(`imagenes/IMG/level/nivel${nivelMatch[1]}.png`);
                    }
                }

                const imageSize = TREE_CONFIG.IMAGE_SIZE;
                const image = document.createElementNS('http://www.w3.org/2000/svg', 'image');
                image.setAttribute('href', imagenUrl);
                image.setAttribute('width', imageSize);
                image.setAttribute('height', imageSize);
                image.setAttribute('x', -imageSize / 2);
                image.setAttribute('y', -imageSize / 2);
                image.setAttribute('class', 'node-image');
                image.setAttribute('clip-path', `circle(${imageSize/2}px at ${imageSize/2}px ${imageSize/2}px)`);
                image.style.cursor = 'pointer';
                image.addEventListener('click', (e) => expandirImagen(e, imagenUrl));
                nodeGroup.appendChild(image);

                // Texto del nodo (debajo de la imagen)
                const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                text.setAttribute('text-anchor', 'middle');
                text.setAttribute('dominant-baseline', 'hanging');
                text.setAttribute('y', TREE_CONFIG.NODE_RADIUS + 10);
                text.setAttribute('fill', '#333');
                text.setAttribute('font-size', '12px');
                text.setAttribute('font-weight', 'bold');
                const textoMostrar = nodo.valor.length > 15 ?
                    nodo.valor.substring(0, 12) + '...' : nodo.valor;
                text.textContent = textoMostrar;
                nodeGroup.appendChild(text);

                g.appendChild(nodeGroup);

                // Evento para mostrar el recuadro con el nombre del nodo
                nodeGroup.addEventListener('click', function(e) {
                    mostrarInfoNodoArbol(e, nodo.valor);
                });

                // Dibujar hijos recursivamente
                if (nodo.hijos) {
                    for (const hijo of nodo.hijos) {
                        await dibujarNodos(hijo, g);
                    }
                }
            }

            // Función para mostrar el número de salón completo (se mantiene igual)
            function mostrarNumeroSalon(numeroSalon) {
                // Crear overlay
                const overlay = document.createElement('div');
                overlay.style.position = 'fixed';
                overlay.style.top = '0';
                overlay.style.left = '0';
                overlay.style.width = '100%';
                overlay.style.height = '100%';
                overlay.style.backgroundColor = 'rgba(0,0,0,0.7)';
                overlay.style.display = 'flex';
                overlay.style.justifyContent = 'center';
                overlay.style.alignItems = 'center';
                overlay.style.zIndex = '1000';
                overlay.style.cursor = 'pointer';

                // Crear contenedor del número
                const numeroContainer = document.createElement('div');
                numeroContainer.style.backgroundColor = 'white';
                numeroContainer.style.padding = '40px 80px';
                numeroContainer.style.borderRadius = '10px';
                numeroContainer.style.boxShadow = '0 0 20px rgba(0,0,0,0.5)';
                numeroContainer.style.fontSize = '48px';
                numeroContainer.style.fontWeight = 'bold';
                numeroContainer.style.color = '#2196F3';
                numeroContainer.textContent = numeroSalon;

                // Cerrar al hacer clic
                overlay.addEventListener('click', () => {
                    document.body.removeChild(overlay);
                });

                overlay.appendChild(numeroContainer);
                document.body.appendChild(overlay);
            }

            // Iniciar el dibujo del árbol
            (async () => {
                await dibujarNodos(arbol, g);

                // Ajustar tamaño del SVG según el árbol
                const bbox = g.getBBox();
                const svgWidth = Math.max(bbox.width + 200, container.offsetWidth);
                const svgHeight = Math.max(bbox.height + 200, 600);

                svg.setAttribute('width', svgWidth);
                svg.setAttribute('height', svgHeight);

                // Centrar el árbol si es más pequeño que el contenedor
                if (bbox.width < container.offsetWidth) {
                    g.setAttribute('transform', `translate(${(container.offsetWidth - bbox.width) / 2}, 80)`);
                }
            })();
        }

        // Funciones para obtener datos (se mantienen iguales)
        function obtenerDatosHistorico() {
            // Usar ubicaciones por edificio
            return <?php
                    $idEdificio = 1; // O el que corresponda
                    $ubicacionesBD = obtenerUbicacionesPorEdificio($idEdificio);
                    $ubicacionesTransformadas = transformarUbicaciones($ubicacionesBD);
                    echo json_encode([
                        'ubicaciones' => $ubicacionesTransformadas,
                        'usuarios' => []
                    ], JSON_PRETTY_PRINT);
                    ?>;
        }

        function obtenerDatosPorFecha() {
            // Usar ubicaciones por edificio
            return <?php
                    $idEdificio = 1; // O el que corresponda
                    $ubicacionesBD = obtenerUbicacionesPorEdificio($idEdificio);
                    $ubicacionesTransformadas = transformarUbicaciones($ubicacionesBD);
                    echo json_encode([
                        'ubicaciones' => $ubicacionesTransformadas,
                        'usuarios' => []
                    ], JSON_PRETTY_PRINT);
                    ?>;
        }
        async function obtenerDatosSalonHistorico() {
            const idSalon = window.selectedCombo ? parseInt(window.selectedCombo.id) : 1;
            const response = await fetch(`../Base de Datos/get_ubicaciones_salon.php?idSalon=${idSalon}`);
            const data = await response.json();
            console.log("Respuesta de obtenerUbicacionesPorSalon:", data);
            return data;
        }
        async function obtenerDatosSalonPorFecha() {
            const idSalon = window.selectedCombo ? parseInt(window.selectedCombo.id) : 1;
            const response = await fetch(`../Base de Datos/get_ubicaciones_salon.php?idSalon=${idSalon}`);
            return await response.json();
        }
        //----------------------------------------------------------------------------------------------------------------
        function tomarAsistencia() {
            // Función para tomar asistencia (compartida entre estudiante y servicios)
            alert('Tomando asistencia...');
        }

        function verAsistencia() {
            // Muestra el historial de asistencia
            document.getElementById('info-content').innerHTML = `
        <h3>Asistencia</h3>
        <p>Historial de asistencia.</p>
    `;
        }

        const IMG_PATHS = {
            nivel: num => `../../imagenes/IMG/level/nivel${num}.png`,
            classroom: "../../imagenes/IMG/objetos/classroom.jpg",
            door: "../../imagenes/IMG/objetos/door.jpg",
            edificio: "../../imagenes/IMG/objetos/edificio.jpeg",
            user: "../../imagenes/IMG/users/user1.png"
        };

        function gestionarUsuarios(accion) {
            // Botón adicional según acción
            let botonExtra = "";
            if (accion.toLowerCase() === "eliminar") {
                botonExtra = `
            <button id="btn-eliminar" class="btn-eliminar" disabled>
                <i class="fas fa-trash-alt"></i> ELIMINAR
            </button>
        `;
            } else if (accion.toLowerCase() === "agregar") {
                botonExtra = `<button id="btn-agregar">AGREGAR</button>`;
            }

            document.getElementById('info-content').innerHTML = `
        <h3>Administración de Usuarios - ${accion}</h3>
        <p>Realizando acción: ${accion}.</p>
        <div class="usuarios-container">
            <div class="usuarios-busqueda">
                <label for="carnet">CARNET:</label>
                <input type="text" id="carnet" placeholder="Ingrese el carnet">
                <button onclick="buscarUsuario('${accion}')">
                    <i class="fas fa-search"></i> BUSCAR
                </button>
                ${botonExtra}
            </div>
            <table class="usuarios-tabla">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Carnet</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Celular</th>
                        <th>Tipo</th>
                        <th>Carrera</th>
                        <th>Sección</th>
                    </tr>
                </thead>
                <tbody id="usuarios-tbody">
                    <tr>
                        <td colspan="9" style="text-align:center;">Ingrese un carnet y haga clic en BUSCAR</td>
                    </tr>
                </tbody>
            </table>
        </div>
    `;

            // Configurar evento de eliminación si es la acción correspondiente
            if (accion.toLowerCase() === "eliminar") {
                document.getElementById('btn-eliminar').addEventListener('click', eliminarUsuario);
            }
        }

        let usuarioActual = null;

        async function buscarUsuario(accion) {
            const carnet = document.getElementById('carnet').value.trim();
            const tbody = document.getElementById('usuarios-tbody');
            const btnEliminar = document.getElementById('btn-eliminar');

            if (!carnet) {
                tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;color:red;">Por favor ingrese un carnet</td></tr>`;
                if (btnEliminar) btnEliminar.disabled = true;
                return;
            }

            tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;"><i class="fas fa-spinner fa-spin"></i> Buscando usuario...</td></tr>`;

            try {
                const response = await fetch(`../Base de datos/buscar_usuario.php?carnet=${encodeURIComponent(carnet)}`);
                const data = await response.json();

                if (data.error) {
                    tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;color:red;">${data.error}</td></tr>`;
                    if (btnEliminar) btnEliminar.disabled = true;
                    return;
                }

                if (!data.usuarios || data.usuarios.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;">No se encontraron usuarios con ese carnet</td></tr>`;
                    if (btnEliminar) btnEliminar.disabled = true;
                    return;
                }

                // Guardar usuario encontrado para posible eliminación
                usuarioActual = data.usuarios[0];

                // Mostrar resultados en la tabla
                tbody.innerHTML = data.usuarios.map((usuario, index) => `
            <tr>
                <td>${index + 1}</td>
                <td>${usuario.Carnet_Usuario}</td>
                <td>${usuario.Nombres_Usuario}</td>
                <td>${usuario.Apellidos_Usuario}</td>
                <td>${usuario.Correo_Electronico_Usuario}</td>
                <td>${usuario.Numero_De_Telefono_Usuario}</td>
                <td>${usuario.Tipo_De_Usuario}</td>
                <td>${usuario.Nombre_Carrera}</td>
                <td>${usuario.Seccion_Usuario}</td>
            </tr>
        `).join('');

                // Habilitar botón de eliminar si estamos en esa acción
                if (btnEliminar) {
                    btnEliminar.disabled = false;
                    btnEliminar.setAttribute('data-carnet', usuarioActual.Carnet_Usuario);
                }

            } catch (error) {
                console.error('Error al buscar usuario:', error);
                tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;color:red;">Error al conectar con el servidor</td></tr>`;
                if (btnEliminar) btnEliminar.disabled = true;
            }
        }

        async function eliminarUsuario() {
            const carnet = this.getAttribute('data-carnet');
            const tbody = document.getElementById('usuarios-tbody');
            const btnEliminar = document.getElementById('btn-eliminar');

            if (!carnet || !confirm(`¿Está seguro que desea eliminar al usuario con carnet ${carnet}?`)) {
                return;
            }

            btnEliminar.disabled = true;
            btnEliminar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Eliminando...';
            tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;"><i class="fas fa-spinner fa-spin"></i> Eliminando usuario...</td></tr>`;

            try {
                const response = await fetch('../Base de datos/eliminar_usuario.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `carnet=${encodeURIComponent(carnet)}`
                });

                const data = await response.json();

                if (data.error) {
                    tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;color:red;">${data.error}</td></tr>`;
                    btnEliminar.innerHTML = '<i class="fas fa-trash-alt"></i> ELIMINAR';
                    return;
                }

                // Mostrar mensaje de éxito
                tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;color:green">
            <i class="fas fa-check-circle"></i> Usuario con carnet ${carnet} eliminado correctamente
        </td></tr>`;

                // Resetear el formulario
                document.getElementById('carnet').value = '';
                btnEliminar.innerHTML = '<i class="fas fa-trash-alt"></i> ELIMINAR';
                btnEliminar.disabled = true;
                usuarioActual = null;


            } catch (error) {
                console.error('Error al eliminar usuario:', error);
                tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;color:red;">Error al conectar con el servidor</td></tr>`;
                btnEliminar.innerHTML = '<i class="fas fa-trash-alt"></i> ELIMINAR';
                btnEliminar.disabled = false;
            }
        }

        function abrirRegistroGeneral() {
            // Muestra el registro general
            document.getElementById('info-content').innerHTML = `
        <h3>Registro General</h3>
        <p>Información completa del registro general.</p>
    `;
        }


        // Función para mostrar estadísticas with gráficos
        function abrirEstadisticas() {
            document.getElementById('info-content').innerHTML = `
        <div class="stats-container">
            <h3>Estadísticas de Asistencia</h3>
            
            <div class="stats-controls">
                <div class="form-group">



                    <label for="report-type">Tipo de Reporte:</label>
                    <select id="report-type" class="form-control" onchange="cambiarTipoReporte()">
                        <option value="asistencia">Asistencia por Salón</option>
                        <option value="comparativa-salones">Comparativa entre Salones</option>
                        <option value="comparativa-docentes">Comparativa entre Docentes</option>
                    </select>
                </div>
                
                <div id="salon-control" class="form-group">
                    <label for="salon-select">Seleccionar Salón:</label>
                    <select id="salon-select" class="form-control">
                        <option value="" disabled selected>-- Seleccione --</option>
                        <option value="101">Salón 101</option>
                        <option value="202">Salón 202</option>
                        <option value="303">Salón 303</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="chart-type">Tipo de Gráfico:</label>
                    <select id="chart-type" class="form-control">
                        <option value="bar">Barras</option>
                        <option value="line">Línea</option>
                        <option value="radar">Radar</option>
                        <option value="doughnut">Dona</option>
                    </select>
                </div>
            </div>
            
            <div class="loading-spinner" id="loading-spinner" style="display:none;">
                <i class="fas fa-spinner fa-spin"></i>
            </div>
            
            <div id="stats-results" class="stats-results">
                <div class="placeholder-message">

                    <i class="fas fa-chart-pie"></i>
                    <p>Seleccione los parámetros para generar el reporte estadístico</p>
                </div>
            </div>
            
            <div id="stats-error" class="stats-error">
                <!-- Aquí se mostrarán los mensajes de error -->
            </div>
        </div>
    `;

            // Inicializar select2 para los selects
            $('#salon-select').select2({
                placeholder: '-- Seleccione --',
                allowClear: true
            });

            // Rango de fechas: últimos 7 días
            const hoy = new Date();
            const hace7Dias = new Date();
            hace7Dias.setDate(hoy.getDate() - 6); // Desde hace 6 días hasta hoy

            document.getElementById('fecha-inicio').valueAsDate = hace7Dias;
            document.getElementById('fecha-fin').valueAsDate = hoy;

            // Ocultar spinner inicialmente
            document.getElementById('loading-spinner').style.display = 'none';
        }

        function cambiarTipoReporte() {
            const tipo = document.getElementById('report-type').value;
            const salonControl = document.getElementById('salon-control');

            if (tipo === 'asistencia') {
                salonControl.style.display = 'block';
            } else {
                salonControl.style.display = 'none';
            }
        }

        function cargarEstadisticas() {
            const tipo = document.getElementById('report-type').value;
            const salon = tipo === 'asistencia' ? document.getElementById('salon-select').value : '';
            const fechaInicio = document.getElementById('fecha-inicio').value;
            const fechaFin = document.getElementById('fecha-fin').value;
            const chartType = document.getElementById('chart-type').value;

            // Validaciones
            if (tipo === 'asistencia' && !salon) {
                mostrarError('Por favor seleccione un salón');
                return;
            }

            if (!fechaInicio || !fechaFin) {
                mostrarError('Por favor seleccione un rango de fechas válido');
                return;
            }

            if (new Date(fechaFin) < new Date(fechaInicio)) {
                mostrarError('La fecha final no puede ser anterior a la fecha inicial');
                return;
            }

            // Mostrar spinner de carga
            document.getElementById('loading-spinner').style.display = 'flex';
            document.getElementById('stats-results').innerHTML = '';
            document.getElementById('stats-error').innerHTML = '';

            // Realizar petición al servidor
            fetch(`get_stats.php?tipo=${tipo}&salon=${salon}&inicio=${fechaInicio}&fin=${fechaFin}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Error del servidor: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    mostrarResultadosEstadisticas(data, tipo, chartType);
                })
                .catch(error => {
                    console.error('Error al cargar estadísticas:', error);
                    mostrarError(`Error al generar el reporte: ${error.message}`);
                })
                .finally(() => {
                    document.getElementById('loading-spinner').style.display = 'none';
                });
        }

        function mostrarResultadosEstadisticas(data, tipo, chartType) {
            const container = document.getElementById('stats-results');

            // Plantilla base para el resumen estadístico
            let htmlContent = `
        <div class="stats-summary">
            <h4>Reporte: ${obtenerTituloReporte(tipo)} (${data.fecha_inicio} a ${data.fecha_fin})</h4>
            <div class="stats-grid">
                ${generarResumenEstadistico(data, tipo)}
            </div>
        </div>
        <div class="stats-charts">
            <h4>Visualización de Datos</h4>
            ${generarContenedoresGraficos(tipo, chartType)}
        </div>
    `;

            container.innerHTML = htmlContent;

            // Crear los gráficos según el tipo
            switch (tipo) {
                case 'asistencia':
                    crearGraficosAsistencia(data, chartType);
                    break;
                case 'comparativa-salones':
                    crearGraficosComparativaSalones(data, chartType);
                    break;
                case 'comparativa-docentes':
                    crearGraficosComparativaDocentes(data, chartType);
                    break;
            }
        }

        function obtenerTituloReporte(tipo) {
            const titulos = {
                'asistencia': `Asistencia Salón ${document.getElementById('salon-select').value}`,
                'comparativa-salones': 'Comparativa entre Salones',
                'comparativa-docentes': 'Comparativa entre Docentes'
            };
            return titulos[tipo] || 'Reporte Estadístico';
        }

        function generarResumenEstadistico(data, tipo) {
            if (tipo === 'asistencia') {
                return `
            <div class="stat-item">
                <span class="stat-label">Total Estudiantes:</span>
                <span class="stat-value">${data.total_estudiantes || 'N/A'}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Asistencia Promedio:</span>
                <span class="stat-value">${data.asistencia_promedio ? data.asistencia_promedio + '%' : 'N/A'}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Día con más asistencia:</span>
                <span class="stat-value">${data.dia_max_asistencia || 'N/A'} (${data.max_asistencia || 0} estudiantes)</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Día con menos asistencia:</span>
                <span class="stat-value">${data.dia_min_asistencia || 'N/A'} (${data.min_asistencia || 0} estudiantes)</span>
            </div>
            <div class="stat-item highlight">
                <span class="stat-label">Estudiante con mejor asistencia:</span>
                <span class="stat-value">${data.estudiante_top?.nombre || 'N/A'} (${data.estudiante_top?.asistencias || 0} asistencias)</span>
            </div>
        `;
            } else if (tipo === 'comparativa-salones') {
                return `
            <div class="stat-item">
                <span class="stat-label">Total Estudiantes:</span>
                <span class="stat-value">${data.total_estudiantes || 'N/A'}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Salón con más asistencia:</span>
                <span class="stat-value">${data.salon_max_asistencia || 'N/A'} (${data.max_asistencia || 0}%)</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Salón con menos asistencia:</span>
                <span class="stat-value">${data.salon_min_asistencia || 'N/A'} (${data.min_asistencia || 0}%)</span>
            </div>
            <div class="stat-item highlight">
                <span class="stat-label">Diferencia porcentual:</span>
                <span class="stat-value">${(data.max_asistencia - data.min_asistencia).toFixed(2) || 0}%</span>
            </div>
        `;
            } else {
                return `
            <div class="stat-item">
                <span class="stat-label">Docente con mejor asistencia:</span>
                <span class="stat-value">${data.docente_max_asistencia || 'N/A'} (${data.max_asistencia || 0}%)</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Docente con menor asistencia:</span>
                <span class="stat-value">${data.docente_min_asistencia || 'N/A'} (${data.min_asistencia || 0}%)</span>
            </div>
            <div class="stat-item highlight">
                <span class="stat-label">Diferencia porcentual:</span>
                <span class="stat-value">${data.diferencia || 0}%</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Total estudiantes:</span>
                <span class="stat-value">${data.estudiantes_por_docente?.reduce((a, b) => a + b, 0) || 'N/A'}</span>
            </div>
        `;
            }
        }

        function generarContenedoresGraficos(tipo, chartType) {
            let chartsHTML = '';

            if (tipo === 'asistencia') {
                chartsHTML = `
            <div class="chart-row">
                <div class="chart-container">
                    <canvas id="asistenciaChart"></canvas>
                    <div class="chart-legend">Asistencia diaria</div>
                </div>
                <div class="chart-container">
                    <canvas id="asistenciaHoraChart"></canvas>
                    <div class="chart-legend">Asistencia por hora</div>
                </div>
            </div>
            <div class="chart-row">
                <div class="chart-container">
                    <canvas id="topEstudiantesChart"></canvas>
                    <div class="chart-legend">Top 5 estudiantes</div>
                </div>
            </div>
        `;
            } else if (tipo === 'comparativa-salones') {
                chartsHTML = `
            <div class="chart-row">
                <div class="chart-container">
                    <canvas id="comparativaSalonesChart"></canvas>
                    <div class="chart-legend">Comparativa porcentual</div>
                </div>
                <div class="chart-container">
                    <canvas id="totalSalonesChart"></canvas>
                    <div class="chart-legend">Total asistencias</div>
                </div>
            </div>
        `;
            } else {
                chartsHTML = `
            <div class="chart-row">
                <div class="chart-container">
                    <canvas id="comparativaDocentesChart"></canvas>
                    <div class="chart-legend">Comparativa porcentual</div>
                </div>
                <div class="chart-container">
                    <canvas id="estudiantesDocentesChart"></canvas>
                    <div class="chart-legend">Estudiantes por docente</div>
                </div>
            </div>
        `;
            }

            return chartsHTML;
        }

        function crearGraficosAsistencia(data, chartType) {
            // Gráfico de asistencia diaria
            crearChart(
                'asistenciaChart',
                chartType,
                data.dias_semana,
                ['Estudiantes presentes'],
                [data.asistencias_diarias],
                'Asistencia Diaria',
                'Número de estudiantes',
                ['rgba(54, 162, 235, 0.7)'],
                true
            );

            // Gráfico de asistencia por hora
            crearChart(
                'asistenciaHoraChart',
                'line',
                data.horas_dia,
                ['Porcentaje de asistencia'],
                [data.asistencia_por_hora],
                'Asistencia por Hora',
                'Porcentaje de asistencia',
                ['rgba(255, 99, 132, 0.7)'],
                true
            );

            // Gráfico de top estudiantes
            const topEstudiantes = [...data.estudiantes]
                .sort((a, b) => b.asistencias - a.asistencias)
                .slice(0, 5);

            crearChart(
                'topEstudiantesChart',
                'bar',
                topEstudiantes.map(e => e.nombre),
                ['Asistencias', 'Inasistencias'],
                [
                    topEstudiantes.map(e => e.asistencias),
                    topEstudiantes.map(e => e.inasistencias)
                ],
                'Top 5 Estudiantes',
                'Número de días',
                ['rgba(75, 192, 192, 0.7)', 'rgba(255, 159, 64, 0.7)'],
                true
            );
        }

        function crearGraficosComparativaSalones(data, chartType) {
            // Gráfico comparativo porcentual
            crearChart(
                'comparativaSalonesChart',
                chartType,
                data.salones,
                ['Porcentaje de asistencia'],
                [data.porcentajes_asistencia],
                'Comparativa entre Salones',
                'Porcentaje de asistencia',
                [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(75, 192, 192, 0.7)'
                ],
                true
            );

            // Gráfico de total asistencias
            crearChart(
                'totalSalonesChart',
                'doughnut',
                data.salones,
                ['Total asistencias'],
                [data.total_asistencias],
                'Total de Asistencias',
                'Número de asistencias',
                [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(75, 192, 192, 0.7)'
                ],
                false
            );
        }

        function crearGraficosComparativaDocentes(data, chartType) {
            // Gráfico comparativo porcentual
            crearChart(
                'comparativaDocentesChart',
                chartType,
                data.docentes,
                ['Porcentaje de asistencia'],
                [data.porcentajes_asistencia],
                'Comparativa entre Docentes',
                'Porcentaje de asistencia',
                [
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    'rgba(201, 203, 207, 0.7)'
                ],
                true
            );

            // Gráfico de estudiantes por docente
            crearChart(
                'estudiantesDocentesChart',
                'pie',
                data.docentes,
                ['Estudiantes'],
                [data.estudiantes_por_docente],
                'Estudiantes por Docente',
                'Número de estudiantes',
                [
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)',
                    'rgba(201, 203, 207, 0.7)'
                ],
                false
            );
        }

        function crearChart(canvasId, type, labels, datasetsLabels, datasetsData, title, yLabel, colors, showLegend) {
            const ctx = document.getElementById(canvasId).getContext('2d');

            // Preparar datasets
            const datasets = [];
            for (let i = 0; i < datasetsLabels.length; i++) {
                datasets.push({
                    label: datasetsLabels[i],
                    data: datasetsData[i],
                    backgroundColor: colors[i % colors.length],
                    borderColor: colors[i % colors.length].replace('0.7', '1'),
                    borderWidth: 1,
                    fill: type === 'line'
                });
            }

            // Configuración común
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 2000,
                    easing: 'easeOutQuart'
                },
                plugins: {
                    title: {
                        display: true,
                        text: title,
                        font: {
                            size: 16
                        }
                    },
                    legend: {
                        display: showLegend,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: !!yLabel,
                            text: yLabel
                        }
                    }
                }
            };

            // Configuración específica para radar
            if (type === 'radar') {
                commonOptions.scales = {
                    r: {
                        angleLines: {
                            display: true
                        },
                        suggestedMin: 0,
                        suggestedMax: 100
                    }
                };
            }

            new Chart(ctx, {
                type: type,
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: commonOptions
            });
        }

        function mostrarError(mensaje) {
            const errorContainer = document.getElementById('stats-error');
            errorContainer.innerHTML = `
        <div class="error-message">
            <i class="fas fa-times-circle"></i>
            <p>${mensaje}</p>
            <button onclick="cargarEstadisticas()" class="btn-reintentar">
                <i class="fas fa-sync-alt"></i> Reintentar
            </button>
        </div>
    `;
        }

        // Añadir estilos al documento
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar Chart.js dinámicamente
            const chartScript = document.createElement('script');
            chartScript.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            chartScript.onload = function() {
                // Cargar animaciones adicionales
                const chartAnimationScript = document.createElement('script');
                chartAnimationScript.src = 'https://cdn.jsdelivr.net/npm/chartjs-plugin-animation@1.1.1';
                document.head.appendChild(chartAnimationScript);
            };
            document.head.appendChild(chartScript);

            // Agregar estilos CSS
            const styleElement = document.createElement('style');
            styleElement.innerHTML = `
        .stats-container {
    padding: 15px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    max-width: 100%;
    overflow: hidden;
}

/* Controles responsivos */
.stats-controls {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 12px;
    margin-bottom: 15px;
}

/* Contenedor de gráficos ajustado */
.chart-container {
    background: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    height: 300px; /* Altura fija */
    display: flex;
    flex-direction: column;
}

/* Ajuste específico para móviles */
@media (max-width: 768px) {
    .stats-container {
        padding: 12px;
    }
    
    .stats-controls {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .chart-container {
        height: 280px;
        padding: 12px;
    }
    
    /* Animaciones optimizadas para móvil */
    .stat-item:hover {
        transform: none; /* Desactiva hover en móvil */
    }
    
    .btn-generar:hover {
        transform: none;
    }
}

/* Animaciones mejoradas */
@keyframes smoothAppear {
    0% { 
        opacity: 0;
        transform: translateY(10px);
    }
    100% { 
        opacity: 1;
        transform: translateY(0);
    }
}

.stats-results {
    animation: smoothAppear 0.4s ease-out;
}

/* Spinner optimizado */
.loading-spinner {
    height: 200px;
    animation: smoothAppear 0.3s ease-out;
}

/* Efectos hover solo para desktop */
@media (min-width: 769px) {
    .stat-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .btn-generar:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .chart-container:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
}
    `;
            document.head.appendChild(styleElement);
        });


        // Inicializar cuando Chart.js esté cargado
        if (typeof Chart !== 'undefined') {
            initEstadisticasButton();
        } else {
            const checkChartLoaded = setInterval(() => {
                if (typeof Chart !== 'undefined') {
                    clearInterval(checkChartLoaded);
                    initEstadisticasButton();
                }
            }, 100);
        }
    </script>
</head>

<body>
    <div class="container">
        <!-- Panel de botones (izquierda) -->
        <div id="panel-botones" class="panel-botones">
            <!-- El contenido se genera dinámicamente según el tipo de usuario -->
        </div>

        <!-- Panel de información (derecha) -->
        <div class="panel-info">
            <div class="info-header">
                <h1>Bienvenido <span id="nombre-usuario"></span></h1>
                <div id="mensaje-tipo" class="mensaje-tipo">
                    Verificando tu tipo de usuario...
                </div>
            </div>

            <div id="info-content" class="info-content">
                <div class="info-placeholder">
                    <i class="fas fa-info-circle"></i>
                    <p>Selecciona una opción del panel izquierdo para ver la información</p>
                </div>
            </div>

            <div class="logout-container">
                <a href="logout.php" class="btn">Cerrar sesión</a>
            </div>
        </div>
    </div>

    <div id="dashboard-arbol" class="dashboard-arbol">
        <!-- Aquí se dibuja el árbol -->
    </div>
    <button onclick="expandirDashboardArbol()" class="btn-expandir-arbol">Expandir árbol</button>

    <script>
        function mostrarInfoUsuario(event, data, imagenUrl) {
            event.stopPropagation();

            // Elimina overlay anterior si existe
            const oldOverlay = document.getElementById('usuario-info-overlay');
            if (oldOverlay) oldOverlay.remove();

            // Overlay semitransparente
            const overlay = document.createElement('div');
            overlay.id = 'usuario-info-overlay';
            overlay.style.position = 'fixed';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.width = '100vw';
            overlay.style.height = '100vh';
            overlay.style.background = 'rgba(0,0,0,0.4)';
            overlay.style.display = 'flex';
            overlay.style.alignItems = 'center';
            overlay.style.justifyContent = 'center';
            overlay.style.zIndex = 2000;

            // Cuadro blanco centrado
            const infoBox = document.createElement('div');
            infoBox.style.background = '#fff';
            infoBox.style.borderRadius = '12px';
            infoBox.style.boxShadow = '0 4px 24px rgba(0,0,0,0.18)';
            infoBox.style.padding = '32px 32px 32px 24px';
            infoBox.style.width = '350px';
            infoBox.style.display = 'flex';
            infoBox.style.flexDirection = 'column';
            infoBox.style.alignItems = 'center';
            infoBox.style.position = 'relative';

            // Imagen grande
            const img = document.createElement('img');
            img.src = imagenUrl;
            img.alt = 'Foto de perfil';
            img.style.width = '120px';
            img.style.height = '120px';
            img.style.borderRadius = '50%';
            img.style.objectFit = 'cover';
            img.style.marginBottom = '18px';
            infoBox.appendChild(img);

            // Nombre y apellidos
            const nombre = document.createElement('div');
            nombre.textContent = (data.nombre || '') + (data.apellidos ? ' ' + data.apellidos : '');
            nombre.style.fontWeight = 'bold';
            nombre.style.fontSize = '20px';
            nombre.style.marginBottom = '8px';
            infoBox.appendChild(nombre);

            // Hora de entrada
            const hora = document.createElement('div');
            hora.innerHTML = `<b>Hora de entrada:</b> ${data.hora || ''}`;
            hora.style.marginBottom = '6px';
            infoBox.appendChild(hora);

            // Fecha (hoy)
            const fecha = document.createElement('div');
            const hoy = new Date().toISOString().slice(0, 10);
            fecha.innerHTML = `<b>Fecha:</b> ${hoy}`;
            infoBox.appendChild(fecha);

            // Botón cerrar
            const btnCerrar = document.createElement('button');
            btnCerrar.textContent = 'Cerrar';
            btnCerrar.style.marginTop = '24px';
            btnCerrar.style.padding = '8px 24px';
            btnCerrar.style.background = '#2196F3';
            btnCerrar.style.color = '#fff';
            btnCerrar.style.border = 'none';
            btnCerrar.style.borderRadius = '6px';
            btnCerrar.style.cursor = 'pointer';
            btnCerrar.onclick = () => overlay.remove();
            infoBox.appendChild(btnCerrar);

            overlay.appendChild(infoBox);
            document.body.appendChild(overlay);

            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) overlay.remove();
            });
        }

        function mostrarInfoNodoArbol(event, nombreNodo) {
            event.stopPropagation();

            // Elimina overlay anterior si existe
            const oldOverlay = document.getElementById('info-nodo-arbol-overlay');
            if (oldOverlay) oldOverlay.remove();

            // Overlay semitransparente
            const overlay = document.createElement('div');
            overlay.id = 'info-nodo-arbol-overlay';
            overlay.style.position = 'fixed';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.width = '100vw';
            overlay.style.height = '100vh';
            overlay.style.background = 'rgba(0,0,0,0.4)';
            overlay.style.display = 'flex';
            overlay.style.alignItems = 'center';
            overlay.style.justifyContent = 'center';
            overlay.style.zIndex = 2100;

            // Cuadro blanco centrado
            const infoBox = document.createElement('div');
            infoBox.style.background = '#fff';
            infoBox.style.borderRadius = '12px';
            infoBox.style.boxShadow = '0 4px 24px rgba(0,0,0,0.18)';
            infoBox.style.padding = '32px 32px 32px 24px';
            infoBox.style.width = '350px';
            infoBox.style.display = 'flex';
            infoBox.style.flexDirection = 'column';
            infoBox.style.alignItems = 'center';
            infoBox.style.position = 'relative';

            // Nombre del nodo
            const nombre = document.createElement('div');
            nombre.textContent = nombreNodo;
            nombre.style.fontWeight = 'bold';
            nombre.style.fontSize = '20px';
            nombre.style.marginBottom = '8px';
            nombre.style.textAlign = 'center';
            infoBox.appendChild(nombre);

            // Botón cerrar
            const btnCerrar = document.createElement('button');
            btnCerrar.textContent = 'Cerrar';
            btnCerrar.style.marginTop = '24px';
            btnCerrar.style.padding = '8px 24px';
            btnCerrar.style.background = '#2196F3';
            btnCerrar.style.color = '#fff';
            btnCerrar.style.border = 'none';
            btnCerrar.style.borderRadius = '6px';
            btnCerrar.style.cursor = 'pointer';
            btnCerrar.onclick = () => overlay.remove();
            infoBox.appendChild(btnCerrar);

            overlay.appendChild(infoBox);
            document.body.appendChild(overlay);

            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) overlay.remove();
            });
        }

        // Obtener y mostrar los datos de ubicaciones y usuarios en consola
        const datos = obtenerDatosHistorico();
        console.log("Ubicaciones:", datos.ubicaciones);
        console.log("Usuarios:", datos.usuarios); // Array vacío por ahora
    </script>
</body>

</html>