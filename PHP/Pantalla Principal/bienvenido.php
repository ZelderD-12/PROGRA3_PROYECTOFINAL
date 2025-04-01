<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link rel="stylesheet" href="../../CSS/style2.css">
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
                
                switch(tipoUsuario) {
                    case 1: // Administrador
                        mensajeDiv.innerHTML = "<strong>Eres un Administrador</strong>. Tienes acceso completo al sistema.";
                        panelBotones.innerHTML = `
                            <h3>Panel de Administrador</h3>
                            <div class="botones-container">
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
                                            <i class="fas fa-user-graduate"></i> Estudiantes
                                        </a>
                                        <a href="#" class="dropdown-item" onclick="mostrarInformacionAdmin('Administrador')">
                                            <i class="fas fa-user-shield"></i> Administrador
                                        </a>
                                        <a href="#" class="dropdown-item" onclick="mostrarInformacionAdmin('Desarrolladores')">
                                            <i class="fas fa-code"></i> Desarrolladores
                                        </a>
                                        <a href="#" class="dropdown-item" onclick="mostrarInformacionAdmin('Servicios')">
                                            <i class="fas fa-cogs"></i> Servicios
                                        </a>
                                    </div>
                                </div>
                                <button onclick="generarPDFAdmin()" class="btn-dev">
                                    <i class="fas fa-file-pdf"></i> Generar PDF
                                </button>
                            </div>
                        `;
                        break;
                        
                    case 2: // Docente
                        mensajeDiv.innerHTML = "<strong>Eres un Docente</strong>. Puedes gestionar cursos y calificaciones.";
                        mensajeDiv.style.borderLeftColor = "#007bff"; // Azul
                        document.body.style.backgroundColor = "#FFD1DC"; // Rosa claro
                        
                        // Mostrar el panel de botones para docente
                        panelBotones.classList.remove('hidden');
                        panelBotones.classList.add('docente');
                        panelBotones.innerHTML = `
                            <h3>Panel de Docente</h3>
                            <div class="botones-container">
                                <button onclick="mostrarCursosAsignados()" class="btn-dev animated-load">
                                    <i class="fas fa-book"></i> Cursos Asignados
                                </button>
                                
                                <div id="botones-cursos" class="botones-ocultos">
                                    <button onclick="confirmarAsistencia()" class="btn-dev animated-load">
                                        <i class="fas fa-clipboard-check"></i> Confirmar Asistencia
                                    </button>
                                    <button onclick="generarPDFDocente()" class="btn-dev animated-load">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </button>
                                </div>
                                
                                <div class="dropdown animated-load">
                                    <button class="dropdown-btn" onclick="toggleDropdown('generadorReportes')">
                                        <span><i class="fas fa-chart-line"></i> Generador de Reportes</span>
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                    <div id="generadorReportes" class="dropdown-content">
                                        <a href="#" class="dropdown-item" onclick="mostrarReporte('historicoEntrada')">
                                            <i class="fas fa-history"></i> Reporte histórico de ingresos a instalaciones por puerta de entrada
                                        </a>
                                        <a href="#" class="dropdown-item" onclick="mostrarReporte('fechaEntrada')">
                                            <i class="fas fa-calendar-alt"></i> Reporte por fecha de ingresos a instalaciones por puerta de entrada
                                        </a>
                                        <a href="#" class="dropdown-item" onclick="mostrarReporte('historicoSalon')">
                                            <i class="fas fa-door-open"></i> Reporte histórico de ingreso a instalaciones por salón de clase
                                        </a>
                                        <a href="#" class="dropdown-item" onclick="mostrarReporte('fechaSalon')">
                                            <i class="fas fa-clipboard-list"></i> Reporte por fecha de ingreso a instalaciones por salón de clase
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
                                <button onclick="tomarAsistencia()" class="btn-dev">
                                    <i class="fas fa-user-check"></i> Tomar Asistencia
                                </button>
                                <button onclick="verAsistencia()" class="btn-dev">
                                    <i class="fas fa-clipboard-list"></i> Ver Asistencia
                                </button>
                                <button onclick="generarPDFEstudiante()" class="btn-dev">
                                    <i class="fas fa-file-pdf"></i> Generar PDF
                                </button>
                            </div>
                        `;
                        break;
                        
                    case 4: // Desarrollador
                        mensajeDiv.innerHTML = "<strong>Eres un Desarrollador</strong>. Gestiona matrículas y documentos.";
                        panelBotones.innerHTML = `
                            <h3>Panel de Desarrollador</h3>
                            <div class="botones-container">
                                <div class="dropdown">
                                    <button class="dropdown-btn" onclick="toggleDropdown('gestorUsuariosDesarrollador')">
                                        <span><i class="fas fa-users-cog"></i> Gestor de Usuarios</span>
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                    <div id="gestorUsuariosDesarrollador" class="dropdown-content">
                                        <a href="#" class="dropdown-item" onclick="gestionarUsuarios('visualizacion')">
                                            <i class="fas fa-eye"></i> Visualización
                                        </a>
                                        <a href="#" class="dropdown-item" onclick="gestionarUsuarios('eliminar')">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </a>
                                        <a href="#" class="dropdown-item" onclick="gestionarUsuarios('agregar')">
                                            <i class="fas fa-user-plus"></i> Agregar
                                        </a>
                                    </div>
                                </div>
                                <button onclick="abrirRegistroGeneral()" class="btn-dev registro">
                                    <i class="fas fa-clipboard-list"></i> Registro General
                                </button>
                                <button onclick="abrirAsistenciasGenerales()" class="btn-dev asistencias">
                                    <i class="fas fa-user-check"></i> Asistencias Generales
                                </button>
                                <button onclick="abrirEstadisticas()" class="btn-dev estadisticas">
                                    <i class="fas fa-chart-bar"></i> Estadísticas
                                </button>
                            </div>
                        `;
                        break;
                        

                    case 5: // Servicios
                        mensajeDiv.innerHTML = "<strong>Eres un invitado de servicio</strong>. Acceso limitado al sistema.";
                        panelBotones.innerHTML = `
                            <h3>Panel de Servicios</h3>
                            <div class="botones-container">
                                <button onclick="visualizarCredenciales()" class="btn-dev">
                                    <i class="fas fa-id-card"></i> Visualización de Credenciales
                                </button>
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
        
        // Funciones para los botones (simuladas)
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            dropdown.classList.toggle('show');
        }
        
        function mostrarInformacionAdmin(tipo) {
            document.getElementById('info-content').innerHTML = `
                <h3>Información de ${tipo}</h3>
                <p>Aquí se mostraría la información específica para ${tipo}.</p>
            `;
        }
        
        function generarPDFAdmin() {
            alert('Generando PDF para administrador...');
        }
        
        function mostrarCursosAsignados() {
            document.getElementById('info-content').innerHTML = `
                <h3>Cursos Asignados</h3>
                <p>Listado de cursos asignados al docente.</p>
            `;
        }
        
        function confirmarAsistencia() {
            alert('Confirmando asistencia...');
        }
        
        function generarPDFDocente() {
            alert('Generando PDF para docente...');
        }
        
        function mostrarReporte(tipo) {
            document.getElementById('info-content').innerHTML = `
                <h3>Reporte: ${tipo}</h3>
                <p>Datos del reporte seleccionado.</p>
            `;
        }
        
        function tomarAsistencia() {
            alert('Tomando asistencia...');
        }
        
        function verAsistencia() {
            document.getElementById('info-content').innerHTML = `
                <h3>Asistencia del Estudiante</h3>
                <p>Historial de asistencia del estudiante.</p>
            `;
        }
        
        function generarPDFEstudiante() {
            alert('Generando PDF para estudiante...');
        }
        
        function gestionarUsuarios(accion) {
            document.getElementById('info-content').innerHTML = `
                <h3>Gestor de Usuarios - ${accion}</h3>
                <p>Realizando acción: ${accion}.</p>
            `;
        }
        
        function abrirRegistroGeneral() {
            document.getElementById('info-content').innerHTML = `
                <h3>Registro General</h3>
                <p>Información completa del registro general.</p>
            `;
        }
        
        function abrirAsistenciasGenerales() {
            document.getElementById('info-content').innerHTML = `
                <h3>Asistencias Generales</h3>
                <p>Resumen de todas las asistencias.</p>
            `;
        }
        
        function abrirEstadisticas() {
            document.getElementById('info-content').innerHTML = `
                <h3>Estadísticas</h3>
                <p>Datos estadísticos del sistema.</p>
            `;
        }
        
        function visualizarCredenciales() {
            document.getElementById('info-content').innerHTML = `
                <h3>Credenciales</h3>
                <p>Visualización de credenciales de servicio.</p>
            `;
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
</body>
</html>