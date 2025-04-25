// AVL Tree Visualization
class AVLNode {
    constructor(value, data = null) {
        this.value = value;
        this.data = data;
        this.left = null;
        this.right = null;
        this.height = 1;
    }
}

class AVLTree {
    constructor() {
        this.root = null;
    }

    // Helper function to get height of a node
    getHeight(node) {
        return node ? node.height : 0;
    }

    // Helper function to update height of a node
    updateHeight(node) {
        node.height = Math.max(this.getHeight(node.left), this.getHeight(node.right)) + 1;
    }

    // Helper function to get balance factor
    getBalanceFactor(node) {
        return this.getHeight(node.left) - this.getHeight(node.right);
    }

    // Right rotation
    rotateRight(y) {
        const x = y.left;
        const T2 = x.right;

        x.right = y;
        y.left = T2;

        this.updateHeight(y);
        this.updateHeight(x);

        return x;
    }

    // Left rotation
    rotateLeft(x) {
        const y = x.right;
        const T2 = y.left;

        y.left = x;
        x.right = T2;

        this.updateHeight(x);
        this.updateHeight(y);

        return y;
    }

    // Insert a value into the AVL tree
    insert(value, data = null) {
        this.root = this._insert(this.root, value, data);
    }

    _insert(node, value, data) {
        if (!node) return new AVLNode(value, data);

        if (value < node.value) {
            node.left = this._insert(node.left, value, data);
        } else if (value > node.value) {
            node.right = this._insert(node.right, value, data);
        } else {
            // Duplicate values not allowed (or you could handle them differently)
            return node;
        }

        this.updateHeight(node);

        const balance = this.getBalanceFactor(node);

        // Left Left Case
        if (balance > 1 && value < node.left.value) {
            return this.rotateRight(node);
        }

        // Right Right Case
        if (balance < -1 && value > node.right.value) {
            return this.rotateLeft(node);
        }

        // Left Right Case
        if (balance > 1 && value > node.left.value) {
            node.left = this.rotateLeft(node.left);
            return this.rotateRight(node);
        }

        // Right Left Case
        if (balance < -1 && value < node.right.value) {
            node.right = this.rotateRight(node.right);
            return this.rotateLeft(node);
        }

        return node;
    }

    // Search for a value in the AVL tree
    search(value) {
        return this._search(this.root, value);
    }

    _search(node, value) {
        if (!node) return null;
        if (value === node.value) return node;
        if (value < node.value) return this._search(node.left, value);
        return this._search(node.right, value);
    }

    // In-order traversal
    inOrder() {
        const result = [];
        this._inOrder(this.root, result);
        return result;
    }

    _inOrder(node, result) {
        if (node) {
            this._inOrder(node.left, result);
            result.push(node);
            this._inOrder(node.right, result);
        }
    }
}

// AVL Tree Visualization Functions
function initAVLTree(containerId) {
    console.log(`Árbol AVL inicializado en: ${containerId}`);
    // Inicialización adicional si es necesaria
}

function dibujarArbolAVL(containerId, data, tipoReporte) {
    const container = document.getElementById(containerId);
    if (!container) {
        console.error(`Contenedor ${containerId} no encontrado`);
        return;
    }

    // Limpiar contenedor
    container.innerHTML = '';

    // Crear elementos de visualización
    const visualization = document.createElement('div');
    visualization.className = 'avl-tree-visualization';
    
    // Crear SVG para el árbol
    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('width', '100%');
    svg.setAttribute('height', '100%');
    
    // Grupo principal
    const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    g.setAttribute('transform', 'translate(50, 50)');
    
    // Ejemplo básico de nodos y conexiones
    const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
    circle.setAttribute('class', 'node');
    circle.setAttribute('cx', '100');
    circle.setAttribute('cy', '50');
    circle.setAttribute('r', '20');
    
    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    text.setAttribute('x', '100');
    text.setAttribute('y', '50');
    text.setAttribute('text-anchor', 'middle');
    text.setAttribute('dy', '.3em');
    text.setAttribute('fill', 'white');
    text.textContent = 'Raíz';
    
    // Ensamblar elementos
    g.appendChild(circle);
    g.appendChild(text);
    svg.appendChild(g);
    visualization.appendChild(svg);
    container.appendChild(visualization);

    console.log('Árbol AVL dibujado:', {containerId, data, tipoReporte});
}

