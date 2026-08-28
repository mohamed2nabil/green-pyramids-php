import { useState, useEffect, useRef, useCallback } from "react";
import { Link } from "react-router-dom";
import { Reveal } from "../components/motion/Reveal";

/* ── Hero card data — 6 editorial panels ───────────────────── */
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

/* depth positions: index 0 = front, index 5 = hidden/exit ──── */
const CARD_POSITIONS = [
  { scale: 1.00, rotZ:  0.0, tx:  0, ty:  0, opacity: 1.00, zBase: 50 },
  { scale: 0.90, rotZ: -4.0, tx: -9, ty:  3, opacity: 0.88, zBase: 40 },
  { scale: 0.81, rotZ:  5.5, tx: 12, ty:  6, opacity: 0.68, zBase: 30 },
  { scale: 0.72, rotZ: -2.5, tx: -5, ty: 11, opacity: 0.48, zBase: 20 },
  { scale: 0.63, rotZ:  4.0, tx:  9, ty: 17, opacity: 0.28, zBase: 10 },
  { scale: 0.50, rotZ: -7.0, tx: 20, ty: 24, opacity: 0.00, zBase:  5 },
];

/* ── Static data arrays ─────────────────────────────────────── */
const CATEGORIES = [
  {
    name: "Fresh Fruits",
    desc: "Mangoes, pomegranates, strawberries from Egyptian orchards.",
    count: "12+ varieties",
    img: "https://images.unsplash.com/photo-1605027990121-cbae9e0642df?w=700&h=900&fit=crop&auto=format",
  },
  {
    name: "Fresh Vegetables",
    desc: "Premium vegetables grown in Egypt's fertile Nile Delta.",
    count: "18+ varieties",
    img: "https://images.unsplash.com/photo-1708417134108-f4d009383f44?w=700&h=600&fit=crop&auto=format",
  },
  {
    name: "Citrus",
    desc: "Sun-ripened oranges, lemons, and mandarins.",
    count: "8+ varieties",
    img: "https://images.unsplash.com/photo-1663681240509-d9a1b7871898?w=700&h=600&fit=crop&auto=format",
  },
  {
    name: "Seasonal Crops",
    desc: "Egypt's finest seasonal agricultural produce.",
    count: "Varies by season",
    img: "https://images.unsplash.com/photo-1666987571351-737b29874697?w=700&h=600&fit=crop&auto=format",
  },
];

const FEATURED_PRODUCTS = [
  { name: "Egyptian Mango", cat: "Fruits", season: "May – Sep", img: "https://images.unsplash.com/photo-1744035355878-222dc04f79f5?w=500&h=620&fit=crop&auto=format" },
  { name: "Pomegranate", cat: "Fruits", season: "Sep – Jan", img: "https://images.unsplash.com/photo-1701294878194-2aa42434e9af?w=500&h=620&fit=crop&auto=format" },
  { name: "Navel Orange", cat: "Citrus", season: "Nov – Apr", img: "https://images.unsplash.com/photo-1594143887697-fb87011a8b2a?w=500&h=620&fit=crop&auto=format" },
  { name: "Strawberry", cat: "Fruits", season: "Dec – Apr", img: "https://images.unsplash.com/photo-1601493700631-2b16ec4b4716?w=500&h=620&fit=crop&auto=format" },
  { name: "Potato", cat: "Vegetables", season: "Year-round", img: "https://images.unsplash.com/photo-1572439409920-0b7111340de3?w=500&h=620&fit=crop&auto=format" },
  { name: "White Onion", cat: "Vegetables", season: "Mar – Jul", img: "https://images.unsplash.com/photo-1720807740685-d9cdcb0836a7?w=500&h=620&fit=crop&auto=format" },
];

const TRUST_FEATURES = [
  { n: "01", title: "Carefully Selected", body: "Premium crops sourced from audited farms across Egypt’s most fertile growing regions." },
  { n: "02", title: "Quality Controlled", body: "Strict protocols at every stage — from field inspection to export-grade packing." },
  { n: "03", title: "Export Ready", body: "Professional sorting, grading, and packing meeting international market specifications." },
  { n: "04", title: "Reliable Delivery", body: "Cold-chain logistics connecting Egyptian farms to global distribution networks." },
];

