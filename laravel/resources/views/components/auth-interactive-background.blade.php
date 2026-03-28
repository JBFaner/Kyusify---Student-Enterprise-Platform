<div id="matter-container" class="fixed inset-0 -z-10 overflow-hidden bg-gray-50 dark:bg-[#08040C] transition-colors duration-500">
</div>

<!-- Include Matter.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/matter-js/0.19.0/matter.min.js"></script>

<script>
    document.addEventListener('alpine:init', () => {
        // We do not strictly need Alpine for this, but to align with the rest of the file
    });

    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('matter-container');
        
        // Matter.js module aliases
        const Engine = Matter.Engine,
              Render = Matter.Render,
              Runner = Matter.Runner,
              MouseConstraint = Matter.MouseConstraint,
              Mouse = Matter.Mouse,
              World = Matter.World,
              Bodies = Matter.Bodies,
              Composite = Matter.Composite;

        // Create engine
        const engine = Engine.create();
        const world = engine.world;

        // Create renderer
        const render = Render.create({
            element: container,
            engine: engine,
            options: {
                width: window.innerWidth,
                height: window.innerHeight,
                background: 'transparent',
                wireframes: false,
                pixelRatio: window.devicePixelRatio
            }
        });

        Render.run(render);

        // Create runner
        const runner = Runner.create();
        Runner.run(runner, engine);

        // Add walls (Ground, Left, Right)
        const wallOptions = { isStatic: true, render: { visible: false } };
        // The floor is slightly below to give a nice padded look, but we want balls to strictly stay visible inside
        let ground = Bodies.rectangle(window.innerWidth / 2, window.innerHeight + 25, window.innerWidth * 2, 50, wallOptions);
        let leftWall = Bodies.rectangle(-25, window.innerHeight / 2, 50, window.innerHeight * 2, wallOptions);
        let rightWall = Bodies.rectangle(window.innerWidth + 25, window.innerHeight / 2, 50, window.innerHeight * 2, wallOptions);
        
        // Add ceiling infinitely high so they don't jump out of the box vertically
        const ceiling = Bodies.rectangle(window.innerWidth / 2, -3000, window.innerWidth * 2, 100, wallOptions);

        Composite.add(world, [ground, leftWall, rightWall, ceiling]);

        // Icons library (SVG Paths)
        const icons = [
            '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>', // User
            '<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>', // Bag
            '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>', // Cart
            '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>', // Star
            '<line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>' // Cash
        ];

        // Color palettes
        const colors = [
            { bg: '#8b5cf6', icon: '#ffffff', stroke: '#7c3aed' }, // Violet-500
            { bg: '#c084fc', icon: '#ffffff', stroke: '#a78bfa' }, // Purple-400
            { bg: '#e879f9', icon: '#ffffff', stroke: '#d946ef' }, // Fuchsia-400
            { bg: '#4c1d95', icon: '#c084fc', stroke: '#3b0764' }, // Violet-900
            { bg: '#1e1b4b', icon: '#8b5cf6', stroke: '#312e81' }, // Indigo-950
            { bg: '#fcd34d', icon: '#92400e', stroke: '#fbbf24' }, // Amber-300
            { bg: '#ffffff', icon: '#8b5cf6', stroke: '#f3f4f6' }  // White
        ];

        // Helper to generate Data URI textures dynamically
        const getTextures = (radius) => {
            const size = radius * 2 + 4; // Padding
            return icons.flatMap(icon => {
                return colors.map(color => {
                    const center = size / 2;
                    // SVG literal: Creates a circular ball with given fill, stroke, and an inner <svg> for the icon.
                    const svg = `<svg width="${size}" height="${size}" xmlns="http://www.w3.org/2000/svg"><circle cx="${center}" cy="${center}" r="${radius}" fill="${color.bg}" stroke="${color.stroke}" stroke-width="2"/><svg x="${center - 12}" y="${center - 12}" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="${color.icon}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${icon}</svg></svg>`;
                    return 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svg);
                });
            });
        };

        const radius = window.innerWidth < 768 ? 22 : 32;
        const textures = getTextures(radius);

        // Calculate amount of balls needed to fill ~65% of the screen box
        const screenArea = window.innerWidth * window.innerHeight;
        const targetArea = screenArea * 0.55; 
        const ballArea = Math.PI * radius * radius;
        let numBalls = Math.floor(targetArea / ballArea) * 0.55; // packing density
        
        // Cap objects to ensure it stays highly performant in React/Vue/Blade
        numBalls = Math.min(Math.max(numBalls, 80), 300);
        
        const bodies = [];
        for (let i = 0; i < numBalls; i++) {
            const texture = textures[Math.floor(Math.random() * textures.length)];
            const x = Math.random() * window.innerWidth;
            // Spawn them dispersed high up so they elegantly rain down to fill the box
            const y = -Math.random() * window.innerHeight * 2 - 100;
            
            const ball = Bodies.circle(x, y, radius, {
                restitution: 0.85, // Highly bouncy!
                friction: 0.005,
                density: 0.04,
                render: {
                    sprite: {
                        texture: texture,
                        xScale: 1,
                        yScale: 1
                    }
                }
            });
            bodies.push(ball);
        }
        
        Composite.add(world, bodies);

        // Allow physically picking up balls on the background
        const mouse = Mouse.create(render.canvas);
        const mouseConstraint = MouseConstraint.create(engine, {
            mouse: mouse,
            constraint: {
                stiffness: 0.1,
                render: { visible: false }
            }
        });
        
        // Bouncy Repulsion Effect on global mouse move 
        // This ensures tracking works even if hovering over form cards!
        document.addEventListener('mousemove', (e) => {
            const mousePosition = { x: e.clientX, y: e.clientY };
            bodies.forEach(body => {
                const dx = body.position.x - mousePosition.x;
                const dy = body.position.y - mousePosition.y;
                const distanceSq = dx * dx + dy * dy;
                // Interaction explosion radius
                if (distanceSq < 20000) {
                    const forceMagnitude = 0.03 * body.mass;
                    Matter.Body.applyForce(body, body.position, {
                        x: (dx / Math.sqrt(distanceSq)) * forceMagnitude,
                        y: (dy / Math.sqrt(distanceSq)) * forceMagnitude - 0.01 // Slight upward bump to counter gravity
                    });
                }
            });
        });

        Composite.add(world, mouseConstraint);
        render.mouse = mouse;

        // Keep walls exactly on resize
        window.addEventListener('resize', () => {
            render.canvas.width = window.innerWidth;
            render.canvas.height = window.innerHeight;
            
            Matter.Body.setPosition(ground, { x: window.innerWidth / 2, y: window.innerHeight + 25 });
            Matter.Body.setPosition(rightWall, { x: window.innerWidth + 25, y: window.innerHeight / 2 });
            Matter.Body.setPosition(ceiling, { x: window.innerWidth / 2, y: -3000 });
        });
    });
</script>
