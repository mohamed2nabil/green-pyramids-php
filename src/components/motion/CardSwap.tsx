import { useState, useEffect, useRef, useCallback } from "react";

const HERO_CARDS = [
  {
    img: "https://images.unsplash.com/photo-1666987571351-737b29874697?w=900&h=1200&fit=crop&auto=format",
    location: "Nile Delta", type: "Seasonal Crops", tag: "Export Ready",
  },
  {
    img: "https://images.unsplash.com/photo-1744035355878-222dc04f79f5?w=900&h=1200&fit=crop&auto=format",
    location: "Upper Egypt", type: "Tropical Fruit", tag: "Premium Grade",
  },
  {
    img: "https://images.unsplash.com/photo-1701294878194-2aa42434e9af?w=900&h=1200&fit=crop&auto=format",
    location: "Nile Delta", type: "Fresh Fruits", tag: "Winter Harvest",
  },
  {
    img: "https://images.unsplash.com/photo-1649192537902-7b06265dd08f?w=900&h=1200&fit=crop&auto=format",
    location: "Fayoum", type: "Field Crops", tag: "Direct Farm",
  },
  {
    img: "https://images.unsplash.com/photo-1708417134108-f4d009383f44?w=900&h=1200&fit=crop&auto=format",
    location: "Delta Region", type: "Fresh Vegetables", tag: "Year-round",
  },
  {
    img: "https://images.unsplash.com/photo-1594143887697-fb87011a8b2a?w=900&h=1200&fit=crop&auto=format",
    location: "Upper Egypt", type: "Citrus", tag: "Nov – Apr",
  },
];

const CARD_POSITIONS = [
  { scale: 1.00, rotZ:  0.0, tx:  0, ty:  0, opacity: 1.00, zBase: 50 },
  { scale: 0.90, rotZ: -4.0, tx: -9, ty:  3, opacity: 0.88, zBase: 40 },
  { scale: 0.81, rotZ:  5.5, tx: 12, ty:  6, opacity: 0.68, zBase: 30 },
  { scale: 0.72, rotZ: -2.5, tx: -5, ty: 11, opacity: 0.48, zBase: 20 },
  { scale: 0.63, rotZ:  4.0, tx:  9, ty: 17, opacity: 0.28, zBase: 10 },
  { scale: 0.50, rotZ: -7.0, tx: 20, ty: 24, opacity: 0.00, zBase:  5 },
];

