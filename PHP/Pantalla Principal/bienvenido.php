<?php
include '../Base de Datos/operaciones.php';
$edificios = saberEdificios();
$salones = saberSalones();

?>

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
                            <a href="#" class="dropdown-item" onclick="cambiarConfiguracion('idioma')">
                                <i class="fas fa-language"></i> Cambiar Idioma
                            </a>
                            <a href="#" class="dropdown-item" onclick="cambiarConfiguracion('color')">
                                <i class="fas fa-palette"></i> Color de Pantalla
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
                    
                    <!-- Configuración (misma función que en Administrador) -->
                    <div class="dropdown">
                        <button class="dropdown-btn" onclick="toggleDropdown('configuracion')">
                            <span><i class="fas fa-cog"></i> Configuración</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div id="configuracion" class="dropdown-content">
                            <a href="#" class="dropdown-item" onclick="cambiarConfiguracion('idioma')">
                                <i class="fas fa-language"></i> Cambiar Idioma
                            </a>
                            <a href="#" class="dropdown-item" onclick="cambiarConfiguracion('color')">
                                <i class="fas fa-palette"></i> Color de Pantalla
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
                    
                    <!-- Configuración (misma función que en Administrador) -->
                    <div class="dropdown">
                        <button class="dropdown-btn" onclick="toggleDropdown('configuracion')">
                            <span><i class="fas fa-cog"></i> Configuración</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div id="configuracion" class="dropdown-content">
                            <a href="#" class="dropdown-item" onclick="cambiarConfiguracion('idioma')">
                                <i class="fas fa-language"></i> Cambiar Idioma
                            </a>
                            <a href="#" class="dropdown-item" onclick="cambiarConfiguracion('color')">
                                <i class="fas fa-palette"></i> Color de Pantalla
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
                                <i class="fas fa-history"></i> Reporte histórico de ingresos
                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarReporte('fechaEntrada')">
                                <i class="fas fa-calendar-alt"></i> Reporte por fecha de ingresos 
                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarReporte('historicoSalon')">
                                <i class="fas fa-door-open"></i> Reporte histórico de ingreso por fecha
                            </a>
                            <a href="#" class="dropdown-item" onclick="mostrarReporte('fechaSalon')">
                                <i class="fas fa-clipboard-list"></i> Reporte por fecha de ingreso de salon
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
                    
                    <!-- Configuración (misma función que en Administrador) -->
                    <div class="dropdown">
                        <button class="dropdown-btn" onclick="toggleDropdown('configuracion')">
                            <span><i class="fas fa-cog"></i> Configuración</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div id="configuracion" class="dropdown-content">
                            <a href="#" class="dropdown-item" onclick="cambiarConfiguracion('idioma')">
                                <i class="fas fa-language"></i> Cambiar Idioma
                            </a>
                            <a href="#" class="dropdown-item" onclick="cambiarConfiguracion('color')">
                                <i class="fas fa-palette"></i> Color de Pantalla
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
                    <!-- Mismos botones que estudiante -->
                     <!-- Ver Asistencia -->
                    <button onclick="verAsistencia()" class="btn-dev">
                        <i class="fas fa-user-check"></i> Ver Asistencia
                    </button>
                    
                    <!-- Configuración (misma función que en Administrador) -->
                    <div class="dropdown">
                        <button class="dropdown-btn" onclick="toggleDropdown('configuracion')">
                            <span><i class="fas fa-cog"></i> Configuración</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div id="configuracion" class="dropdown-content">
                            <a href="#" class="dropdown-item" onclick="cambiarConfiguracion('idioma')">
                                <i class="fas fa-language"></i> Cambiar Idioma
                            </a>
                            <a href="#" class="dropdown-item" onclick="cambiarConfiguracion('color')">
                                <i class="fas fa-palette"></i> Color de Pantalla
                            </a>
                                    <a href="#" class="dropdown-item" data-opcion="datos" onclick="cambiarConfiguracion('datos')">
                                 <i class="fas fa-user-edit"></i> Datos Usuario
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
    switch(tipo) {
        case 'idioma':
            document.getElementById('info-content').innerHTML = `
                <h3>Cambiar Idioma</h3>
                <p>Opciones de idioma disponibles.</p>
            `;
            break;
        case 'color':
            document.getElementById('info-content').innerHTML = `
                <h3>Color de Pantalla</h3>
                <p>Seleccione un esquema de color para la interfaz.</p>
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
                        
                        <div class="info-personal">
                            <div class="campo-dato">
                                <label>Nombre:</label>
                                <span>${usuarioData.Nombres_Usuario} ${usuarioData.Apellidos_Usuario}</span>
                            </div>
                            <div class="campo-dato">
                                <label>Carnet:</label>
                                <span>${usuarioData.Carnet_Usuario}</span>
                            </div>
                            <div class="campo-dato">
                                <label>Correo:</label>
                                <span>${usuarioData.Correo_Electronico_Usuario}</span>
                            </div>
                            <div class="campo-dato">
                                <label>Teléfono:</label>
                                <span>${usuarioData.Numero_De_Telefono_Usuario}</span>
                            </div>
                            <div class="campo-dato">
                                <label>Carrera:</label>
                                <span>${usuarioData.Nombre_Carrera}</span>
                            </div>
                            <div class="campo-dato">
                                <label>Tipo de Usuario:</label>
                                <span>${usuarioData.Tipo_Usuario}</span>
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
    } 
    else if (opcion === 'idioma') {
        infoContent.innerHTML = `
            <h3>Cambiar Idioma</h3>
            <p>Opciones de idioma disponibles.</p>
        `;
    } 
    else if (opcion === 'color') {
        infoContent.innerHTML = `
            <h3>Color de Pantalla</h3>
            <p>Seleccione un esquema de color para la interfaz.</p>
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
    let mostrarFecha = false;
    let datos = [];
    let labelDefault = '';
    
    // Determinar qué datos usar y el label
    if (tipoReporte === 'historicoEntrada' || tipoReporte === 'fechaEntrada') {
        // Usar los datos de edificios que ya están disponibles desde PHP
        datos = <?php echo json_encode($edificios); ?>;
        labelDefault = 'Seleccione un edificio';
    } else if (tipoReporte === 'historicoSalon' || tipoReporte === 'fechaSalon') {
        // Usar los datos de salones que ya están disponibles desde PHP
        datos = <?php echo json_encode($salones); ?>;
        labelDefault = 'Seleccione un salón';
    }

    // Si el reporte es por fecha, mostrar input de fecha
    if (tipoReporte === 'fechaEntrada' || tipoReporte === 'fechaSalon') {
        mostrarFecha = true;
    }

    const comboBox = document.getElementById('report-options-select');
    if (!comboBox) return;

    comboBox.innerHTML = '';

    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = labelDefault;
    defaultOption.disabled = true;
    defaultOption.selected = true;
    comboBox.appendChild(defaultOption);

    // Guardar los ids y nombres en arrays si lo necesitas
    const ids = [];
    const nombres = [];

    datos.forEach(item => {
        let id, nombre;
        if (tipoReporte === 'historicoEntrada' || tipoReporte === 'fechaEntrada') {
            id = item.idEdificio;
            nombre = item.Edificio;
        } else {
            id = item.idSalon;
            nombre = item.Area;
        }
        ids.push(id);
        nombres.push(nombre);

        const option = document.createElement('option');
        option.value = id;
        option.textContent = nombre;
        comboBox.appendChild(option);
    });

    // Guarda los arrays globalmente
    window.comboBoxIds = ids;
    window.comboBoxNombres = nombres;

    // También guarda el tipo de combo cargado
    window.comboBoxTipo = (tipoReporte === 'historicoEntrada' || tipoReporte === 'fechaEntrada') ? 'edificio' : 'salon';

    // Manejo del input de fecha
    let fechaInput = document.getElementById('report-date-input');
    if (mostrarFecha) {
        if (!fechaInput) {
            fechaInput = document.createElement('input');
            fechaInput.type = 'date';
            fechaInput.id = 'report-date-input';
            fechaInput.className = 'report-date-input';
            comboBox.parentNode.appendChild(fechaInput);
        }
    } else {
        if (fechaInput) {
            fechaInput.parentNode.removeChild(fechaInput);
        }
    }
}