function convertirDatosAArbol(data, tipo) {
    // Esta función convierte tus datos a una estructura de árbol
    // Implementación básica - debes adaptarla a tus necesidades reales
    if (tipo === 'historicoEntrada') {
        return {
            valor: "Entradas",
            izquierda: {
                valor: data[0].instalacion,
                izquierda: { valor: data[0].puerta },
                derecha: { valor: data[0].fechas[0] }
            },
            derecha: {
                valor: data[1].instalacion,
                izquierda: { valor: data[1].puerta },
                derecha: { valor: data[1].fechas[0] }
            }
        };
    }
    // Patrones similares para otros tipos de reportes
    return { valor: "Raíz" }; // Estructura por defecto
}

function dibujarArbolEnCanvas(canvas, arbol) {
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Función para dibujar un nodo
    function dibujarNodo(x, y, texto) {
        const radio = 20;
        ctx.beginPath();
        ctx.arc(x, y, radio, 0, Math.PI * 2);
        ctx.fillStyle = '#4CAF50';
        ctx.fill();
        ctx.stroke();
        ctx.fillStyle = '#000';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(texto, x, y);
    }

    // Función para dibujar una línea
    function dibujarLinea(x1, y1, x2, y2) {
        ctx.beginPath();
        ctx.moveTo(x1, y1);
        ctx.lineTo(x2, y2);
        ctx.stroke();
    }

    // Función recursiva para dibujar el árbol
    function dibujar(nodo, x, y, nivel, espacio) {
        if (!nodo) return;
        
        const offsetY = 80;
        const nuevoEspacio = espacio / 2;
        
        if (nodo.izquierda) {
            const xIzq = x - nuevoEspacio;
            const yIzq = y + offsetY;
            dibujarLinea(x, y + 20, xIzq, yIzq - 20);
            dibujar(nodo.izquierda, xIzq, yIzq, nivel + 1, nuevoEspacio);
        }
        
        if (nodo.derecha) {
            const xDer = x + nuevoEspacio;
            const yDer = y + offsetY;
            dibujarLinea(x, y + 20, xDer, yDer - 20);
            dibujar(nodo.derecha, xDer, yDer, nivel + 1, nuevoEspacio);
        }
        
        dibujarNodo(x, y, nodo.valor);
    }

    // Iniciar el dibujo desde la raíz
    dibujar(arbol, canvas.width / 2, 50, 0, canvas.width / 3);
}

