import { Link } from "react-router-dom";

const STANDARDS = [
  { title: "Farm Selection", body: "We audit and approve farms based on soil quality, water source, pest management practices, and historical yield performance before any partnership begins." },
  { title: "Product Inspection", body: "Incoming produce is inspected at arrival in our packing facilities. Size, color, firmness, and visual quality are assessed against our export grading criteria." },
  { title: "Sorting & Grading", body: "Products are mechanically and manually sorted into export grades — ensuring uniformity that meets international market requirements." },
  { title: "Packing Standards", body: "We use export-standard cartons and packaging materials that protect produce during long-haul refrigerated transport." },
  { title: "Cold Chain", body: "Temperature-controlled storage and refrigerated transport maintain product freshness from packing house to destination port." },
  { title: "Export Documentation", body: "We prepare all required documentation including phytosanitary certificates, origin certificates, and customs clearance paperwork." },
];

export default function QualityPage() {
  return (
    <div className="bg-[#F6F3EC] min-h-screen">

      {/* ── HERO — precise, clinical, trustworthy ──────────────── */}
      <div className="bg-[#173F35] pt-[72px] relative overflow-hidden">

        {/* Fine grid overlay — precision / engineering feel */}
        <div className="absolute inset-0 opacity-[0.045] pointer-events-none" aria-hidden>
          <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs>
              <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#F6F3EC" strokeWidth="0.5" />
              </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)" />
          </svg>
        </div>

        {/* Subtle right-side pyramid mark */}
        <div className="absolute right-10 top-1/2 -translate-y-1/2 opacity-[0.06] pointer-events-none hidden lg:block" aria-hidden>
          <svg width="280" height="280" viewBox="0 0 280 280" fill="none">
            <polygon points="140,20 20,260 260,260" stroke="#F6F3EC" strokeWidth="1.2" />
            <line x1="95" y1="100" x2="185" y2="100" stroke="#F6F3EC" strokeWidth="0.7" />
            <line x1="70" y1="155" x2="210" y2="155" stroke="#F6F3EC" strokeWidth="0.7" />
            <line x1="42" y1="210" x2="238" y2="210" stroke="#F6F3EC" strokeWidth="0.7" />
          </svg>
        </div>

        <div className="relative max-w-7xl mx-auto px-6 lg:px-10 pt-16 pb-20">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-10 items-end">
            <div>
              <div className="flex items-center gap-3 mb-5">
                <div className="w-5 h-px bg-[#8FAE5D]" />
                <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Standards</p>
              </div>
              <h1 className="font-serif text-5xl lg:text-[68px] text-[#F6F3EC] leading-[1.03]">
                Our Quality<br />Commitment
              </h1>
            </div>
            <div>
              <p className="text-[#F6F3EC]/50 text-[15px] leading-relaxed max-w-md">
                Quality is not a checkpoint at the end of our process — it is embedded at every stage, from farm selection to final delivery.
              </p>
              {/* Precision stats */}
              <div className="flex items-center gap-8 mt-8 pt-8 border-t border-[#F6F3EC]/10">
                {[["100%", "Traceability"], ["6", "Chain stages"], ["0", "Compromises"]].map(([num, label]) => (
                  <div key={num}>
                    <p className="font-serif text-2xl text-[#F6F3EC]">{num}</p>
                    <p className="text-[10px] tracking-[0.15em] uppercase text-[#F6F3EC]/38 mt-0.5">{label}</p>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Intro with image */}
      <section className="py-24 lg:py-36 max-w-7xl mx-auto px-6 lg:px-10">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
          <div>
            <div className="flex items-center gap-3 mb-5">
              <div className="w-5 h-px bg-[#8FAE5D]" />
              <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Approach</p>
            </div>
            <h2 className="font-serif text-4xl text-[#173F35] leading-[1.08] mb-7">
              Quality Control at Every Stage
            </h2>
            <p className="text-[#173F35]/65 leading-relaxed mb-5 text-[15px]">
              At Green Pyramids, quality is a system — not a single inspection step. We apply consistent standards from the moment we select a farm partner through to the final loading of export containers.
            </p>
            <p className="text-[#173F35]/65 leading-relaxed text-[15px]">
              Our quality team is present at every critical stage: farm selection, harvest supervision, arrival inspection, sorting, packing, and cold chain management. Every shipment is traceable back to its source farm.
            </p>
          </div>
          <div>
            <img
              src="https://images.unsplash.com/photo-1652211955967-99c892925469?w=900&h=700&fit=crop&auto=format"
              alt="Quality control at packing facility"
              className="w-full aspect-[4/3] object-cover rounded-2xl"
            />
          </div>
        </div>
      </section>

      {/* Standards Grid */}
      <section className="py-24 bg-[#173F35]">
        <div className="max-w-7xl mx-auto px-6 lg:px-10">
          <div className="flex items-center gap-3 mb-5">
            <div className="w-5 h-px bg-[#8FAE5D]" />
            <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">The System</p>
          </div>
          <h2 className="font-serif text-4xl lg:text-5xl text-[#F6F3EC] leading-[1.08] mb-14">Quality Standards</h2>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            {STANDARDS.map((s, i) => (
              <div key={s.title} className="p-7 rounded-xl border border-[#F6F3EC]/8 bg-[#F6F3EC]/[0.04] hover:bg-[#F6F3EC]/[0.08] transition-colors">
                <div className="font-serif text-4xl text-[#D8C7A1]/40 mb-5">0{i + 1}</div>
                <div className="w-6 h-px bg-[#8FAE5D]/40 mb-4" />
                <h3 className="font-serif text-lg text-[#F6F3EC] mb-3">{s.title}</h3>
                <p className="text-[13px] text-[#F6F3EC]/52 leading-relaxed">{s.body}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Certifications */}
      <section className="py-24 lg:py-36 max-w-7xl mx-auto px-6 lg:px-10">
        <div className="flex items-center gap-3 mb-4">
          <div className="w-5 h-px bg-[#8FAE5D]" />
          <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Certifications</p>
        </div>
        <h2 className="font-serif text-4xl text-[#173F35] leading-[1.08] mb-5">Our Certifications</h2>
        <p className="text-[#173F35]/55 max-w-xl mb-12 leading-relaxed text-[14px]">
          Green Pyramids operates in compliance with international export and food safety standards. Our official certifications are maintained and renewed annually.
        </p>

        <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
          {[
            { label: "Phytosanitary Certificate", real: true },
            { label: "Certificate of Origin", real: true },
            { label: "Export License", real: true },
            { label: "Certification Placeholder", real: false },
            { label: "Certification Placeholder", real: false },
            { label: "Certification Placeholder", real: false },
            { label: "Certification Placeholder", real: false },
            { label: "Certification Placeholder", real: false },
          ].map((cert, i) => (
            <div
              key={i}
              className={`rounded-xl p-6 text-center border ${
                cert.real
                  ? "border-[#8FAE5D]/30 bg-[#8FAE5D]/5"
                  : "border-dashed border-[#D8C7A1]/50 bg-[#D8C7A1]/8"
              }`}
            >
              <div className="w-10 h-10 rounded-full bg-[#D8C7A1]/25 mx-auto mb-4 flex items-center justify-center">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                  <rect x="3" y="2" width="12" height="14" rx="2" stroke="#8FAE5D" strokeWidth="1.2" />
                  <line x1="6" y1="6" x2="12" y2="6" stroke="#8FAE5D" strokeWidth="0.9" />
                  <line x1="6" y1="9" x2="12" y2="9" stroke="#8FAE5D" strokeWidth="0.9" />
                  <line x1="6" y1="12" x2="10" y2="12" stroke="#8FAE5D" strokeWidth="0.9" />
                </svg>
              </div>
              <p className="text-[11px] text-[#173F35]/58 leading-snug">{cert.label}</p>
              {!cert.real && <p className="text-[9px] text-[#173F35]/28 mt-1 tracking-wide uppercase">Placeholder</p>}
            </div>
          ))}
        </div>
      </section>

      {/* CTA */}
      <section className="bg-[#173F35] py-24 text-center">
        <h2 className="font-serif text-3xl lg:text-5xl text-[#F6F3EC] mb-6 max-w-xl mx-auto leading-[1.08]">
          Quality You Can Rely On, Every Shipment.
        </h2>
        <Link
          to="/contact"
          className="inline-block px-9 py-4 bg-[#8FAE5D] text-[#173F35] font-medium tracking-wide rounded-full hover:bg-[#F6F3EC] transition-colors text-[13px]"
        >
          Request a Quote
        </Link>
      </section>
    </div>
  );
}