// Función principal para mostrar reportes
function mostrarReporte(tipo) {
    const baseCSSPath = '../../../CSS/avl-tree.css';
    const baseJSPath = '../../../Javascript/avl-tree.js';

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
    const comboBox = document.getElementById('report-options-select');
    const idEdificio = comboBox ? comboBox.value : null;
    const nombreEdificio = comboBox ? comboBox.options[comboBox.selectedIndex].text : '';

    if (!idEdificio) {
        alert('Seleccione un edificio');
        return;
    }

    // Llama al endpoint para obtener puertas y niveles
    fetch('obtenerPuertasyNiveles.php?idEdificio=' + idEdificio)
        .then(response => response.json())
        .then(data => {
            // Construir el árbol solo con puertas y niveles
            const arbol = {
                valor: nombreEdificio,
                nivel: 0,
                hijos: data.puertas.map(nombrePuerta => ({
                    valor: nombrePuerta,
                    nivel: 1,
                    hijos: data.niveles.map(nivel => ({
                        valor: `Nivel ${nivel}`,
                        nivel: 2,
                        hijos: []
                    }))
                }))
            };
            dibujarArbolAVLCompleto(containerId, arbol);
        })
        .catch(error => {
            alert('Error al obtener puertas y niveles del edificio');
            console.error(error);
        });
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
        case 'historicoEntrada': return obtenerDatosHistorico();
        case 'fechaEntrada': return obtenerDatosPorFecha();
        case 'historicoSalon': return obtenerDatosSalonHistorico();
        case 'fechaSalon': return obtenerDatosSalonPorFecha();
        default: return [];
    }
}