export function CardSwap({ mouseX = 0, mouseY = 0 }: { mouseX?: number; mouseY?: number }) {
  const [active, setActive] = useState(0);
  const [cyclingOut, setCyclingOut] = useState<number | null>(null);
  const [paused, setPaused] = useState(false);
  const cyclingTimerRef = useRef<ReturnType<typeof setTimeout> | null>(null);
  const N = HERO_CARDS.length;

  const advance = useCallback(() => {
    setActive((prev) => {
      setCyclingOut(prev);
      if (cyclingTimerRef.current) clearTimeout(cyclingTimerRef.current);
      cyclingTimerRef.current = setTimeout(() => setCyclingOut(null), 1450);
      return (prev + 1) % N;
    });
  }, [N]);

  const goTo = useCallback((idx: number) => {
    setActive((prev) => {
      if (prev === idx) return prev;
      setCyclingOut(prev);
      if (cyclingTimerRef.current) clearTimeout(cyclingTimerRef.current);
      cyclingTimerRef.current = setTimeout(() => setCyclingOut(null), 1450);
      return idx;
    });
  }, []);

  useEffect(() => {
    const id = setInterval(advance, paused ? 9000 : 4200);
    return () => clearInterval(id);
  }, [paused, advance]);

  useEffect(() => () => {
    if (cyclingTimerRef.current) clearTimeout(cyclingTimerRef.current);
  }, []);

  return (
    <div
      className="absolute inset-0 overflow-hidden cursor-default"
      onMouseEnter={() => setPaused(true)}
      onMouseLeave={() => setPaused(false)}
    >
      <div className="absolute inset-0 bg-[#050c0a]" />
      <div
        className="absolute inset-[-10%] opacity-75"
        style={{
          background: "radial-gradient(ellipse 70% 65% at 55% 48%, #162e23 0%, #050c0a 70%)",
          transform: `translate3d(${mouseX * -12}px, ${mouseY * -8}px, 0)`,
          transition: "transform 0.65s ease-out",
        }}
      />
      <div className="absolute" style={{ top: "9%", bottom: "9%", left: "10%", right: "10%" }}>
        {HERO_CARDS.map((card, i) => {
          const posIdx = (i - active + N) % N;
          const cfg = CARD_POSITIONS[posIdx];
          const parallaxFactor = Math.max(0, 1 - posIdx * 0.16);
          const px = mouseX * 16 * parallaxFactor;
          const py = mouseY * 10 * parallaxFactor;
          const zIdx = cyclingOut === i ? 60 : cfg.zBase;

          return (
            <div
              key={i}
              className="absolute inset-0"
              style={{
                zIndex: zIdx,
                transform: `translate3d(${px}px, ${py}px, 0)`,
                transition: "transform 0.12s linear",
              }}
            >
              <div
                style={{
                  position: "absolute",
                  inset: 0,
                  transform: `scale(${cfg.scale}) rotateZ(${cfg.rotZ}deg) translate(${cfg.tx}%, ${cfg.ty}%)`,
                  opacity: cfg.opacity,
                  transition: "transform 1.15s cubic-bezier(0.76,0,0.24,1), opacity 1.15s cubic-bezier(0.76,0,0.24,1)",
                  willChange: "transform, opacity",
                  borderRadius: "11px",
                  overflow: "hidden",
                  boxShadow:
                    posIdx === 0
                      ? "0 50px 130px -22px rgba(0,0,0,0.80), 0 0 0 1px rgba(255,255,255,0.055)"
                      : `0 ${26 - posIdx * 4}px ${72 - posIdx * 11}px -14px rgba(0,0,0,${0.52 - posIdx * 0.08})`,
                }}
              >
                <img
                  src={card.img}
                  alt={card.type}
                  draggable={false}
                  className="absolute inset-[-6%] w-[112%] h-[112%] object-cover select-none"
                  style={{ objectPosition: "center 38%" }}
                />
                <div className="absolute inset-0 bg-gradient-to-t from-[#040a08]/90 via-[#0a1a12]/30 to-transparent" />
                <div
                  className="absolute inset-0"
                  style={{
                    background:
                      "linear-gradient(to right, rgba(143,174,93,0.11) 0%, transparent 32%, rgba(4,10,7,0.28) 100%)",
                  }}
                />
                {posIdx === 0 && (
                  <div className="absolute bottom-0 left-0 right-0 p-6" style={{ animation: "fadeUp 0.6s ease-out both" }}>
                    <div className="flex items-center gap-2 mb-2">
                      <div className="w-1.5 h-1.5 rounded-full bg-[#8FAE5D]" />
                      <p className="text-[10px] tracking-[0.24em] uppercase text-[#D8C7A1]/72">{card.location}</p>
                    </div>
                    <p className="font-serif text-[21px] text-[#F6F3EC] leading-tight mb-3">{card.type}</p>
                    <span
                      className="text-[9px] tracking-[0.18em] uppercase px-2.5 py-1 rounded-full"
                      style={{
                        background: "rgba(246,243,236,0.09)",
                        color: "rgba(216,199,161,0.78)",
                        backdropFilter: "blur(8px)",
                        WebkitBackdropFilter: "blur(8px)",
                        border: "1px solid rgba(216,199,161,0.12)",
                      }}
                    >
                      {card.tag}
                    </span>
                  </div>
                )}
              </div>
            </div>
          );
        })}
      </div>
      <div className="absolute bottom-5 left-0 right-0 z-[70] flex justify-center items-center gap-[7px]">
        {HERO_CARDS.map((_, i) => (
          <button
            key={i}
            onClick={() => goTo(i)}
            aria-label={`Go to slide ${i + 1}`}
            style={{
              width: i === active ? 20 : 5,
              height: 2,
              borderRadius: 1,
              background: i === active ? "#8FAE5D" : "rgba(216,199,161,0.25)",
              transition: "width 0.45s cubic-bezier(0.76,0,0.24,1), background 0.35s ease",
              cursor: "pointer",
              border: "none",
              padding: 0,
              flexShrink: 0,
            }}
          />
        ))}
      </div>
    </div>
  );
}
