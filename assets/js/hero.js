(() => {
  'use strict';

  // Helper for random hex colors
  const randomColors = (count) => {
    return new Array(count)
      .fill(0)
      .map(() => '#' + Math.floor(Math.random() * 16777215).toString(16).padStart(6, '0'));
  };

  const initHeroTubes = async () => {
    const canvas = document.getElementById('hero-canvas');
    const heroSection = document.getElementById('hero-section');
    if (!canvas || !heroSection) return;

    let app = null;

    try {
      // Load TubesCursor from threejs-components CDN
      const module = await import('https://cdn.jsdelivr.net/npm/threejs-components@0.0.19/build/cursors/tubes1.min.js');
      const TubesCursor = module.default;

      // Initialize with vibrant neon tubes matching reference implementation
      app = TubesCursor(canvas, {
        tubes: {
          colors: ["#53bc28", "#f967fb", "#6958d5"],
          lights: {
            intensity: 200,
            colors: ["#83f36e", "#fe8a2e", "#ff008a", "#60aed5"]
          }
        }
      });

      // Fade in canvas after first render so farm photo remains visible during load
      // ponytail: rAF once ensures WebGL cleared before we show the canvas
      requestAnimationFrame(() => {
        canvas.style.transition = 'opacity 0.8s ease';
        canvas.style.opacity = '1';
      });

      // Handle window resize
      const handleResize = () => {
        if (app && typeof app.resize === 'function') {
          app.resize();
        }
      };
      window.addEventListener('resize', handleResize, { passive: true });

      // Click on hero background randomizes tube and light colors
      heroSection.addEventListener('click', (e) => {
        if (e.target.closest('a') || e.target.closest('button')) return;
        if (!app || !app.tubes) return;

        const colors = randomColors(3);
        const lightsColors = randomColors(4);
        app.tubes.setColors(colors);
        app.tubes.setLightsColors(lightsColors);
      });

    } catch (err) {
      console.warn("Could not load 3D TubesCursor:", err);
      // Show canvas anyway if 3D fails (it'll be transparent)
      canvas.style.opacity = '1';
    }
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHeroTubes);
  } else {
    initHeroTubes();
  }
})();