// Función para construir la estructura del árbol desde los datos
function construirArbolDesdeDatos(datos, tipo) {
    const comboBox = document.getElementById('report-options-select');
    const valorRaiz = comboBox?.options[comboBox.selectedIndex]?.text || "Sin selección";
    const fechaInput = document.getElementById('report-date-input');
    const fechaSeleccionada = fechaInput ? fechaInput.value : null;

    const nodoRaiz = {
        valor: valorRaiz,
        nivel: 0,
        hijos: []
    };

    switch (tipo) {
        case 'historicoEntrada':
            const entrada = datos.find(e => e.instalacion === valorRaiz);
            if (entrada) {
                // Usuarios solo en el edificio
                if (entrada.usuarios) {
                    entrada.usuarios
                        .filter(u => u.ubicacion === "edificio")
                        .forEach((usuario, idx) => {
                            nodoRaiz.hijos.push({
                                valor: usuario.nombre,
                                nivel: 1,
                                data: {
                                    ...usuario,
                                    hora: usuario.hora || `${String(8 + idx).padStart(2, '0')}:00`
                                },
                                hijos: []
                            });
                        });
                }
                // Puertas
                if (entrada.puertas) {
                    entrada.puertas.forEach((puerta, idxP) => {
                        const nodoPuerta = {
                            valor: puerta.nombre,
                            nivel: 1,
                            data: { foto: IMG_PATHS.door },
                            hijos: []
                        };
                        // Usuarios en la puerta
                        (puerta.usuarios || [])
                            .filter(u => u.ubicacion === "puerta")
                            .forEach((usuario, idxU) => {
                                nodoPuerta.hijos.push({
                                    valor: usuario.nombre,
                                    nivel: 2,
                                    data: {
                                        ...usuario,
                                        hora: usuario.hora || `${String(8 + idxU).padStart(2, '0')}:00`
                                    },
                                    hijos: []
                                });
                            });
                        // Niveles
                        (puerta.niveles || []).forEach((nivel, idxN) => {
                            const nodoNivel = {
                                valor: `Nivel ${nivel.numero}`,
                                nivel: 2,
                                data: { foto: IMG_PATHS.nivel(nivel.numero) },
                                hijos: []
                            };
                            // Usuarios en el nivel
                            (nivel.usuarios || [])
                                .filter(u => u.ubicacion === "nivel")
                                .forEach((usuario, idxU) => {
                                    nodoNivel.hijos.push({
                                        valor: usuario.nombre,
                                        nivel: 3,
                                        data: {
                                            ...usuario,
                                            hora: usuario.hora || `${String(8 + idxU).padStart(2, '0')}:00`
                                        },
                                        hijos: []
                                    });
                                });
                            // Salones
                            (nivel.salones || []).forEach((salon, idxS) => {
                                const nodoSalon = {
                                    valor: `Salón ${salon.numero}`,
                                    nivel: 3,
                                    data: { foto: IMG_PATHS.classroom },
                                    hijos: []
                                };
                                // Usuarios en el salón
                                (salon.usuarios || [])
                                    .filter(u => u.ubicacion === "salon")
                                    .forEach((usuario, idxU) => {
                                        nodoSalon.hijos.push({
                                            valor: usuario.nombre,
                                            nivel: 4,
                                            data: {
                                                ...usuario,
                                                hora: usuario.hora || `${String(8 + idxU).padStart(2, '0')}:00`
                                            },
                                            hijos: []
                                        });
                                    });
                                nodoNivel.hijos.push(nodoSalon);
                            });
                            nodoPuerta.hijos.push(nodoNivel);
                        });
                        nodoRaiz.hijos.push(nodoPuerta);
                    });
                }
            }
            break;
        case 'fechaEntrada':
            // Solo agregar el primer edificio que coincide y filtrar por fecha
            const entradaFecha = datos.find(e => e.instalacion === valorRaiz && (!fechaSeleccionada || e.fecha === fechaSeleccionada));
            if (entradaFecha) {
                const nodoPuerta = {
                    valor: entradaFecha.puerta,
                    nivel: 1,
                    data: { foto: IMG_PATHS.door },
                    hijos: []
                };
                (entradaFecha.registros || []).forEach((registro, idx) => {
                    nodoPuerta.hijos.push({
                        valor: registro.nombre,
                        nivel: 2,
                        data: {
                            ...registro,
                            hora: `${String(8 + idx).padStart(2, '0')}:00`
                        },
                        hijos: []
                    });
                });
                nodoRaiz.hijos.push(nodoPuerta);
            }
            break;

        case 'historicoSalon':
            // Solo agregar el último salón que coincide
            const salonesHistorico = datos.filter(s => `Salón ${s.salon}` === valorRaiz);
            if (salonesHistorico.length > 0) {
                const salon = salonesHistorico[salonesHistorico.length - 1];
                const nodoNivel = {
                    valor: `Nivel ${salon.nivel}`,
                    nivel: 1,
                    data: { foto: IMG_PATHS.nivel(salon.nivel) },
                    hijos: []
                };
                const nodoSalon = {
                    valor: `Salón ${salon.salon}`,
                    nivel: 2,
                    data: { foto: IMG_PATHS.classroom },
                    hijos: []
                };
                (salon.estudiantes || []).forEach((est, idx) => {
                    nodoSalon.hijos.push({
                        valor: `${est.nombre} (${est.tipo})`,
                        nivel: 3,
                        data: {
                            ...est,
                            hora: `${String(8 + idx).padStart(2, '0')}:00`
                        },
                        hijos: []
                    });
                });
                nodoNivel.hijos.push(nodoSalon);
                nodoRaiz.hijos.push(nodoNivel);
            }
            break;

        case 'fechaSalon':
            // Solo agregar el último salón que coincide y filtrar por fecha
            const salonesFecha = datos.filter(s => `Salón ${s.salon}` === valorRaiz && (!fechaSeleccionada || s.fecha === fechaSeleccionada));
            if (salonesFecha.length > 0) {
                const salon = salonesFecha[salonesFecha.length - 1];
                const nodoNivel = {
                    valor: `Nivel ${salon.nivel}`,
                    nivel: 1,
                    data: { foto: IMG_PATHS.nivel(salon.nivel) },
                    hijos: []
                };
                const nodoSalon = {
                    valor: `Salón ${salon.salon}`,
                    nivel: 2,
                    data: { foto: IMG_PATHS.classroom },
                    hijos: []
                };
                (salon.registros || []).forEach((reg, idx) => {
                    nodoSalon.hijos.push({
                        valor: `${reg.nombre} (${reg.tipo})`,
                        nivel: 3,
                        data: {
                            ...reg,
                            hora: `${String(8 + idx).padStart(2, '0')}:00`
                        },
                        hijos: []
                    });
                });
                nodoNivel.hijos.push(nodoSalon);
                nodoRaiz.hijos.push(nodoNivel);
            }
            break;

        default:
            console.error('Tipo de reporte no reconocido:', tipo);
            break;
    }

    return nodoRaiz;
}

