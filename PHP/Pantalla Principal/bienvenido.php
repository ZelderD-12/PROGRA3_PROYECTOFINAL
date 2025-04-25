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
                    
                    <!-- Imprimir PDF -->
                    <button onclick="generarPDFAdmin()" class="btn-dev">
                        <i class="fas fa-file-pdf"></i> Imprimir PDF
                    </button>
                    
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
                            <a href="#" class="dropdown-item" onclick="cambiarConfiguracion('datos')">
                                <i class="fas fa-user-edit"></i> Datos Usuario
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
                    
                    <!-- Generar PDF (misma función que en Administrador) -->
                    <button onclick="generarPDFAdmin()" class="btn-dev">
                        <i class="fas fa-file-pdf"></i> Imprimir PDF
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
                            <a href="#" class="dropdown-item" onclick="cambiarConfiguracion('datos')">
                                <i class="fas fa-user-edit"></i> Datos Usuario
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
                    <!-- Tomar Asistencia -->
                    <button onclick="tomarAsistencia()" class="btn-dev">
                        <i class="fas fa-user-check"></i> Tomar Asistencia
                    </button>
                    
                    <!-- Generar PDF (misma función que en Administrador) -->
                    <button onclick="generarPDFAdmin()" class="btn-dev">
                        <i class="fas fa-file-pdf"></i> Imprimir PDF
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
                            <a href="#" class="dropdown-item" onclick="cambiarConfiguracion('datos')">
                                <i class="fas fa-user-edit"></i> Datos Usuario
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
                    
                    <!-- Imprimir PDF (misma función que en Administrador) -->
                    <button onclick="generarPDFAdmin()" class="btn-dev">
                        <i class="fas fa-file-pdf"></i> Imprimir PDF
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
                            <a href="#" class="dropdown-item" onclick="cambiarConfiguracion('datos')">
                                <i class="fas fa-user-edit"></i> Datos Usuario
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
                    <!-- Tomar Asistencia -->
                    <button onclick="tomarAsistencia()" class="btn-dev">
                        <i class="fas fa-user-check"></i> Tomar Asistencia
                    </button>
                    
                    <!-- Generar PDF (misma función que en Administrador) -->
                    <button onclick="generarPDFAdmin()" class="btn-dev">
                        <i class="fas fa-file-pdf"></i> Imprimir PDF
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
                            <a href="#" class="dropdown-item" onclick="cambiarConfiguracion('datos')">
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

function generarPDFAdmin() {
    // Función para generar PDF que comparten todos los tipos de usuario
    alert('Generando PDF...');
}

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

function mostrarReporte(tipo) {
    // Definir las rutas base de los archivos
    // Rutas corregidas (ajustadas a tu estructura de proyecto)
const baseCSSPath = '../../CSS/avl-tree.css';
const baseJSPath = '../../Javascript/avl-tree.js';
    
    let contenido = '';
    switch (tipo) {
        case 'historicoEntrada':
            contenido = `
                <h3>Reporte Histórico de Ingresos</h3>
                <p>Visualización del árbol AVL con datos históricos de ingresos.</p>
                <div id="avl-tree-historico" class="avl-tree-container"></div>
            `;
            // Cargar recursos con rutas correctas
            cargarRecursosAVL(baseCSSPath, baseJSPath, () => {
                initAVLTree('avl-tree-historico');
                dibujarArbolAVL('avl-tree-historico', obtenerDatosHistorico(), 'historicoEntrada');
            });
            break;

        case 'fechaEntrada':
            contenido = `
                <h3>Reporte por Fecha de Ingresos</h3>
                <p>Visualización del árbol AVL con datos por fecha de ingresos.</p>
                <div id="avl-tree-fecha" class="avl-tree-container"></div>
            `;
            cargarRecursosAVL(baseCSSPath, baseJSPath, () => {
                initAVLTree('avl-tree-fecha');
                dibujarArbolAVL('avl-tree-fecha', obtenerDatosPorFecha(), 'fechaEntrada');
            });
            break;

        case 'historicoSalon':
            contenido = `
                <h3>Reporte Histórico por Salón</h3>
                <p>Visualización del árbol AVL con datos históricos por salón.</p>
                <div id="avl-tree-salon-historico" class="avl-tree-container"></div>
            `;
            cargarRecursosAVL(baseCSSPath, baseJSPath, () => {
                initAVLTree('avl-tree-salon-historico');
                dibujarArbolAVL('avl-tree-salon-historico', obtenerDatosSalonHistorico(), 'historicoSalon');
            });
            break;

        case 'fechaSalon':
            contenido = `
                <h3>Reporte por Fecha y Salón</h3>
                <p>Visualización del árbol AVL con datos por fecha y salón.</p>
                <div id="avl-tree-salon-fecha" class="avl-tree-container"></div>
            `;
            cargarRecursosAVL(baseCSSPath, baseJSPath, () => {
                initAVLTree('avl-tree-salon-fecha');
                dibujarArbolAVL('avl-tree-salon-fecha', obtenerDatosSalonPorFecha(), 'fechaSalon');
            });
            break;

        default:
            contenido = `
                <h3>Reporte no encontrado</h3>
                <p>El tipo de reporte solicitado no está disponible.</p>
            `;
            break;
    }

    document.getElementById('info-content').innerHTML = contenido;
}

