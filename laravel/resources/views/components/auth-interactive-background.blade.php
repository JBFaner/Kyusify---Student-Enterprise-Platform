<div class="fixed inset-0 -z-10 overflow-hidden bg-violet-50 dark:bg-[#0A0612] transition-colors duration-500" x-data="interactiveBackground()">
    <!-- Base dark purple gradient overlay (Dark Mode Only) -->
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-violet-900/20 via-[#0A0612] to-[#0A0612] hidden dark:block"></div>
    <!-- Base light purple gradient overlay (Light Mode Only) -->
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-violet-200/50 via-violet-50 to-white dark:hidden"></div>
    
    <!-- Animated flowing orbs -->
    <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-violet-400/30 dark:bg-violet-600/30 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[80px] dark:blur-[100px] animate-blob transition-transform duration-[10000ms] ease-in-out" :style="`transform: translate(${mouseX * 0.05}px, ${mouseY * 0.05}px) scale(${1 + Math.sin(time) * 0.1})`"></div>
    
    <div class="absolute top-1/3 right-1/4 w-[600px] h-[600px] bg-fuchsia-300/30 dark:bg-fuchsia-600/20 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[100px] dark:blur-[120px] animate-blob transition-transform duration-[10000ms] ease-in-out" :style="`transform: translate(${mouseX * -0.03}px, ${mouseY * -0.05}px) scale(${1 + Math.cos(time) * 0.1})`"></div>
    
    <div class="absolute bottom-1/4 left-1/2 w-[400px] h-[400px] bg-indigo-300/30 dark:bg-indigo-600/30 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-[70px] dark:blur-[90px] animate-blob transition-transform duration-[10000ms] ease-in-out" :style="`transform: translate(${mouseX * -0.06}px, ${mouseY * 0.04}px) scale(${1 + Math.sin(time + 2) * 0.1})`"></div>

    <!-- Interactive Grid Overlay for depth -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNNTQgMzBoLTZ2MjRoLTZWMzBoLTZWMThoLTZ2MTJoLTZWOThoLTZWMThINnYxMkgwVjE4aDZWMGg2djE4aDZWMGg2djE4aDZWMGg2djE4aDZWMGg2djE4aDZWMHoiIGZpbGw9IiM5QzI3QjAiIGZpbGwtb3BhY2l0eT0iMC4wNSIgZmlsbC1ydWxlPSJldmVub2RkIi8+PC9zdmc+')] [mask-image:linear-gradient(to_bottom,white,transparent)] opacity-30 dark:opacity-50"></div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('interactiveBackground', () => ({
            mouseX: 0,
            mouseY: 0,
            time: 0,
            init() {
                window.addEventListener('mousemove', (e) => {
                    // Normalize mouse coordinates from center of screen
                    this.mouseX = e.clientX - window.innerWidth / 2;
                    this.mouseY = e.clientY - window.innerHeight / 2;
                });
                
                const animate = () => {
                    this.time += 0.01;
                    requestAnimationFrame(animate);
                };
                animate();
            }
        }));
    });
</script>

<style>
    @keyframes auth-blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: auth-blob 15s infinite alternate ease-in-out;
        will-change: transform;
    }
</style>