// Nueva función para construir el árbol de ubicaciones
function construirArbolUbicaciones(datos, edificioSeleccionado) {
    // Nodo raíz: el edificio seleccionado
    const nodoRaiz = {
        valor: edificioSeleccionado,
        nivel: 0,
        hijos: []
    };

    // Agrupar por puerta, nivel y salón
    const puertas = {};

    datos.forEach(registro => {
        if (registro.edificio !== edificioSeleccionado) return;

        // Agrupar por puerta
        if (!puertas[registro.puerta]) {
            puertas[registro.puerta] = {};
        }
        const niveles = puertas[registro.puerta];

        // Agrupar por nivel
        if (!niveles[registro.nivel]) {
            niveles[registro.nivel] = {};
        }
        const salones = niveles[registro.nivel];

        // Agrupar por salón
        if (!salones[registro.salon]) {
            salones[registro.salon] = [];
        }
        // Agregar usuario al salón correspondiente
        salones[registro.salon].push(registro);
    });

    // Construir nodos
    Object.entries(puertas).forEach(([puerta, niveles]) => {
        const nodoPuerta = {
            valor: `Puerta ${puerta}`,
            nivel: 1,
            data: { foto: IMG_PATHS.door },
            hijos: []
        };

        Object.entries(niveles).forEach(([nivel, salones]) => {
            const nodoNivel = {
                valor: `Nivel ${nivel}`,
                nivel: 2,
                data: { foto: IMG_PATHS.nivel(nivel) },
                hijos: []
            };

            Object.entries(salones).forEach(([salon, usuarios]) => {
                const nodoSalon = {
                    valor: `Salón ${salon}`,
                    nivel: 3,
                    data: { foto: IMG_PATHS.classroom },
                    hijos: []
                };

                usuarios.forEach((usuario, idx) => {
                    nodoSalon.hijos.push({
                        valor: usuario.nombre,
                        nivel: 4,
                        data: {
                            ...usuario,
                            hora: usuario.hora || usuario.fecha || "",
                            foto: usuario.foto || IMG_PATHS.user
                        },
                        hijos: []
                    });
                });

                nodoNivel.hijos.push(nodoSalon);
            });

            nodoPuerta.hijos.push(nodoNivel);
        });

        nodoRaiz.hijos.push(nodoPuerta);
    });

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
        image.setAttribute('x', -imageSize/2);
        image.setAttribute('y', -imageSize/2);
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

    // Agregado: Mostrar la hora si está disponible
    if (nodo.data && nodo.data.hora) {
        const horaText = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        horaText.setAttribute('text-anchor', 'middle');
        horaText.setAttribute('dominant-baseline', 'hanging');
        horaText.setAttribute('y', TREE_CONFIG.NODE_RADIUS + 28);
        horaText.setAttribute('fill', '#1976d2');
        horaText.setAttribute('font-size', '11px');
        horaText.textContent = nodo.data.hora;
        nodeGroup.appendChild(horaText);
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
    return [
        {
            instalacion: "Edificio B",
            usuarios: [
                { nombre: "Carlos López", foto: IMG_PATHS.user, ubicacion: "edificio" }
            ],
            puertas: [
                {
                    nombre: "Puerta 1",
                    usuarios: [
                        { nombre: "Ana Torres", foto: IMG_PATHS.user, ubicacion: "puerta" }
                    ],
                    niveles: [
                        {
                            numero: 1,
                            usuarios: [],
                            salones: [
                                {
                                    numero: "101",
                                    usuarios: [
                                        { nombre: "Juan Pérez", foto: IMG_PATHS.user, ubicacion: "salon" }
                                    ]
                                }
                            ]
                        }
                    ]
                }
            ]
        }
    ];
}

function obtenerDatosPorFecha() {
    return [
        {
            instalacion: "Edificio A",
            puerta: "Puerta Principal",
            fecha: "2023-05-01",
            registros: [
                { nombre: "Juan Pérez", foto: IMG_PATHS.user, asistencia: true },
                { nombre: "María García", foto: IMG_PATHS.user, asistencia: false }
            ]
        },
        {
            instalacion: "Edificio B",
            puerta: "Puerta Secundaria",
            fecha: "2023-05-02",
            registros: [
                { nombre: "Carlos López", foto: IMG_PATHS.user, asistencia: true },
                { nombre: "Ana Torres", foto: IMG_PATHS.user, asistencia: true }
            ]
        }
    ];
}

function obtenerDatosSalonHistorico() {
    return [
        {
            instalacion: "Edificio A",
            nivel: "1",
            salon: "101",
            estudiantes: [
                { nombre: "Juan Pérez", tipo: "estudiante", foto: IMG_PATHS.user },
                { nombre: "Prof. Rodríguez", tipo: "catedrático", foto: IMG_PATHS.user }
            ]
        },
        {
            instalacion: "Edificio B",
            nivel: "2",
            salon: "202",
            estudiantes: [
                { nombre: "Carlos López", tipo: "estudiante", foto: IMG_PATHS.user },
                { nombre: "Prof. García", tipo: "catedrático", foto: IMG_PATHS.user }
            ]
        }
    ];
}

function obtenerDatosSalonPorFecha() {
    return [
        {
            instalacion: "Edificio A",
            nivel: "1",
            salon: "101",
            fecha: "2023-05-01",
            registros: [
                { nombre: "Juan Pérez", tipo: "estudiante", foto: IMG_PATHS.user },
                { nombre: "Prof. Rodríguez", tipo: "catedrático", foto: IMG_PATHS.user }
            ]
        },
        {
            instalacion: "Edificio B",
            nivel: "2",
            salon: "202",
            fecha: "2023-05-02",
            registros: [
                { nombre: "Carlos López", tipo: "estudiante", foto: IMG_PATHS.user },
                { nombre: "Prof. García", tipo: "catedrático", foto: IMG_PATHS.user }
            ]
        }
    ];
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
    // Función para gestionar usuarios (compartida entre admin y desarrollador)
    document.getElementById('info-content').innerHTML = `
        <h3>Gestor de Usuarios - ${accion}</h3>
        <p>Realizando acción : ${accion}.</p>
    `;
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
    switch(tipo) {
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
            'rgba(75, 192, 192,  0.7)'
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

// Definir rutas de imágenes estáticas globales
const IMG_PATHS = {
    nivel: num => `imagenes/IMG/level/nivel${num}.png`,
    classroom: "imagenes/IMG/objetos/classroom.jpg",
    door: "imagenes/IMG/objetos/door.jpg",
    edificio: "imagenes/IMG/objetos/edificio.jpeg",
    user: "imagenes/IMG/users/user.avif"
};
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