function cargarRecursosAVL(cssPath, jsPath, callback) {
    let cssLoaded = false;
    let jsLoaded = false;

    function verificarCarga() {
        if (cssLoaded && jsLoaded) {
            callback();
        }
    }

    // Cargar CSS
    if (!document.querySelector(`link[href="${cssPath}"]`)) {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = cssPath;
        link.onload = () => {
            cssLoaded = true;
            verificarCarga();
        };
        link.onerror = () => {
            console.error(`Error al cargar CSS: ${cssPath}`);
            cssLoaded = true; // Continuar aunque falle el CSS
            verificarCarga();
        };
        document.head.appendChild(link);
    } else {
        cssLoaded = true;
        verificarCarga();
    }

    // Cargar JS
    if (!document.querySelector(`script[src="${jsPath}"]`)) {
        const script = document.createElement('script');
        script.src = jsPath;
        script.onload = () => {
            jsLoaded = true;
            verificarCarga();
        };
        script.onerror = () => {
            console.error(`Error al cargar JS: ${jsPath}`);
            // Mostrar mensaje de error en el contenedor
            const container = document.getElementById('info-content');
            if (container) {
                container.innerHTML += `
                    <div class="error-message">
                        <p>No se pudo cargar la visualización del árbol AVL.</p>
                        <p>El archivo JavaScript no se encontró en: ${jsPath}</p>
                    </div>
                `;
            }
        };
        document.body.appendChild(script);
    } else {
        jsLoaded = true;
        verificarCarga();
    }
}
// Funciones para obtener datos (actualizadas)
function obtenerDatosHistorico() {
    // Datos de ejemplo para el reporte histórico
    return [
        {
            instalacion: "Edificio A",
            puerta: "Principal",
            fechas: ["2023-05-01", "2023-05-02", "2023-05-03"]
        },
        {
            instalacion: "Edificio B",
            puerta: "Secundaria",
            fechas: ["2023-05-01", "2023-05-04"]
        }
    ];
}

function obtenerDatosPorFecha() {
    // Datos de ejemplo para reporte por fecha
    return [
        {
            instalacion: "Edificio A",
            puerta: "Principal",
            fecha: "2023-05-01",
            registros: [
                { 
                    id: 1, 
                    nombre: "Juan Pérez", 
                    correo: "juan@example.com", 
                    foto: "img/users/user1.jpg", 
                    asistencia: true 
                },
                { 
                    id: 2, 
                    nombre: "María García", 
                    correo: "maria@example.com", 
                    foto: "img/users/user2.jpg", 
                    asistencia: false 
                }
            ]
        }
    ];
}

function obtenerDatosSalonHistorico() {
    // Datos de ejemplo para reporte histórico por salón
    return [
        {
            instalacion: "Edificio A",
            nivel: "1",
            salon: "101",
            estudiantes: [
                { id: 1, nombre: "Juan Pérez", tipo: "estudiante" },
                { id: 2, nombre: "Prof. Rodríguez", tipo: "catedrático" }
            ]
        }
    ];
}

