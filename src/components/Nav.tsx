import { useState, useEffect } from "react";
import { Link, useLocation } from "react-router-dom";

const links = [
  { label: "Home", to: "/" },
  { label: "About Us", to: "/about" },
  { label: "Products", to: "/products" },
  { label: "Our Process", to: "/process" },
  { label: "Quality", to: "/quality" },
  { label: "Contact", to: "/contact" },
];

function PyramidMark({ size = 32 }: { size?: number }) {
  return (
    <svg width={size} height={size} viewBox="0 0 24 24" fill="none" aria-hidden>
      <polygon points="12,2 2,21 12,21" fill="#1f5245" />
      <polygon points="12,2 22,21 12,21" fill="#0d2a24" />
      <line x1="12" y1="2" x2="12" y2="21" stroke="#8FAE5D" strokeWidth="0.7" opacity="0.5" />
      <polygon points="12,2 2,21 22,21" fill="none" stroke="#8FAE5D" strokeWidth="0.9" strokeLinejoin="round" />
      <line x1="7" y1="11.5" x2="17" y2="11.5" stroke="#D8C7A1" strokeWidth="0.5" opacity="0.45" />
      <line x1="4.5" y1="16.5" x2="19.5" y2="16.5" stroke="#D8C7A1" strokeWidth="0.5" opacity="0.35" />
    </svg>
  );
}

