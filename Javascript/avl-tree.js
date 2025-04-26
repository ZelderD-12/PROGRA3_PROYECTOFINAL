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

    // Helper functions
    getHeight(node) {
        return node ? node.height : 0;
    }

    updateHeight(node) {
        node.height = Math.max(this.getHeight(node.left), this.getHeight(node.right)) + 1;
    }

    getBalanceFactor(node) {
        return this.getHeight(node.left) - this.getHeight(node.right);
    }

    // Rotations
    rotateRight(y) {
        const x = y.left;
        const T2 = x.right;

        x.right = y;
        y.left = T2;

        this.updateHeight(y);
        this.updateHeight(x);

        return x;
    }

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
            return node; // Duplicate values are not allowed
        }

        this.updateHeight(node);

        const balance = this.getBalanceFactor(node);

        // Balance the tree
        if (balance > 1 && value < node.left.value) return this.rotateRight(node);
        if (balance < -1 && value > node.right.value) return this.rotateLeft(node);
        if (balance > 1 && value > node.left.value) {
            node.left = this.rotateLeft(node.left);
            return this.rotateRight(node);
        }
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
        return value < node.value ? this._search(node.left, value) : this._search(node.right, value);
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
}

function dibujarArbolAVL(containerId, data, tipoReporte) {
    const container = document.getElementById(containerId);
    if (!container) {
        console.error(`Contenedor ${containerId} no encontrado`);
        return;
    }

    container.innerHTML = ''; // Clear container

    const canvas = document.createElement('canvas');
    canvas.width = container.offsetWidth;
    canvas.height = 600;
    container.appendChild(canvas);

    dibujarArbolEnCanvas(canvas, data);
}

function dibujarArbolEnCanvas(canvas, arbol) {
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);

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

    function dibujarLinea(x1, y1, x2, y2) {
        ctx.beginPath();
        ctx.moveTo(x1, y1);
        ctx.lineTo(x2, y2);
        ctx.stroke();
    }

    function dibujar(nodo, x, y, nivel, espacio) {
        if (!nodo) return;

        const offsetY = 80;
        const nuevoEspacio = espacio / 2;

        if (nodo.left) {
            const xIzq = x - nuevoEspacio;
            const yIzq = y + offsetY;
            dibujarLinea(x, y + 20, xIzq, yIzq - 20);
            dibujar(nodo.left, xIzq, yIzq, nivel + 1, nuevoEspacio);
        }

        if (nodo.right) {
            const xDer = x + nuevoEspacio;
            const yDer = y + offsetY;
            dibujarLinea(x, y + 20, xDer, yDer - 20);
            dibujar(nodo.right, xDer, yDer, nivel + 1, nuevoEspacio);
        }

        dibujarNodo(x, y, nodo.value);
    }

    dibujar(arbol, canvas.width / 2, 50, 0, canvas.width / 3);
}

// Data Conversion Functions
function convertirDatosAArbol(data, tipo) {
    if (tipo === 'historicoEntrada') {
        return {
            value: "Entradas",
            left: {
                value: data[0].instalacion,
                left: { value: data[0].puerta },
                right: { value: data[0].fechas[0] }
            },
            right: {
                value: data[1].instalacion,
                left: { value: data[1].puerta },
                right: { value: data[1].fechas[0] }
            }
        };
    }
    return { value: "Raíz" }; // Default structure
}

// Sample Data Functions
function obtenerDatosHistorico() {
    return [
        { instalacion: "Edificio A", puerta: "Principal", fechas: ["2023-05-01", "2023-05-02"] },
        { instalacion: "Edificio B", puerta: "Secundaria", fechas: ["2023-05-03", "2023-05-04"] }
    ];
}

function obtenerDatosPorFecha() {
    return [
        {
            instalacion: "Edificio A",
            puerta: "Principal",
            fecha: "2023-05-01",
            registros: [
                { id: 1, nombre: "Juan Pérez", asistencia: true },
                { id: 2, nombre: "María García", asistencia: false }
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