function obtenerDatosSalonPorFecha() {
    // Datos de ejemplo para reporte por fecha y salón
    return [
        {
            instalacion: "Edificio A",
            nivel: "1",
            salon: "101",
            fecha: "2023-05-01",
            registros: [
                { 
                    id: 1, 
                    nombre: "Juan Pérez", 
                    correo: "juan@example.com", 
                    foto: "img/users/user1.jpg", 
                    asistencia: true, 
                    tipo: "estudiante" 
                },
                { 
                    id: 2, 
                    nombre: "Prof. Rodríguez", 
                    correo: "prof@example.com", 
                    foto: "img/users/prof1.jpg", 
                    asistencia: true, 
                    tipo: "catedrático" 
                }
            ]
        }
    ];
}

// Función para dibujar el árbol AVL (mejorada)
function dibujarArbolAVL(containerId, data, tipoReporte) {
    const container = document.getElementById(containerId);
    if (!container) return;

    // Limpiar el contenedor
    container.innerHTML = '';

    // Crear canvas para el árbol
    const canvas = document.createElement('canvas');
    canvas.width = container.offsetWidth;
    canvas.height = 600; // Altura fija para el árbol
    container.appendChild(canvas);

    const ctx = canvas.getContext('2d');

    // Dibujar el árbol AVL
    function dibujarNodo(x, y, texto, nivel) {
        const radio = 20; // Radio del nodo
        ctx.beginPath();
        ctx.arc(x, y, radio, 0, 2 * Math.PI); // Dibujar el círculo
        ctx.fillStyle = '#4CAF50';
        ctx.fill();
        ctx.strokeStyle = '#000';
        ctx.stroke();
        ctx.fillStyle = '#FFF';
        ctx.font = '14px Arial';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(texto, x, y); // Dibujar el texto dentro del nodo
    }

    function dibujarLinea(x1, y1, x2, y2) {
        ctx.beginPath();
        ctx.moveTo(x1, y1);
        ctx.lineTo(x2, y2);
        ctx.strokeStyle = '#000';
        ctx.stroke();
    }

    function dibujarArbol(data, x, y, nivel, offsetX) {
        if (!data) return;

        // Dibujar el nodo actual
        dibujarNodo(x, y, data.valor, nivel);

        // Dibujar las conexiones y los nodos hijos
        const nextY = y + 80; // Distancia vertical entre niveles
        if (data.izquierda) {
            const nextXIzquierda = x - offsetX / 2;
            dibujarLinea(x, y + 20, nextXIzquierda, nextY - 20);
            dibujarArbol(data.izquierda, nextXIzquierda, nextY, nivel + 1, offsetX / 2);
        }
        if (data.derecha) {
            const nextXDerecha = x + offsetX / 2;
            dibujarLinea(x, y + 20, nextXDerecha, nextY - 20);
            dibujarArbol(data.derecha, nextXDerecha, nextY, nivel + 1, offsetX / 2);
        }
    }

    // Calcular el punto inicial para el nodo raíz
    const rootX = canvas.width / 2;
    const rootY = 50;
    const offsetX = canvas.width / 4; // Separación horizontal inicial

    // Dibujar el árbol AVL
    dibujarArbol(data, rootX, rootY, 0, offsetX);

    console.log('Árbol AVL dibujado:', { containerId, data, tipoReporte });
}


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
        <p>Realizando acción: ${accion}.</p>
    `;
}

function abrirRegistroGeneral() {
    // Muestra el registro general
    document.getElementById('info-content').innerHTML = `
        <h3>Registro General</h3>
        <p>Información completa del registro general.</p>
    `;
}

function abrirAsistenciasGenerales() {
    // Función para mostrar todas las asistencias (compartida entre admin y desarrollador)
    document.getElementById('info-content').innerHTML = `
        <h3>Asistencias Generales</h3>
        <p>Resumen de todas las asistencias.</p>
    `;
}

function abrirEstadisticas() {
    // Función para mostrar estadísticas (compartida entre admin y desarrollador)
    document.getElementById('info-content').innerHTML = `
        <h3>Estadísticas</h3>
        <p>Datos estadísticos del sistema.</p>
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