export default function Nav() {
  const [menuOpen, setMenuOpen] = useState(false);
  const [scrollPercent, setScrollPercent] = useState(0);
  const location = useLocation();

  useEffect(() => {
    setMenuOpen(false);
  }, [location]);

  useEffect(() => {
    const handleScroll = () => {
      const h = document.documentElement.scrollHeight - window.innerHeight;
      setScrollPercent(h > 0 ? window.scrollY / h : 0);
    };
    window.addEventListener("scroll", handleScroll, { passive: true });
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  return (
    <>
      {/* ── Desktop Editorial Rail (Left Side) ── */}
      <aside
        className={`hidden lg:flex fixed top-0 left-0 bottom-0 z-50 bg-[#F6F3EC] border-r border-[#D8C7A1]/40 flex-col transition-all duration-500 ease-[cubic-bezier(0.76,0,0.24,1)] ${
          menuOpen ? "w-[320px]" : "w-[80px]"
        }`}
        onMouseLeave={() => setMenuOpen(false)}
      >
        {/* Top: Logo & Trigger */}
        <div className="h-[120px] flex flex-col items-center justify-center border-b border-[#D8C7A1]/20 w-full relative">
          <Link to="/" className="mb-4">
            <PyramidMark size={28} />
          </Link>
          <button
            onMouseEnter={() => setMenuOpen(true)}
            onClick={() => setMenuOpen(!menuOpen)}
            className="text-[#173F35] flex flex-col gap-1 w-5"
          >
            <span className={`block h-[1px] bg-current transition-all ${menuOpen ? "w-full" : "w-full"}`} />
            <span className={`block h-[1px] bg-current transition-all ${menuOpen ? "w-full" : "w-3/4"}`} />
          </button>
        </div>

        {/* Middle: Expanded Navigation Links */}
        <div className="flex-1 overflow-hidden relative w-full">
          <nav
            className={`absolute inset-0 flex flex-col justify-center px-12 transition-all duration-500 ${
              menuOpen ? "opacity-100 translate-x-0" : "opacity-0 -translate-x-4 pointer-events-none"
            }`}
          >
            {links.map((l) => (
              <Link
                key={l.to}
                to={l.to}
                className={`font-serif text-[28px] leading-[1.4] transition-all group ${
                  location.pathname === l.to ? "text-[#8FAE5D]" : "text-[#173F35]/70 hover:text-[#173F35]"
                }`}
              >
                <span className="relative inline-block">
                  {l.label}
                  <span className="absolute -bottom-1 left-0 w-0 h-px bg-[#8FAE5D] transition-all duration-300 group-hover:w-full" />
                </span>
              </Link>
            ))}
            <Link
              to="/contact"
              className="mt-10 px-6 py-3 bg-[#173F35] text-[#F6F3EC] text-[12px] tracking-wide text-center hover:bg-[#8FAE5D] hover:text-[#173F35] transition-colors"
            >
              Request a Quote
            </Link>
          </nav>

          {/* Middle: Collapsed Indicators */}
          <div
            className={`absolute inset-0 flex flex-col items-center justify-center gap-6 transition-all duration-500 ${
              menuOpen ? "opacity-0 translate-x-4 pointer-events-none" : "opacity-100 translate-x-0"
            }`}
          >
            <span className="text-[10px] tracking-[0.2em] text-[#173F35] writing-vertical-rl rotate-180">
              EXPLORE
            </span>
            <div className="w-px h-16 bg-[#D8C7A1]/40" />
            <span className="text-[10px] tracking-widest text-[#173F35]/40 font-serif">
              0{Math.floor(scrollPercent * 4) + 1}
            </span>
          </div>
        </div>

        {/* Bottom: Scroll Progress */}
        <div className="h-[120px] flex flex-col items-center justify-end pb-8 border-t border-[#D8C7A1]/20 w-full relative">
          <div className="w-[2px] h-12 bg-[#D8C7A1]/30 relative rounded-full overflow-hidden">
            <div
              className="absolute top-0 left-0 right-0 bg-[#8FAE5D] rounded-full origin-top"
              style={{ height: `${scrollPercent * 100}%` }}
            />
          </div>
        </div>
      </aside>

      {/* Spacer for desktop layout since aside is fixed left */}
      <div className="hidden lg:block w-[80px] shrink-0" />

      {/* ── Mobile Header (Top) ── */}
      <header className="lg:hidden fixed top-0 left-0 right-0 z-50 bg-[#F6F3EC]/96 backdrop-blur-sm border-b border-[#D8C7A1]/50 h-[72px] flex items-center justify-between px-6">
        <Link to="/" className="flex items-center gap-2">
          <PyramidMark size={24} />
          <span className="font-serif text-[#173F35] text-[15px]">Green Pyramids</span>
        </Link>
        <button onClick={() => setMenuOpen(!menuOpen)} className="text-[#173F35] w-5 h-5 flex flex-col justify-center gap-1">
          <span className={`block h-[1px] bg-current transition-all ${menuOpen ? "rotate-45 translate-y-[5px]" : ""}`} />
          <span className={`block h-[1px] bg-current transition-all ${menuOpen ? "opacity-0" : ""}`} />
          <span className={`block h-[1px] bg-current transition-all ${menuOpen ? "-rotate-45 -translate-y-[5px]" : ""}`} />
        </button>
      </header>

      {/* ── Mobile Full-screen Menu ── */}
      <div
        className={`lg:hidden fixed inset-0 z-40 bg-[#173F35] flex flex-col pt-[72px] transition-transform duration-500 ease-[cubic-bezier(0.76,0,0.24,1)] ${
          menuOpen ? "translate-y-0" : "-translate-y-full"
        }`}
      >
        <nav className="flex flex-col px-8 pt-12 gap-6">
          {links.map((l, i) => (
            <Link
              key={l.to}
              to={l.to}
              style={{ transitionDelay: menuOpen ? `${i * 0.05 + 0.2}s` : "0s" }}
              className={`font-serif text-[2.5rem] leading-none transition-all duration-500 ${
                menuOpen ? "opacity-100 translate-y-0" : "opacity-0 translate-y-8"
              } ${location.pathname === l.to ? "text-[#8FAE5D]" : "text-[#F6F3EC]/80"}`}
            >
              {l.label}
            </Link>
          ))}
        </nav>
      </div>
    </>
  );
}
