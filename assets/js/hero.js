document.addEventListener('DOMContentLoaded', () => {
  const canvas = document.querySelector('canvas.absolute.inset-0');
  if (!canvas) return;

  const wrapper = canvas.parentElement;
  let app = null;

  const initTubes = async () => {
    try {
      const module = await import('https://cdn.jsdelivr.net/npm/threejs-components@0.0.19/build/cursors/tubes1.min.js');
      const TubesCursor = module.default;

      app = TubesCursor(canvas, {
        tubes: {
          colors: ["#173F35", "#8FAE5D", "#D8C7A1"],
          lights: {
            intensity: 150,
            colors: ["#F6F3EC", "#D8C7A1", "#8FAE5D", "#A65D37"]
          }
        }
      });

      const handleResize = () => {
        if (app && app.resize) app.resize();
      };
      window.addEventListener('resize', handleResize);
    } catch (e) {
      console.error("Failed to load TubesCursor", e);
    }
  };

  // Intersection observer to only load and run when visible
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        if (!app) initTubes();
        // if app is already initialized, maybe we can unpause it here if it has a pause/play method.
      } else {
        // pause if we could
      }
    });
  }, { threshold: 0.1 });

  observer.observe(wrapper);

  wrapper.addEventListener('click', () => {
    if (!app) return;
    const palettes = [
      ["#173F35", "#8FAE5D", "#D8C7A1"],
      ["#A65D37", "#173F35", "#D8C7A1"],
      ["#0a1a12", "#8FAE5D", "#F6F3EC"],
    ];
    const lightsPalettes = [
      ["#F6F3EC", "#D8C7A1", "#8FAE5D", "#A65D37"],
      ["#8FAE5D", "#A65D37", "#173F35", "#F6F3EC"],
    ];
    const colors = palettes[Math.floor(Math.random() * palettes.length)];
    const lightsColors = lightsPalettes[Math.floor(Math.random() * lightsPalettes.length)];
    app.tubes.setColors(colors);
    app.tubes.setLightsColors(lightsColors);
  });
});
