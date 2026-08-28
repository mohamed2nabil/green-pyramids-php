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
  const [scrolled, setScrolled] = useState(false);
  const [menuOpen, setMenuOpen] = useState(false);
  const location = useLocation();

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 30);
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  useEffect(() => {
    setMenuOpen(false);
  }, [location]);

  // Determine text color based on route if not scrolled
  const isDarkHero = location.pathname === "/products" || location.pathname === "/about" || location.pathname === "/process";
  const navTextColor = scrolled ? "text-[#173F35]" : isDarkHero ? "text-[#F6F3EC]" : "text-[#173F35]";
  const navBg = scrolled ? "bg-[#F6F3EC]/95 backdrop-blur-md shadow-sm border-b border-[#D8C7A1]/30" : "bg-transparent";

  return (
    <>
      <header className={`fixed top-0 left-0 right-0 z-50 transition-all duration-500 ease-out ${navBg}`}>
        <div className="max-w-7xl mx-auto px-6 lg:px-10 flex items-center justify-between h-[80px]">
          {/* Logo */}
          <Link to="/" className={`flex items-center gap-3 group flex-shrink-0 transition-colors duration-300 ${navTextColor}`}>
            <PyramidMark size={32} />
            <div>
              <div className="font-serif text-[17px] leading-none tracking-wide">Green Pyramids</div>
              <div className={`text-[9px] tracking-[0.2em] uppercase mt-1 opacity-70`}>Agricultural Exports</div>
            </div>
          </Link>

          {/* Desktop Links */}
          <nav className="hidden lg:flex items-center gap-8">
            {links.map((l) => (
              <Link
                key={l.to}
                to={l.to}
                className={`text-[13px] font-medium tracking-wide transition-all duration-300 hover:opacity-100 ${
                  location.pathname === l.to
                    ? `${navTextColor} opacity-100 border-b border-[#8FAE5D] pb-1`
                    : `${navTextColor} opacity-60 hover:-translate-y-0.5`
                }`}
              >
                {l.label}
              </Link>
            ))}
          </nav>

          {/* Desktop CTA & Hamburger */}
          <div className="flex items-center gap-5">
            <Link
              to="/contact"
              className={`hidden lg:flex px-6 py-2.5 rounded-full text-[13px] font-medium tracking-wide transition-all duration-300 ${
                scrolled || !isDarkHero
                  ? "bg-[#173F35] text-[#F6F3EC] hover:bg-[#8FAE5D] hover:text-[#173F35]"
                  : "bg-[#F6F3EC] text-[#173F35] hover:bg-[#8FAE5D]"
              }`}
            >
              Request Quote
            </Link>

            {/* Mobile Hamburger Button */}
            <button
              onClick={() => setMenuOpen(!menuOpen)}
              className={`lg:hidden w-10 h-10 flex flex-col justify-center items-center gap-[5px] rounded-full transition-colors ${navTextColor}`}
              aria-label="Toggle menu"
            >
              <span className={`block h-[1.5px] w-5 bg-current transition-transform duration-300 ${menuOpen ? "rotate-45 translate-y-[6.5px]" : ""}`} />
              <span className={`block h-[1.5px] w-5 bg-current transition-opacity duration-300 ${menuOpen ? "opacity-0" : ""}`} />
              <span className={`block h-[1.5px] w-5 bg-current transition-transform duration-300 ${menuOpen ? "-rotate-45 -translate-y-[6.5px]" : ""}`} />
            </button>
          </div>
        </div>
      </header>

      {/* Full-screen Mobile Menu */}
      <div
        className={`fixed inset-0 z-40 bg-[#173F35] flex flex-col transition-transform duration-500 ease-[cubic-bezier(0.76,0,0.24,1)] ${
          menuOpen ? "translate-x-0" : "translate-x-full"
        }`}
      >
        <div className="flex items-center justify-between px-6 h-[80px] border-b border-[#F6F3EC]/10">
          <Link to="/" onClick={() => setMenuOpen(false)} className="flex items-center gap-3">
            <PyramidMark size={32} />
            <span className="font-serif text-[#F6F3EC] text-[17px]">Green Pyramids</span>
          </Link>
        </div>
        <nav className="flex flex-col px-8 pt-12 gap-6 overflow-y-auto">
          {links.map((l, i) => (
            <Link
              key={l.to}
              to={l.to}
              style={{ transitionDelay: menuOpen ? `${i * 0.05 + 0.1}s` : "0s" }}
              className={`font-serif text-[2.5rem] leading-none transition-all duration-500 ${
                menuOpen ? "opacity-100 translate-y-0" : "opacity-0 translate-y-8"
              } ${location.pathname === l.to ? "text-[#8FAE5D]" : "text-[#F6F3EC]/80"}`}
            >
              {l.label}
            </Link>
          ))}
          <div 
            className={`mt-10 transition-all duration-500 delay-300 ${menuOpen ? "opacity-100 translate-y-0" : "opacity-0 translate-y-8"}`}
          >
            <Link
              to="/contact"
              className="inline-block w-full text-center py-4 bg-[#8FAE5D] text-[#173F35] font-medium tracking-wide rounded-full"
            >
              Request a Quote
            </Link>
          </div>
        </nav>
      </div>
    </>
  );
}