const PROCESS_STEPS = [
  { n: "01", label: "Farm Selection" },
  { n: "02", label: "Harvesting" },
  { n: "03", label: "Sorting & QC" },
  { n: "04", label: "Packing" },
  { n: "05", label: "Cold Chain" },
  { n: "06", label: "Global Shipment" },
];

const MARKETS = [
  { region: "Europe", desc: "Germany, Netherlands, UK, France, Italy" },
  { region: "Gulf Region", desc: "UAE, Saudi Arabia, Qatar, Kuwait" },
  { region: "Middle East", desc: "Jordan, Lebanon, Iraq" },
  { region: "Asia", desc: "Emerging export opportunities" },
];

/* ── Animated perspective card gallery ──────────────────────── */
function HeroCardStack({ mouseX, mouseY }: { mouseX: number; mouseY: number }) {
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
      {/* Deep atmospheric base */}
      <div className="absolute inset-0 bg-[#050c0a]" />

      {/* Radial depth gradient — moves opposite to mouse */}
      <div
        className="absolute inset-[-10%] opacity-75"
        style={{
          background: "radial-gradient(ellipse 70% 65% at 55% 48%, #162e23 0%, #050c0a 70%)",
          transform: `translate3d(${mouseX * -12}px, ${mouseY * -8}px, 0)`,
          transition: "transform 0.65s ease-out",
        }}
      />

      {/* Faint pyramid geometry — moves with mouse (forward layer) */}
      <svg
        className="absolute inset-0 w-full h-full pointer-events-none"
        style={{
          opacity: 0.055,
          transform: `translate3d(${mouseX * 7}px, ${mouseY * 5}px, 0)`,
          transition: "transform 0.3s ease-out",
        }}
        viewBox="0 0 500 500"
        preserveAspectRatio="xMidYMid meet"
      >
        <polygon points="250,55 52,445 448,445" stroke="#8FAE5D" strokeWidth="1.0" fill="none" />
        <line x1="152" y1="190" x2="348" y2="190" stroke="#D8C7A1" strokeWidth="0.6" />
        <line x1="105" y1="290" x2="395" y2="290" stroke="#D8C7A1" strokeWidth="0.6" />
        <line x1="58" y1="390" x2="442" y2="390" stroke="#D8C7A1" strokeWidth="0.5" />
        <line x1="250" y1="55" x2="52" y2="445" stroke="#8FAE5D" strokeWidth="0.5" opacity="0.5" />
        <line x1="250" y1="55" x2="448" y2="445" stroke="#050c0a" strokeWidth="0.5" opacity="0.8" />
      </svg>

      {/* Card stack — centered pivot area */}
      <div className="absolute" style={{ top: "9%", bottom: "9%", left: "10%", right: "10%" }}>
        {HERO_CARDS.map((card, i) => {
          const posIdx = (i - active + N) % N;
          const cfg = CARD_POSITIONS[posIdx];
          const parallaxFactor = Math.max(0, 1 - posIdx * 0.16);
          const px = mouseX * 16 * parallaxFactor;
          const py = mouseY * 10 * parallaxFactor;
          const zIdx = cyclingOut === i ? 60 : cfg.zBase;

          return (
            /* Outer wrapper: fast parallax only */
            <div
              key={i}
              className="absolute inset-0"
              style={{
                zIndex: zIdx,
                transform: `translate3d(${px}px, ${py}px, 0)`,
                transition: "transform 0.12s linear",
              }}
            >
              {/* Inner wrapper: slow cinematic cycling */}
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
                {/* Photograph */}
                <img
                  src={card.img}
                  alt={card.type}
                  draggable={false}
                  className="absolute inset-[-6%] w-[112%] h-[112%] object-cover select-none"
                  style={{ objectPosition: "center 38%" }}
                />

                {/* Bottom atmosphere gradient */}
                <div className="absolute inset-0 bg-gradient-to-t from-[#040a08]/90 via-[#0a1a12]/30 to-transparent" />

                {/* 3D edge lighting: left highlight, right shadow */}
                <div
                  className="absolute inset-0"
                  style={{
                    background:
                      "linear-gradient(to right, rgba(143,174,93,0.11) 0%, transparent 32%, rgba(4,10,7,0.28) 100%)",
                  }}
                />

                {/* Top edge vignette */}
                <div className="absolute inset-0 bg-gradient-to-b from-[#040a08]/30 to-transparent" style={{ height: "30%" }} />

                {/* Front card info — only visible on active card */}
                {posIdx === 0 && (
                  <div
                    className="absolute bottom-0 left-0 right-0 p-6"
                    style={{ animation: "fadeUp 0.6s ease-out both" }}
                  >
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

      {/* ── UI overlays: always above card stack ─────────────── */}

      {/* Card counter — top right */}
      <div className="absolute top-7 right-7 z-[70]">
        <p className="font-serif text-[12px] text-[#D8C7A1]/38 tabular-nums tracking-widest">
          {String(active + 1).padStart(2, "0")}&nbsp;/&nbsp;{String(N).padStart(2, "0")}
        </p>
      </div>

      {/* Origin label — bottom left */}
      <div className="absolute bottom-[52px] left-7 z-[70]">
        <p className="text-[9px] tracking-[0.28em] uppercase text-[#D8C7A1]/38">Nile Delta · Egypt</p>
      </div>

      {/* Progress dots — bottom center */}
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

      {/* Premium origin badge — top left */}
      <div className="absolute top-7 left-7 z-[70] flex items-center gap-2">
        <div className="w-1.5 h-1.5 rounded-full bg-[#8FAE5D]" />
        <p className="text-[9px] tracking-[0.22em] uppercase text-[#D8C7A1]/48">Premium Origin</p>
      </div>
    </div>
  );
}

/* ── Page component ─────────────────────────────────────────── */
export default function HomePage() {
  const [mouse, setMouse] = useState({ x: 0, y: 0 });
  const sectionRef = useRef<HTMLElement>(null);

  useEffect(() => {
    const el = sectionRef.current;
    if (!el) return;
    const onMove = (e: MouseEvent) => {
      const r = el.getBoundingClientRect();
      setMouse({
        x: (e.clientX - r.left - r.width / 2) / (r.width / 2),
        y: (e.clientY - r.top - r.height / 2) / (r.height / 2),
      });
    };
    const onLeave = () => setMouse({ x: 0, y: 0 });
    el.addEventListener("mousemove", onMove);
    el.addEventListener("mouseleave", onLeave);
    return () => {
      el.removeEventListener("mousemove", onMove);
      el.removeEventListener("mouseleave", onLeave);
    };
  }, []);

  return (
    <div className="bg-[#F6F3EC]">

      {/* ── HERO — editorial split with animated card gallery ─── */}
      <section ref={sectionRef} className="flex flex-col lg:flex-row lg:h-screen">

        {/* Left: typographic editorial column */}
        <div className="lg:w-1/2 relative flex flex-col justify-center bg-[#F6F3EC] overflow-hidden px-8 sm:px-12 lg:px-16 xl:px-20 pt-28 pb-14 lg:py-0">

          {/* Subtle pyramid watermark ghost */}
          <div className="absolute -bottom-10 -left-10 pointer-events-none opacity-[0.035] hidden lg:block" aria-hidden>
            <svg width="380" height="380" viewBox="0 0 380 380" fill="none">
              <polygon points="190,24 18,356 362,356" stroke="#173F35" strokeWidth="2" />
              <line x1="129" y1="134" x2="251" y2="134" stroke="#173F35" strokeWidth="1" />
              <line x1="96" y1="200" x2="284" y2="200" stroke="#173F35" strokeWidth="1" />
              <line x1="56" y1="270" x2="324" y2="270" stroke="#173F35" strokeWidth="1" />
            </svg>
          </div>

          {/* Content — subtle multi-layer parallax */}
          <div
            className="relative z-10 flex flex-col"
            style={{
              transform: `translate3d(${mouse.x * 4}px, ${mouse.y * 2.5}px, 0)`,
              transition: "transform 0.35s ease-out",
            }}
          >
            {/* Brand accent line */}
            <div className="flex items-center gap-3 mb-8">
              <div className="w-6 h-px bg-[#8FAE5D]" />
              <p className="text-[11px] tracking-[0.3em] uppercase text-[#8FAE5D]">Egyptian Agricultural Exports</p>
            </div>

            <Reveal as="h1" type="letter" className="font-serif text-[52px] sm:text-[60px] lg:text-[66px] xl:text-[76px] text-[#173F35] leading-[1.02] mb-8 max-w-[480px]">
              From Egyptian Soil To Global Markets.
            </Reveal>

            <p className="text-[#173F35]/58 text-base lg:text-[17px] max-w-[340px] leading-relaxed mb-11">
              Premium agricultural crops sourced, packed, and delivered with uncompromising quality.
            </p>

            <div className="flex flex-col sm:flex-row gap-3">
              <Link
                to="/products"
                className="group inline-flex items-center justify-center gap-2 px-7 py-3.5 bg-[#173F35] text-[#F6F3EC] text-[13px] font-medium tracking-wide rounded-full hover:bg-[#1a473b] hover:shadow-lg transition-all duration-300"
              >
                Explore Products
                <span className="text-[#8FAE5D] group-hover:translate-x-1 transition-transform">→</span>
              </Link>
              <Link
                to="/contact"
                className="inline-flex items-center justify-center px-7 py-3.5 border border-[#173F35]/25 text-[#173F35] text-[13px] font-medium tracking-wide rounded-full hover:border-[#173F35]/80 hover:bg-[#173F35]/5 transition-all duration-300"
              >
                Request a Quote
              </Link>
            </div>

            {/* Stat strip */}
            <div className="flex items-center gap-6 mt-14 pt-8 border-t border-[#D8C7A1]/50">
              {[["15+", "Years exporting"], ["40+", "Global markets"], ["200+", "Farm partners"]].map(([num, label]) => (
                <div key={num} className="flex flex-col">
                  <span className="font-serif text-xl text-[#173F35]">{num}</span>
                  <span className="text-[10px] tracking-[0.12em] uppercase text-[#173F35]/45 mt-0.5">{label}</span>
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* Right: animated perspective card gallery */}
        <div className="lg:w-1/2 relative h-[82vw] sm:h-[55vw] lg:h-full bg-[#050c0a] overflow-hidden">
          <HeroCardStack mouseX={mouse.x} mouseY={mouse.y} />
        </div>
      </section>

      {/* ── TRUST STRIP ───────────────────────────────────────── */}
      <div className="bg-[#173F35]">
        <div className="max-w-7xl mx-auto px-6 lg:px-10 grid grid-cols-2 lg:grid-cols-4 divide-x divide-[#F6F3EC]/10">
          {[
            { label: "Egyptian Origin", sub: "Nile Delta farms" },
            { label: "Fresh Produce", sub: "Harvested to order" },
            { label: "Quality Controlled", sub: "Export-grade certified" },
            { label: "Export Ready", sub: "Cold chain managed" },
          ].map((t) => (
            <div key={t.label} className="px-5 py-5 first:pl-0 last:pr-0">
              <div className="flex items-center gap-2 mb-1">
                <div className="w-1 h-1 rounded-full bg-[#8FAE5D] flex-shrink-0" />
                <span className="text-[11px] tracking-[0.18em] uppercase text-[#F6F3EC]/80">{t.label}</span>
              </div>
              <p className="text-[10px] text-[#F6F3EC]/35 ml-3 tracking-wide">{t.sub}</p>
            </div>
          ))}
        </div>
      </div>

      {/* ── ABOUT INTRO ───────────────────────────────────────── */}
      <section className="py-24 lg:py-36 max-w-7xl mx-auto px-6 lg:px-10">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
          <div className="relative">
            <img
              src="https://images.unsplash.com/photo-1649192537902-7b06265dd08f?w=900&h=1100&fit=crop&auto=format"
              alt="Egyptian farmer in the field"
              className="w-full aspect-[3/4] object-cover rounded-2xl"
            />
            <div className="absolute -bottom-5 -right-5 hidden lg:block bg-[#F6F3EC] border border-[#D8C7A1]/60 rounded-xl px-5 py-4 shadow-sm">
              <div className="flex items-center gap-3">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden>
                  <polygon points="12,2 2,21 12,21" fill="#1f5245" />
                  <polygon points="12,2 22,21 12,21" fill="#0d2a24" />
                  <polygon points="12,2 2,21 22,21" fill="none" stroke="#8FAE5D" strokeWidth="0.9" />
                </svg>
                <div>
                  <p className="text-[10px] tracking-[0.15em] uppercase text-[#173F35]/40">Founded</p>
                  <p className="font-serif text-2xl text-[#173F35] leading-none">2010</p>
                </div>
              </div>
            </div>
          </div>
          <div>
            <div className="flex items-center gap-3 mb-5">
              <div className="w-5 h-px bg-[#8FAE5D]" />
              <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Who We Are</p>
            </div>
            <Reveal as="h2" type="word" className="font-serif text-4xl lg:text-5xl text-[#173F35] leading-[1.08] mb-8">
              Growing Quality. Delivering Trust.
            </Reveal>
            <p className="text-[#173F35]/65 leading-relaxed mb-5 text-[15px]">
              Green Pyramids is an Egyptian agricultural export company connecting the world to Egypt&apos;s finest fresh produce. We partner with trusted farms across the Nile Delta and Upper Egypt, applying strict quality standards from field to final delivery.
            </p>
            <p className="text-[#173F35]/65 leading-relaxed mb-10 text-[15px]">
              Our clients include importers, distributors, wholesalers, and supermarket chains across Europe, the Gulf, the Middle East, and Asia.
            </p>
            <Link to="/about" className="inline-flex items-center gap-2 text-[#173F35] text-sm font-medium hover:text-[#8FAE5D] transition-colors group">
              Discover Green Pyramids
              <span className="transition-transform group-hover:translate-x-1.5">→</span>
            </Link>
          </div>
        </div>
      </section>

      {/* ── PRODUCT CATEGORIES — asymmetric bento grid ────────── */}
      <section className="py-24 lg:py-36 bg-[#173F35]">
        <div className="max-w-7xl mx-auto px-6 lg:px-10">
          <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12">
            <div>
              <div className="flex items-center gap-3 mb-4">
                <div className="w-5 h-px bg-[#8FAE5D]" />
                <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">What We Export</p>
              </div>
              <Reveal as="h2" type="word" className="font-serif text-4xl lg:text-5xl text-[#F6F3EC] leading-[1.08]">Our Products</Reveal>
            </div>
            <Link to="/products" className="text-[13px] text-[#D8C7A1]/70 hover:text-[#D8C7A1] transition-colors flex items-center gap-2 group">
              View All Products
              <span className="transition-transform group-hover:translate-x-1">→</span>
            </Link>
          </div>

          <div className="grid grid-cols-2 lg:grid-cols-4 lg:grid-rows-2 gap-3 lg:gap-3 lg:h-[600px]">
            <Link
              to="/products"
              className="group relative overflow-hidden rounded-xl bg-[#0d2a24] col-span-1 row-span-1 lg:row-span-2 aspect-[3/4] lg:aspect-auto block"
            >
              <img
                src={CATEGORIES[0].img}
                alt={CATEGORIES[0].name}
                className="absolute inset-0 w-full h-full object-cover opacity-70 group-hover:opacity-85 group-hover:scale-103 transition-all duration-500"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-[#0d2a24]/95 via-[#0d2a24]/15 to-transparent" />
              <div className="absolute inset-0 p-5 lg:p-7 flex flex-col justify-end">
                <p className="text-[10px] text-[#8FAE5D] tracking-[0.18em] uppercase mb-2">{CATEGORIES[0].count}</p>
                <h3 className="font-serif text-xl lg:text-2xl text-[#F6F3EC] mb-2">{CATEGORIES[0].name}</h3>
                <p className="text-xs lg:text-sm text-[#F6F3EC]/55 mb-4 leading-relaxed hidden lg:block">{CATEGORIES[0].desc}</p>
                <span className="text-[#D8C7A1] text-xs lg:text-sm flex items-center gap-1 group-hover:gap-3 transition-all">
                  Explore <span>→</span>
                </span>
              </div>
            </Link>

            {CATEGORIES.slice(1).map((cat) => (
              <Link
                key={cat.name}
                to="/products"
                className="group relative overflow-hidden rounded-xl bg-[#0d2a24] block aspect-[4/3] lg:aspect-auto"
              >
                <img
                  src={cat.img}
                  alt={cat.name}
                  className="absolute inset-0 w-full h-full object-cover opacity-65 group-hover:opacity-80 group-hover:scale-103 transition-all duration-500"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-[#0d2a24]/90 via-transparent to-transparent" />
                <div className="absolute inset-0 p-4 lg:p-5 flex flex-col justify-end">
                  <p className="text-[10px] text-[#8FAE5D] tracking-[0.15em] uppercase mb-1">{cat.count}</p>
                  <h3 className="font-serif text-base lg:text-lg text-[#F6F3EC] mb-3">{cat.name}</h3>
                  <span className="text-[#D8C7A1] text-xs flex items-center gap-1 group-hover:gap-2 transition-all opacity-70 group-hover:opacity-100">
                    Explore <span>→</span>
                  </span>
                </div>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* ── FEATURED PRODUCTS ─────────────────────────────────── */}
      <section className="py-24 lg:py-36 max-w-7xl mx-auto px-6 lg:px-10">
        <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12">
          <div>
            <div className="flex items-center gap-3 mb-4">
              <div className="w-5 h-px bg-[#8FAE5D]" />
              <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Selected Produce</p>
            </div>
            <Reveal as="h2" type="word" className="font-serif text-4xl lg:text-5xl text-[#173F35] leading-[1.08]">Featured Products</Reveal>
          </div>
          <Link to="/products" className="text-[13px] text-[#173F35]/55 hover:text-[#173F35] transition-colors flex items-center gap-2 group">
            Full Catalog <span className="transition-transform group-hover:translate-x-1">→</span>
          </Link>
        </div>

        <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
          {FEATURED_PRODUCTS.map((p, i) => (
            <Link
              key={p.name}
              to={`/products/${p.name.toLowerCase().replace(/\s+/g, "-")}`}
              className="group"
            >
              <div
                className={`relative overflow-hidden rounded-xl bg-[#D8C7A1]/20 mb-3.5 ${
                  i === 1 ? "aspect-[3/5]" : "aspect-[3/4]"
                }`}
              >
                <img
                  src={p.img}
                  alt={p.name}
                  className="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
                <div className="absolute top-2.5 left-2.5">
                  <span className="text-[9px] tracking-[0.15em] uppercase bg-[#F6F3EC]/90 text-[#173F35] px-2 py-0.5 rounded-full">
                    {p.cat}
                  </span>
                </div>
              </div>
              <h4 className="font-medium text-[#173F35] text-[13px] mb-0.5 leading-snug">{p.name}</h4>
              <p className="text-[11px] text-[#173F35]/45">{p.season}</p>
            </Link>
          ))}
        </div>
      </section>

      {/* ── WHY GREEN PYRAMIDS ────────────────────────────────── */}
      <section className="py-24 lg:py-36 border-t border-[#D8C7A1]/35">
        <div className="max-w-7xl mx-auto px-6 lg:px-10">
          <div className="max-w-lg mb-16">
            <div className="flex items-center gap-3 mb-4">
              <div className="w-5 h-px bg-[#8FAE5D]" />
              <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Our Commitment</p>
            </div>
            <h2 className="font-serif text-4xl lg:text-5xl text-[#173F35] leading-[1.08]">
              Why Green Pyramids?
            </h2>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12">
            {TRUST_FEATURES.map((f) => (
              <div key={f.n}>
                <div className="font-serif text-[56px] text-[#D8C7A1]/80 mb-5 leading-none">{f.n}</div>
                <h3 className="font-serif text-[19px] text-[#173F35] mb-3">{f.title}</h3>
                <div className="w-8 h-px bg-[#8FAE5D]/50 mb-3" />
                <p className="text-[13px] text-[#173F35]/58 leading-relaxed">{f.body}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ── EXPORT JOURNEY ────────────────────────────────────── */}
      <section className="py-24 lg:py-36 bg-[#173F35] overflow-hidden">
        <div className="max-w-7xl mx-auto px-6 lg:px-10">
          <div className="mb-14">
            <div className="flex items-center gap-3 mb-4">
              <div className="w-5 h-px bg-[#8FAE5D]" />
              <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Supply Chain</p>
            </div>
            <h2 className="font-serif text-4xl lg:text-5xl text-[#F6F3EC] leading-[1.08]">Our Export Journey</h2>
          </div>

          <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-8 relative">
            <div className="hidden lg:block absolute top-7 left-0 right-0 h-px bg-[#F6F3EC]/8 z-0" />
            {PROCESS_STEPS.map((step) => (
              <div key={step.n} className="relative z-10">
                <div className="w-14 h-14 rounded-full border border-[#F6F3EC]/18 flex items-center justify-center mb-4 bg-[#173F35]">
                  <span className="font-serif text-base text-[#D8C7A1]">{step.n}</span>
                </div>
                <div className="w-5 h-px bg-[#8FAE5D]/40 mb-3" />
                <h4 className="text-[12px] text-[#F6F3EC]/75 font-medium leading-snug">{step.label}</h4>
              </div>
            ))}
          </div>

          <div className="mt-14 pt-8 border-t border-[#F6F3EC]/8">
            <Link to="/process" className="inline-flex items-center gap-2 text-[13px] text-[#D8C7A1]/60 hover:text-[#D8C7A1] transition-colors group">
              Learn About Our Process
              <span className="transition-transform group-hover:translate-x-1">→</span>
            </Link>
          </div>
        </div>
      </section>

      {/* ── EGYPTIAN ORIGIN ───────────────────────────────────── */}
      <section className="relative py-36 lg:py-52 overflow-hidden">
        <div className="absolute inset-0 bg-[#173F35]">
          <img
            src="https://images.unsplash.com/photo-1666987571450-29a997016a96?w=1800&h=900&fit=crop&auto=format"
            alt="Egyptian agricultural landscape"
            className="absolute inset-0 w-full h-full object-cover opacity-38 mix-blend-multiply"
          />
          <div className="absolute inset-0 bg-gradient-to-r from-[#0d2a24]/85 via-[#0d2a24]/50 to-transparent" />
        </div>
        <div className="relative max-w-7xl mx-auto px-6 lg:px-10 grid grid-cols-1 lg:grid-cols-2 gap-16">
          <div>
            <div className="flex items-center gap-3 mb-6">
              <div className="w-5 h-px bg-[#8FAE5D]" />
              <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">The Origin</p>
            </div>
            <h2 className="font-serif text-5xl lg:text-6xl text-[#F6F3EC] leading-[1.04] mb-8">
              Proudly Grown<br /><em>in Egypt.</em>
            </h2>
            <p className="text-[#F6F3EC]/65 leading-relaxed mb-9 max-w-md text-[15px]">
              Egypt&apos;s unique geography creates ideal conditions for year-round agricultural production &mdash; from the fertile Nile Delta to the rich soils of Upper Egypt.
            </p>
            <ul className="space-y-4">
              {[
                "Fertile Nile silt and alluvial soils",
                "Favorable Mediterranean and desert climate",
                "Extended growing seasons throughout the year",
                "Strategic location between Europe, Africa, and Asia",
              ].map((item) => (
                <li key={item} className="flex items-start gap-3">
                  <div className="w-4 h-4 rounded-full border border-[#8FAE5D]/60 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <div className="w-1.5 h-1.5 rounded-full bg-[#8FAE5D]" />
                  </div>
                  <span className="text-[13px] text-[#F6F3EC]/75">{item}</span>
                </li>
              ))}
            </ul>
          </div>
        </div>
      </section>

      {/* ── GLOBAL MARKETS ────────────────────────────────────── */}
      <section className="py-24 lg:py-36 max-w-7xl mx-auto px-6 lg:px-10">
        <div className="text-center mb-14">
          <div className="flex items-center justify-center gap-3 mb-4">
            <div className="w-5 h-px bg-[#8FAE5D]" />
            <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Our Reach</p>
            <div className="w-5 h-px bg-[#8FAE5D]" />
          </div>
          <h2 className="font-serif text-4xl lg:text-5xl text-[#173F35] leading-[1.08]">
            Connecting Egypt to Global Markets
          </h2>
        </div>

        <div className="rounded-2xl bg-[#173F35] overflow-hidden p-8 lg:p-12 mb-10">
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {MARKETS.map((m) => (
              <div key={m.region} className="p-5 rounded-xl border border-[#F6F3EC]/8 bg-[#F6F3EC]/4 hover:bg-[#F6F3EC]/9 transition-colors">
                <div className="flex items-center gap-2 mb-2.5">
                  <div className="w-1.5 h-1.5 rounded-full bg-[#8FAE5D]" />
                  <h4 className="font-serif text-base text-[#F6F3EC]">{m.region}</h4>
                </div>
                <p className="text-[11px] text-[#F6F3EC]/45 leading-relaxed">{m.desc}</p>
              </div>
            ))}
          </div>
          <div className="mt-8 pt-6 border-t border-[#F6F3EC]/8 flex items-center gap-4">
            <div className="w-1.5 h-1.5 rounded-full bg-[#8FAE5D]" />
            <p className="text-[12px] text-[#F6F3EC]/50">
              Egypt &mdash; strategically located at the crossroads of Europe, Africa, and Asia
            </p>
          </div>
        </div>

        <div className="flex justify-center">
          <Link to="/contact" className="inline-flex items-center gap-2 text-[13px] text-[#173F35]/50 hover:text-[#173F35] transition-colors group">
            Become a Partner in Your Region
            <span className="transition-transform group-hover:translate-x-1">→</span>
          </Link>
        </div>
      </section>

      {/* ── FINAL CTA ─────────────────────────────────────────── */}
      <section className="bg-[#173F35] py-24 lg:py-36">
        <div className="max-w-7xl mx-auto px-6 lg:px-10 text-center">
          <div className="flex items-center justify-center gap-3 mb-6">
            <div className="w-5 h-px bg-[#8FAE5D]" />
            <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Partner With Us</p>
            <div className="w-5 h-px bg-[#8FAE5D]" />
          </div>
          <h2 className="font-serif text-4xl lg:text-[58px] text-[#F6F3EC] leading-[1.07] max-w-3xl mx-auto mb-8">
            Looking for a Reliable Egyptian Export Partner?
          </h2>
          <p className="text-[#F6F3EC]/55 max-w-md mx-auto mb-12 leading-relaxed text-[15px]">
            Tell us what products you need and our export team will help you find the right solution.
          </p>
          <div className="flex flex-col sm:flex-row justify-center gap-4">
            <Link
              to="/contact"
              className="px-9 py-4 bg-[#8FAE5D] text-[#173F35] font-medium tracking-wide rounded-full hover:bg-[#F6F3EC] transition-colors duration-200 text-[13px]"
            >
              Request Your Quote
            </Link>
            <Link
              to="/contact"
              className="px-9 py-4 border border-[#F6F3EC]/25 text-[#F6F3EC] font-medium tracking-wide rounded-full hover:bg-[#F6F3EC]/8 transition-colors duration-200 text-[13px]"
            >
              Contact Our Team
            </Link>
          </div>
        </div>
      </section>
    </div>
  );
}
