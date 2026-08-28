import { Link } from "react-router-dom";

export default function Footer() {
  return (
    <footer className="bg-[#173F35] text-[#F6F3EC]">
      <div className="max-w-7xl mx-auto px-6 lg:px-10 py-16 lg:py-20">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
          {/* Brand */}
          <div className="lg:col-span-1">
            <div className="flex items-center gap-3 mb-5">
              <div className="w-8 h-8 rounded-full border border-[#D8C7A1]/60 flex items-center justify-center">
                <svg width="14" height="14" viewBox="0 0 18 18" fill="none">
                  <polygon points="9,2 16,14 2,14" fill="none" stroke="#D8C7A1" strokeWidth="1.5" strokeLinejoin="round" />
                </svg>
              </div>
              <span className="font-serif text-base">Green Pyramids</span>
            </div>
            <p className="text-sm text-[#F6F3EC]/60 leading-relaxed max-w-xs">
              Premium Egyptian agricultural crops sourced, packed, and delivered to global markets with uncompromising quality.
            </p>
            <div className="flex gap-4 mt-6">
              {["LinkedIn", "WhatsApp"].map((s) => (
                <a key={s} href="#" className="text-xs text-[#F6F3EC]/50 hover:text-[#D8C7A1] transition-colors tracking-wide">
                  {s}
                </a>
              ))}
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h4 className="text-xs tracking-[0.2em] uppercase text-[#D8C7A1] mb-5">Quick Links</h4>
            <ul className="space-y-3">
              {[
                { label: "Home", to: "/" },
                { label: "About Us", to: "/about" },
                { label: "Products", to: "/products" },
                { label: "Our Process", to: "/process" },
                { label: "Quality", to: "/quality" },
                { label: "Contact", to: "/contact" },
              ].map((l) => (
                <li key={l.to}>
                  <Link to={l.to} className="text-sm text-[#F6F3EC]/60 hover:text-[#F6F3EC] transition-colors">
                    {l.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Products */}
          <div>
            <h4 className="text-xs tracking-[0.2em] uppercase text-[#D8C7A1] mb-5">Products</h4>
            <ul className="space-y-3">
              {["Fresh Fruits", "Fresh Vegetables", "Citrus", "Seasonal Crops", "Egyptian Mango", "Pomegranate"].map((p) => (
                <li key={p}>
                  <Link to="/products" className="text-sm text-[#F6F3EC]/60 hover:text-[#F6F3EC] transition-colors">
                    {p}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h4 className="text-xs tracking-[0.2em] uppercase text-[#D8C7A1] mb-5">Contact</h4>
            <ul className="space-y-3">
              {[
                { icon: "✉", label: "info@greenpyramids.eg" },
                { icon: "☎", label: "+20 (2) 000-0000" },
                { icon: "◑", label: "WhatsApp Available" },
                { icon: "◎", label: "Cairo, Egypt" },
              ].map((c) => (
                <li key={c.label} className="flex items-start gap-2.5">
                  <span className="text-[#8FAE5D] text-sm mt-0.5">{c.icon}</span>
                  <span className="text-sm text-[#F6F3EC]/60">{c.label}</span>
                </li>
              ))}
            </ul>
          </div>
        </div>

        <div className="border-t border-[#F6F3EC]/10 mt-14 pt-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <p className="text-xs text-[#F6F3EC]/40 tracking-wide">
            © 2026 Green Pyramids for Exporting Agricultural Crops. All Rights Reserved.
          </p>
          <p className="text-xs text-[#F6F3EC]/30 tracking-wide">Egypt · Global Markets</p>
        </div>
      </div>
    </footer>
  );
}
