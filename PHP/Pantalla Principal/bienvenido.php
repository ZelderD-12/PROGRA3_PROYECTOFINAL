<?php
include '../Base de Datos/operaciones.php';
$edificios = saberEdificios();
$salones = saberSalones();

$idArbol = isset($_GET['idArbol']) ? intval($_GET['idArbol']) : 0;
$resultado = obtenerEstructuraSinUsuarios($idArbol);
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
                            <a href="#" class="dropdown-item" onclick="gestionarUsuarios('agregar')">
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
                            <a href="#" class="dropdown-item" onclick="gestionarUsuarios('agregar')">
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
                <tbody>
                    <tr>
                        <td colspan="9" style="text-align:center;">(Datos aquí...)</td>
                    </tr>
                </tbody>
            </table>
        </div>
    `;
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
                    
                    <button id="boton-adicional" class="btn-adicional">
                        <i class="fas fa-file-pdf"></i> Generar PDF
                    </button>
                </div>
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
                infoContent.innerHTML = `
        <div class="restablecer-password-container">
            <h3>Restablecer contraseña</h3>
            <p>Cambie su contraseña.</p>
            
            <form id="form-restablecer-password" class="form-password">
                <div class="campo-password">
                    <label for="password-actual">Contraseña actual:</label>
                    <input type="password" id="password-actual" name="password-actual" required>
                </div>
                
                <div class="campo-password">
                    <label for="password-nueva">Nueva contraseña:</label>
                    <input type="password" id="password-nueva" name="password-nueva" required>
                </div>
                
                <div class="campo-password">
                    <label for="password-confirmar">Confirmar nueva contraseña:</label>
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
    `;

            }

            // Cerrar el dropdown si está abierto
            cerrarDropdowns();
        }

        // Función para generar PDF
        function generarPDFAdmin() {
            alert('Generando PDF...');
            // Aquí iría el código real para generar el PDF
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
            id: edificio.idEdificio,      // <-- nombre correcto
            nombre: edificio.Edificio     // <-- nombre correcto
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
        console.log('Seleccionado:', window.selectedCombo);
    };
}

        // Función principal para mostrar reportes
        function mostrarReporte(tipo) {
            const baseCSSPath = '../../CSS/avl-tree.css';
            const baseJSPath = '../../Javascript/avl-tree.js';

            let contenido = '';
            let containerId = '';

            switch (tipo) {
                case 'historicoEntrada':
                    containerId = 'avl-tree-historico';
                    contenido = `
                <h3>Reporte Histórico de Ingresos</h3>
                <p>Visualización del árbol AVL con datos históricos de ingresos.</p>
                <div class="report-options">
                    <label for="report-options-select">Selecciona una opción:</label>
                    <select id="report-options-select">
                        <!-- Las opciones se cargarán dinámicamente -->
                    </select>
                    <button id="draw-tree-btn" onclick="dibujarArbol('${containerId}', '${tipo}')">Dibujar</button>
                </div>
                <div style="height: 24px;"></div> <!-- Espacio entre combo y árbol -->
                <div id="${containerId}" class="avl-tree-container"></div>
            `;
                    break;
                case 'fechaEntrada':
                    containerId = 'avl-tree-fecha';
                    contenido = `
                <h3>Reporte por Fecha de Ingresos</h3>
                <p>Visualización del árbol AVL con datos por fecha de ingresos.</p>
                <div class="report-options">
                    <label for="report-options-select">Selecciona una opción:</label>
                    <select id="report-options-select">
                        <!-- Las opciones se cargarán dinámicamente -->
                    </select>
                    <button id="draw-tree-btn" onclick="dibujarArbol('${containerId}', '${tipo}')">Dibujar</button>
                </div>
                <div style="height: 24px;"></div> <!-- Espacio entre combo y árbol -->
                <div id="${containerId}" class="avl-tree-container"></div>
            `;
                    break;
                case 'historicoSalon':
                    containerId = 'avl-tree-salon-historico';
                    contenido = `
                <h3>Reporte Histórico por Salón</h3>
                <p>Visualización del árbol AVL con datos históricos por salón.</p>
                <div class="report-options">
                    <label for="report-options-select">Selecciona una opción:</label>
                    <select id="report-options-select">
                        <!-- Las opciones se cargarán dinámicamente -->
                    </select>
                    <button id="draw-tree-btn" onclick="dibujarArbol('${containerId}', '${tipo}')">Dibujar</button>
                </div>
                <div style="height: 24px;"></div> <!-- Espacio entre combo y árbol -->
                <div id="${containerId}" class="avl-tree-container"></div>
            `;
                    break;
                case 'fechaSalon':
                    containerId = 'avl-tree-salon-fecha';
                    contenido = `
                <h3>Reporte por Fecha y Salón</h3>
                <p>Visualización del árbol AVL con datos por fecha y salón.</p>
                <div class="report-options">
                    <label for="report-options-select">Selecciona una opción:</label>
                    <select id="report-options-select">
                        <!-- Las opciones se cargarán dinámicamente -->
                    </select>
                    <button id="draw-tree-btn" onclick="dibujarArbol('${containerId}', '${tipo}')">Dibujar</button>
                </div>
                <div style="height: 24px;"></div> <!-- Espacio entre combo y árbol -->
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
        function dibujarArbol(containerId, tipo) {
            const datos = obtenerDatosParaReporte(tipo);
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
            const comboBox = document.getElementById('report-options-select');
            const valorRaiz = comboBox.options[comboBox.selectedIndex]?.text || "Sin selección";

            const nodoRaiz = {
                valor: valorRaiz,
                nivel: 0,
                hijos: []
            };

            switch (tipo) {
                case 'historicoEntrada':
                    datos.forEach(entrada => {
                        // Determinar la imagen según si es edificio o puerta
                        const esEdificio = entrada.instalacion.includes("Edificio");
                        const imagen = esEdificio ? "imagenes/IMG/objetos/edificio.jpeg" : "imagenes/IMG/objetos/door.jpg";

                        const nodoInstalacion = {
                            valor: entrada.instalacion,
                            nivel: 1,
                            data: {
                                foto: imagen
                            },
                            hijos: []
                        };

                        const nodoPuerta = {
                            valor: entrada.puerta,
                            nivel: 2,
                            data: {
                                foto: "imagenes/IMG/objetos/door.jpg"
                            },
                            hijos: []
                        };

                        entrada.fechas.forEach(fecha => {
                            nodoPuerta.hijos.push({
                                valor: fecha,
                                nivel: 3,
                                hijos: []
                            });
                        });

                        nodoInstalacion.hijos.push(nodoPuerta);
                        nodoRaiz.hijos.push(nodoInstalacion);
                    });
                    break;

                case 'fechaEntrada':
                    datos.forEach(entrada => {
                        const nodoInstalacion = {
                            valor: entrada.instalacion,
                            nivel: 1,
                            data: {
                                foto: "imagenes/IMG/objetos/edificio.jpeg"
                            },
                            hijos: []
                        };

                        const nodoFecha = {
                            valor: entrada.fecha,
                            nivel: 2,
                            hijos: []
                        };

                        entrada.registros.forEach(registro => {
                            nodoFecha.hijos.push({
                                valor: registro.nombre,
                                nivel: 5,
                                data: {
                                    ...registro,
                                    foto: registro.foto
                                },
                                hijos: []
                            });
                        });

                        nodoInstalacion.hijos.push(nodoFecha);
                        nodoRaiz.hijos.push(nodoInstalacion);
                    });
                    break;

                case 'historicoSalon':
                    datos.forEach(salon => {
                        const nodoInstalacion = {
                            valor: salon.instalacion,
                            nivel: 1,
                            data: {
                                foto: "imagenes/IMG/objetos/edificio.jpeg"
                            },
                            hijos: []
                        };

                        const nodoNivel = {
                            valor: `Nivel ${salon.nivel}`,
                            nivel: 2,
                            data: {
                                foto: `imagenes/IMG/level/nivel${salon.nivel}.png`
                            }, // Imagen de nivel
                            hijos: []
                        };

                        const nodoSalon = {
                            valor: `Salón ${salon.salon}`,
                            nivel: 3,
                            data: {
                                foto: "imagenes/IMG/objetos/classroom.png"
                            }, // Imagen de salón
                            hijos: []
                        };

                        salon.estudiantes.forEach(est => {
                            nodoSalon.hijos.push({
                                valor: `${est.nombre} (${est.tipo})`,
                                nivel: 4,
                                data: est,
                                hijos: []
                            });
                        });

                        nodoNivel.hijos.push(nodoSalon);
                        nodoInstalacion.hijos.push(nodoNivel);
                        nodoRaiz.hijos.push(nodoInstalacion);
                    });
                    break;

                case 'fechaSalon':
                    datos.forEach(salon => {
                        const nodoInstalacion = {
                            valor: salon.instalacion,
                            nivel: 1,
                            data: {
                                foto: "imagenes/IMG/objetos/edificio.jpeg"
                            },
                            hijos: []
                        };

                        const nodoNivel = {
                            valor: `Nivel ${salon.nivel}`,
                            nivel: 2,
                            data: {
                                foto: `imagenes/IMG/level/nivel${salon.nivel}.png`
                            }, // Imagen de nivel
                            hijos: []
                        };

                        const nodoSalon = {
                            valor: `Salón ${salon.salon}`,
                            nivel: 3,
                            data: {
                                foto: "imagenes/IMG/objetos/classroom.png"
                            }, // Imagen de salón
                            hijos: []
                        };

                        salon.registros.forEach(reg => {
                            nodoSalon.hijos.push({
                                valor: `${reg.nombre} (${reg.tipo}) - ${salon.fecha}`,
                                nivel: 4,
                                data: reg,
                                hijos: []
                            });
                        });

                        nodoNivel.hijos.push(nodoSalon);
                        nodoInstalacion.hijos.push(nodoNivel);
                        nodoRaiz.hijos.push(nodoInstalacion);
                    });
                    break;

                default:
                    console.error('Tipo de reporte no reconocido:', tipo);
                    break;
            }

            return nodoRaiz;
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

            // Crear elemento SVG para el árbol con scroll
            const svgWrapper = document.createElement('div');
            svgWrapper.style.width = '100%';
            svgWrapper.style.height = '600px';
            svgWrapper.style.overflow = 'auto';
            svgWrapper.style.border = '1px solid #ddd';
            svgWrapper.style.borderRadius = '8px';
            svgWrapper.style.padding = '10px';
            svgWrapper.style.backgroundColor = '#f9f9f9';

            const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('width', '100%');
            svg.setAttribute('height', '800');
            svg.style.display = 'block';
            svg.style.margin = '0 auto';
            svg.style.minWidth = '1000px'; // Ancho mínimo para asegurar el scroll horizontal

            // Grupo principal
            const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
            g.setAttribute('transform', 'translate(80, 80)'); // Margen izquierdo aumentado
            svg.appendChild(g);
            svgWrapper.appendChild(svg);
            container.appendChild(svgWrapper);

            // Calcular posiciones con más espacio
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
                    `imagenes/IMG/objetos/${ruta.split('/').pop()}`,
                    'imagenes/IMG/users/user.png',
                    'https://via.placeholder.com/100?text=Usuario'
                ];

                for (const posibleRuta of rutasPosibles) {
                    try {
                        const existe = await verificarImagen(posibleRuta);
                        if (existe) return posibleRuta;
                    } catch (e) {
                        console.warn(`Error al verificar imagen: ${posibleRuta}`, e);
                    }
                }

                return 'https://via.placeholder.com/100?text=Usuario';
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

                // Detectar si es un nodo de nivel (ejemplo: "Nivel 1", "Nivel 2", ...)
                const nivelMatch = nodo.valor.match(/^Nivel\s*(\d+)/i);
                let esNivel = false;
                let nivelNumero = 0;
                if (nivelMatch) {
                    esNivel = true;
                    nivelNumero = parseInt(nivelMatch[1]);
                }

                // Crear el círculo base para todos los nodos
                const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                circle.setAttribute('r', TREE_CONFIG.NODE_RADIUS);
                circle.setAttribute('stroke', TREE_CONFIG.NODE_COLORS.stroke);
                circle.setAttribute('stroke-width', '2');
                circle.setAttribute('class', 'node-circle');

                if (esNivel && nivelNumero >= 1 && nivelNumero <= 5) {
                    // Imagen de nivel correspondiente
                    const imagenUrl = await cargarImagenSegura(`imagenes/IMG/level/nivel${nivelNumero}.png`);
                    const imageSize = TREE_CONFIG.IMAGE_SIZE;
                    const image = document.createElementNS('http://www.w3.org/2000/svg', 'image');
                    image.setAttribute('href', imagenUrl);
                    image.setAttribute('width', imageSize);
                    image.setAttribute('height', imageSize);
                    image.setAttribute('x', -imageSize / 2);
                    image.setAttribute('y', -imageSize / 2);
                    image.setAttribute('class', 'node-image');
                    image.style.cursor = 'pointer';
                    nodeGroup.appendChild(circle);
                    nodeGroup.appendChild(image);

                    // Texto del nivel debajo de la imagen
                    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
                    text.setAttribute('text-anchor', 'middle');
                    text.setAttribute('dominant-baseline', 'hanging');
                    text.setAttribute('y', TREE_CONFIG.NODE_RADIUS + 10);
                    text.setAttribute('fill', '#333');
                    text.setAttribute('font-size', '12px');
                    text.setAttribute('font-weight', 'bold');
                    text.textContent = nodo.valor;
                    nodeGroup.appendChild(text);
                } else {
                    // Configuración para otros nodos (edificios, puertas, personas)
                    circle.setAttribute('fill', TREE_CONFIG.NODE_COLORS.default);
                    nodeGroup.appendChild(circle);

                    // Determinar la imagen a mostrar
                    let imagenUrl = 'imagenes/IMG/users/user.png';
                    if (nodo.data && nodo.data.foto) {
                        imagenUrl = await cargarImagenSegura(nodo.data.foto);
                    } else if (nodo.valor.includes("Edificio")) {
                        imagenUrl = await cargarImagenSegura("imagenes/IMG/objetos/edificio.jpeg");
                    } else if (nodo.valor.includes("Puerta")) {
                        imagenUrl = await cargarImagenSegura("imagenes/IMG/objetos/door.jpg");
                    } else if (nodo.valor.includes("Salón")) {
                        imagenUrl = await cargarImagenSegura("imagenes/IMG/objetos/classroom.jpg");
                    }

                    // Crear elemento de imagen
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

                    // Acortar texto largo
                    const textoMostrar = nodo.valor.length > 15 ?
                        nodo.valor.substring(0, 12) + '...' : nodo.valor;
                    text.textContent = textoMostrar;

                    nodeGroup.appendChild(text);
                }

                g.appendChild(nodeGroup);

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
            return [{
                    instalacion: "Edificio A",
                    puerta: "Puerta Principal",
                    fechas: ["2023-05-01", "2023-05-02", "2023-05-03"]
                },
                {
                    instalacion: "Edificio B",
                    puerta: "Puerta Secundaria",
                    fechas: ["2023-05-01", "2023-05-04"]
                },
                {
                    instalacion: "Edificio C",
                    puerta: "Puerta Emergencia",
                    fechas: ["2023-05-02", "2023-05-03"]
                }
            ];
        }

        function obtenerDatosPorFecha() {
            return [{
                    instalacion: "Edificio A",
                    puerta: "Puerta Principal",
                    fecha: "2023-05-01",
                    registros: [{
                            id: 1,
                            nombre: "Juan Pérez",
                            correo: "juan@example.com",
                            foto: "imagenes/IMG/users/user.avif",
                            asistencia: true
                        },
                        {
                            id: 2,
                            nombre: "María García",
                            correo: "maria@example.com",
                            foto: "imagenes/IMG/users/user.avif",
                            asistencia: false
                        }
                    ]
                },
                {
                    instalacion: "Edificio B",
                    puerta: "Puerta Secundaria",
                    fecha: "2023-05-02",
                    registros: [{
                            id: 3,
                            nombre: "Carlos López",
                            correo: "carlos@example.com",
                            foto: "imagenes/IMG/users/user.avif",
                            asistencia: true
                        },
                        {
                            id: 4,
                            nombre: "Ana Torres",
                            correo: "ana@example.com",
                            foto: "imagenes/IMG/users/user.avif",
                            asistencia: true
                        }
                    ]
                }
            ];
        }

        function obtenerDatosSalonHistorico() {
            return [{
                    instalacion: "Edificio A",
                    nivel: "1",
                    salon: "101",
                    estudiantes: [{
                            id: 1,
                            nombre: "Juan Pérez",
                            tipo: "estudiante",
                            foto: "imagenes/IMG/users/user.avif"
                        },
                        {
                            id: 2,
                            nombre: "Prof. Rodríguez",
                            tipo: "catedrático",
                            foto: "imagenes/IMG/users/user.avif"
                        }
                    ]
                },
                {
                    instalacion: "Edificio B",
                    nivel: "2",
                    salon: "202",
                    estudiantes: [{
                            id: 3,
                            nombre: "Carlos López",
                            tipo: "estudiante",
                            foto: "imagenes/IMG/users/user.avif"
                        },
                        {
                            id: 4,
                            nombre: "Prof. García",
                            tipo: "catedrático",
                            foto: "imagenes/IMG/users/user.avif"
                        }
                    ]
                }
            ];
        }

        function obtenerDatosSalonPorFecha() {
            return [{
                instalacion: "Edificio A",
                nivel: "1",
                salon: "101",
                fecha: "2023-05-01",
                registros: [{
                        id: 1,
                        nombre: "Juan Pérez",
                        tipo: "estudiante",
                        foto: "imagenes/IMG/users/user.avif"
                    },
                    {
                        id: 2,
                        nombre: "Prof. Rodríguez",
                        tipo: "catedrático",
                        foto: "imagenes/IMG/users/user.avif"
                    }
                ]
            }];
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
        tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;color:green;">
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
                        <option value="line">Líneas</option>
                        <option value="pie">Circular</option>
                        <option value="doughnut">Dona</option>
                        <option value="radar">Radar</option>
                    </select>
                </div>
                
                <div class="form-group date-range">
                    <label for="rango-fechas">Rango de Fechas:</label>
                    <div class="date-inputs">
                        <input type="date" id="fecha-inicio" class="form-control">
                        <span>a</span>
                        <input type="date" id="fecha-fin" class="form-control">
                    </div>
                </div>
                
                <button onclick="cargarEstadisticas()" class="btn-generar">
                    <i class="fas fa-chart-bar"></i> Generar Grafico
                </button>
            </div>
            
            <div id="loading-spinner" class="loading-spinner">
                <div class="spinner"></div>
                <p>Generando estadísticas...</p>
            </div>
            
            <div id="stats-results" class="stats-results">
                <div class="placeholder-message">
                    <i class="fas fa-chart-pie"></i>
                    <p>Seleccione los parámetros para generar el reporte estadístico</p>
                </div>
            </div>
            
            <div id="stats-error" class="stats-error"></div>
        </div>
    `;

            // Establecer fechas por defecto (últimos 7 días)
            const hoy = new Date();
            const hace7Dias = new Date();
            hace7Dias.setDate(hoy.getDate() - 7);

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
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
</body>

</html>