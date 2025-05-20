// Configuración mejorada del árbol
const TREE_CONFIG = {
    NODE_RADIUS: 35, // Aumentado de 25 a 35
    VERTICAL_SPACING: 120, // Aumentado de 80 a 120
    HORIZONTAL_SPACING: 60, // Aumentado para más espacio
    LEVEL_HEIGHT: 100,
    IMAGE_SIZE: 50, // Tamaño de la imagen
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

    // Crear elemento SVG para el árbol
    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('width', '100%');
    svg.setAttribute('height', '800'); // Altura aumentada
    svg.style.display = 'block';
    svg.style.margin = '20px auto';
    svg.style.overflow = 'visible';

    // Grupo principal
    const g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
    g.setAttribute('transform', 'translate(0, 80)');
    svg.appendChild(g);
    container.appendChild(svg);

    // Calcular posiciones con más espacio
    function calcularPosiciones(nodo, nivel, posX, espacioDisponible) {
        if (!nodo) return;

        const posY = nivel * TREE_CONFIG.VERTICAL_SPACING;
        nodo.x = posX;
        nodo.y = posY;

        if (nodo.hijos && nodo.hijos.length > 0) {
            const totalHijos = nodo.hijos.length;
            const espacioRequerido = Math.max(
                TREE_CONFIG.HORIZONTAL_SPACING * (totalHijos - 1), 
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
    calcularPosiciones(arbol, 0, container.offsetWidth / 2, container.offsetWidth);

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
            `imagenes/IMG/users/${ruta.split('/').pop()}`,
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

    // Dibujar nodos mejorados
    async function dibujarNodos(nodo, g) {
        if (!nodo || nodo.x === undefined || nodo.y === undefined) return;

        const nodeGroup = document.createElementNS('http://www.w3.org/2000/svg', 'g');
        nodeGroup.setAttribute('class', 'avl-node-group');
        nodeGroup.setAttribute('transform', `translate(${nodo.x}, ${nodo.y})`);

        // Crear fondo del nodo
        const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        circle.setAttribute('r', TREE_CONFIG.NODE_RADIUS);
        circle.setAttribute('fill', TREE_CONFIG.NODE_COLORS.default);
        circle.setAttribute('stroke', TREE_CONFIG.NODE_COLORS.stroke);
        circle.setAttribute('stroke-width', '2');
        circle.setAttribute('class', 'node-circle');
        nodeGroup.appendChild(circle);

        // Cargar imagen (si existe)
        let imagenUrl = 'imagenes/IMG/users/user.png';
        if (nodo.data && nodo.data.foto) {
            imagenUrl = await cargarImagenSegura(nodo.data.foto);
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
        
        // Tooltip para texto completo
        if (nodo.valor.length > 15) {
            nodeGroup.setAttribute('title', nodo.valor);
        }
        
        nodeGroup.appendChild(text);

        g.appendChild(nodeGroup);

        // Dibujar hijos recursivamente
        if (nodo.hijos) {
            for (const hijo of nodo.hijos) {
                await dibujarNodos(hijo, g);
            }
        }
    }

    // Iniciar el dibujo del árbol
    (async () => {
        await dibujarNodos(arbol, g);
        
        // Ajustar tamaño del SVG
        const bbox = g.getBBox();
        const svgWidth = Math.max(bbox.width + 100, container.offsetWidth);
        const svgHeight = Math.max(bbox.height + 150, 600);
        
        svg.setAttribute('width', svgWidth);
        svg.setAttribute('height', svgHeight);
        
        // Centrar el árbol si es más pequeño que el contenedor
        if (bbox.width < container.offsetWidth) {
            g.setAttribute('transform', `translate(${(container.offsetWidth - bbox.width) / 2}, 80)`);
        }
    })();
}

    // Iniciar el dibujo del árbol
    (async () => {
        await dibujarNodos(arbol, g);
        
        // Ajustar tamaño del SVG
        const bbox = g.getBBox();
        const svgWidth = Math.max(bbox.width + 100, container.offsetWidth);
        const svgHeight = Math.max(bbox.height + 150, 600);
        
        svg.setAttribute('width', svgWidth);
        svg.setAttribute('height', svgHeight);
        
        // Centrar el árbol si es más pequeño que el contenedor
        if (bbox.width < container.offsetWidth) {
            g.setAttribute('transform', `translate(${(container.offsetWidth - bbox.width) / 2}, 80)`);
        }
    })();