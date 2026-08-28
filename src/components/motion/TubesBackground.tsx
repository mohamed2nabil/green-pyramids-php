import React, { useEffect, useRef, useState } from 'react';

// Helper for random colors
const randomColors = (count: number) => {
  return new Array(count)
    .fill(0)
    .map(() => "#" + Math.floor(Math.random() * 16777215).toString(16).padStart(6, '0'));
};

interface TubesBackgroundProps {
  children?: React.ReactNode;
  className?: string;
  enableClickInteraction?: boolean;
}

export function TubesBackground({ 
  children, 
  className = "",
  enableClickInteraction = true 
}: TubesBackgroundProps) {
  const canvasRef = useRef<HTMLCanvasElement>(null);
  const [isLoaded, setIsLoaded] = useState(false);
  const tubesRef = useRef<any>(null);

  useEffect(() => {
    let mounted = true;
    let cleanup: (() => void) | undefined;

    const initTubes = async () => {
      if (!canvasRef.current) return;

      try {
        // We use the specific build from the CDN as it contains the exact effect requested
        // Using native dynamic import which works in modern browsers
        // @ts-ignore
        const module = await import('https://cdn.jsdelivr.net/npm/threejs-components@0.0.19/build/cursors/tubes1.min.js');
        const TubesCursor = module.default;

        if (!mounted) return;

        const app = TubesCursor(canvasRef.current, {
          tubes: {
            // Colors adapted for Green Pyramids: Deep green, bright green, gold/sand
            colors: ["#173F35", "#8FAE5D", "#D8C7A1"],
            lights: {
              intensity: 150,
              // Lights adapted: White, gold, sage, terracotta
              colors: ["#F6F3EC", "#D8C7A1", "#8FAE5D", "#A65D37"]
            }
          }
        });

        tubesRef.current = app;
        setIsLoaded(true);

        const handleResize = () => {
          if (app.resize) app.resize();
        };

        window.addEventListener('resize', handleResize);
        
        cleanup = () => {
          window.removeEventListener('resize', handleResize);
          if (app.destroy) app.destroy();
        };

      } catch (error) {
        console.error("Failed to load TubesCursor:", error);
      }
    };

    initTubes();

    return () => {
      mounted = false;
      if (cleanup) cleanup();
    };
  }, []);

  const handleClick = () => {
    if (!enableClickInteraction || !tubesRef.current) return;
    
    // Instead of random neon colors, we'll keep cycling through Egyptian/Agri palettes
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
    
    tubesRef.current.tubes.setColors(colors);
    tubesRef.current.tubes.setLightsColors(lightsColors);
  };

  return (
    <div 
      className={`relative w-full h-full min-h-[400px] overflow-hidden bg-[#050c0a] ${className}`}
      onClick={handleClick}
    >
      <canvas 
        ref={canvasRef} 
        className="absolute inset-0 w-full h-full block"
        style={{ touchAction: 'none' }}
      />
      
      {/* Content Overlay */}
      <div className="relative z-10 w-full h-full pointer-events-none">
        {children}
      </div>
    </div>
  );
}

export default TubesBackground;
