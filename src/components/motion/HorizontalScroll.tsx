import { useRef, useEffect, useState } from "react";

export function HorizontalScroll({ children }: { children: React.ReactNode }) {
  const containerRef = useRef<HTMLDivElement>(null);
  const trackRef = useRef<HTMLDivElement>(null);
  const [x, setX] = useState(0);

  useEffect(() => {
    const handleScroll = () => {
      if (!containerRef.current || !trackRef.current) return;
      const rect = containerRef.current.getBoundingClientRect();
      const trackWidth = trackRef.current.scrollWidth;
      const windowWidth = window.innerWidth;
      
      const scrollableDistanceY = rect.height - window.innerHeight;
      const maxTranslateX = trackWidth - windowWidth + (windowWidth * 0.2); // padding
      
      if (rect.top <= 0 && rect.bottom >= window.innerHeight) {
        const progress = Math.abs(rect.top) / scrollableDistanceY;
        setX(progress * maxTranslateX);
      } else if (rect.top > 0) {
        setX(0);
      } else {
        setX(maxTranslateX);
      }
    };
    window.addEventListener("scroll", handleScroll, { passive: true });
    handleScroll();
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  return (
    <div ref={containerRef} className="relative h-[300vh] bg-[#F6F3EC]">
      <div className="sticky top-0 h-screen overflow-hidden flex items-center">
        <div
          ref={trackRef}
          className="flex gap-16 px-[10vw] will-change-transform"
          style={{ 
            transform: `translate3d(-${x}px, 0, 0)`,
            width: 'max-content',
            transition: 'transform 0.1s ease-out'
          }}
        >
          {children}
        </div>
      </div>
    </div>
  );
}