function renderTree(container, node, tipoReporte, level = 0) {
    if (!node) return;

    // Create node element
    const nodeElement = document.createElement('div');
    nodeElement.className = `avl-node level-${level}`;
    
    // Add specific classes based on report type and node data
    if (node.data) {
        switch (node.data.tipo) {
            case 'instalacion':
                nodeElement.classList.add('node-instalacion');
                nodeElement.innerHTML = `<strong>${node.value}</strong>`;
                break;
                
            case 'puerta':
                nodeElement.classList.add('node-puerta');
                nodeElement.innerHTML = `<strong>Puerta:</strong> ${node.value.split('-')[1]}`;
                break;
                
            case 'nivel':
                nodeElement.classList.add('node-nivel');
                nodeElement.innerHTML = `<strong>Nivel:</strong> ${node.value.split('-')[1]}`;
                break;
                
            case 'salon':
                nodeElement.classList.add('node-salon');
                nodeElement.innerHTML = `<strong>Salón:</strong> ${node.value.split('-')[2]}`;
                break;
                
            case 'fecha':
                nodeElement.classList.add('node-fecha');
                nodeElement.innerHTML = `<strong>Fecha:</strong> ${node.data.data.fecha}`;
                break;
                
            case 'registro':
                nodeElement.classList.add('node-registro');
                nodeElement.classList.add(node.data.asistencia ? 'asistencia-si' : 'asistencia-no');
                
                if (tipoReporte === 'fechaEntrada' || tipoReporte === 'fechaSalon') {
                    nodeElement.innerHTML = `
                        <div class="registro-info">
                            <img src="${node.data.data.foto || 'default.jpg'}" alt="Foto" class="registro-foto">
                            <div>
                                <p><strong>${node.data.data.nombre || 'Nombre no disponible'}</strong></p>
                                <p>${node.data.data.correo || 'Correo no disponible'}</p>
                                <p>${node.data.asistencia ? 'Asistió' : 'No asistió'}</p>
                            </div>
                        </div>
                    `;
                } else {
                    nodeElement.innerHTML = `<p>${node.value}</p>`;
                }
                break;
                
            default:
                nodeElement.innerHTML = `<p>${node.value}</p>`;
        }
    } else {
        nodeElement.innerHTML = `<p>${node.value}</p>`;
    }

    // Add click event for interactive features
    nodeElement.addEventListener('click', (e) => {
        e.stopPropagation();
        // Handle node click (expand/collapse or show details)
        const children = nodeElement.parentElement.querySelector('.avl-children');
        if (children) {
            children.classList.toggle('collapsed');
        }
    });

    // Create children container
    const childrenContainer = document.createElement('div');
    childrenContainer.className = 'avl-children';

    // Render left and right children
    if (node.left || node.right) {
        if (node.left) {
            const leftChild = renderTree(container, node.left, tipoReporte, level + 1);
            childrenContainer.appendChild(leftChild);
        }
        if (node.right) {
            const rightChild = renderTree(container, node.right, tipoReporte, level + 1);
            childrenContainer.appendChild(rightChild);
        }
    }

    // Create node container
    const nodeContainer = document.createElement('div');
    nodeContainer.className = 'avl-node-container';
    nodeContainer.appendChild(nodeElement);
    
    if (node.left || node.right) {
        nodeContainer.appendChild(childrenContainer);
    }

    return nodeContainer;
}

// Sample data functions (replace with actual data fetching)
function obtenerDatosHistorico() {
    // Mock data for historical report
    return [
        {
            instalacion: "Edificio A",
            puerta: "Principal",
            fechas: ["2023-05-01", "2023-05-02", "2023-05-03"]
        },
        {
            instalacion: "Edificio A",
            puerta: "Secundaria",
            fechas: ["2023-05-01", "2023-05-04"]
        },
        {
            instalacion: "Edificio B",
            puerta: "Principal",
            fechas: ["2023-05-02", "2023-05-05"]
        }
    ];
}

function obtenerDatosPorFecha() {
    // Mock data for date-specific report
    return [
        {
            instalacion: "Edificio A",
            puerta: "Principal",
            fecha: "2023-05-01",
            registros: [
                { id: 1, nombre: "Juan Pérez", correo: "juan@example.com", foto: "user1.jpg", asistencia: true },
                { id: 2, nombre: "María García", correo: "maria@example.com", foto: "user2.jpg", asistencia: false }
            ]
        }
    ];
}

function obtenerDatosSalonHistorico() {
    // Mock data for classroom historical report
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
    // Mock data for classroom date-specific report
    return [
        {
            instalacion: "Edificio A",
            nivel: "1",
            salon: "101",
            fecha: "2023-05-01",
            registros: [
                { id: 1, nombre: "Juan Pérez", correo: "juan@example.com", foto: "user1.jpg", asistencia: true, tipo: "estudiante" },
                { id: 2, nombre: "Prof. Rodríguez", correo: "prof@example.com", foto: "user3.jpg", asistencia: true, tipo: "catedrático" }
            ]
        }
    ];
}