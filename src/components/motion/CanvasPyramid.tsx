import { useEffect, useRef } from "react";

export function CanvasPyramid({ mouseX = 0, mouseY = 0 }: { mouseX?: number; mouseY?: number }) {
  const canvasRef = useRef<HTMLCanvasElement>(null);

  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const ctx = canvas.getContext("2d");
    if (!ctx) return;

    let w = canvas.width = canvas.offsetWidth;
    let h = canvas.height = canvas.offsetHeight;
    let animationFrameId: number;

    const draw = () => {
      ctx.clearRect(0, 0, w, h);
      
      const cx = w / 2 + mouseX * 20;
      const cy = h / 2 + mouseY * 20;
      const size = Math.min(w, h) * 0.4;

      // Draw subtle pyramid abstract lines
      ctx.strokeStyle = "rgba(143, 174, 93, 0.15)";
      ctx.lineWidth = 1;

      // Base
      ctx.beginPath();
      ctx.moveTo(cx - size, cy + size);
      ctx.lineTo(cx + size, cy + size);
      ctx.lineTo(cx + size * 0.3, cy - size * 0.2);
      ctx.closePath();
      ctx.stroke();

      // Top point
      const px = cx;
      const py = cy - size * 1.2;

      ctx.beginPath();
      ctx.moveTo(cx - size, cy + size);
      ctx.lineTo(px, py);
      ctx.lineTo(cx + size, cy + size);
      ctx.stroke();

      ctx.beginPath();
      ctx.moveTo(cx + size * 0.3, cy - size * 0.2);
      ctx.lineTo(px, py);
      ctx.stroke();

      animationFrameId = requestAnimationFrame(draw);
    };

    draw();

    const handleResize = () => {
      w = canvas.width = canvas.offsetWidth;
      h = canvas.height = canvas.offsetHeight;
    };
    window.addEventListener("resize", handleResize);

    return () => {
      window.removeEventListener("resize", handleResize);
      cancelAnimationFrame(animationFrameId);
    };
  }, [mouseX, mouseY]);

  return <canvas ref={canvasRef} className="absolute inset-0 w-full h-full pointer-events-none" />;
}
