import { useState } from "react";
import { Link } from "react-router-dom";
import { Reveal } from "../components/motion/Reveal";
import { TubesBackground } from "../components/motion/TubesBackground";

const CATEGORIES = [
  {
    name: "Citrus Fruits",
    count: "8 Varieties",
    desc: "Navel oranges, Valencias, lemons, and mandarins grown in the Nile Delta.",
    img: "https://images.unsplash.com/photo-1594143887697-fb87011a8b2a?w=800&h=1000&fit=crop&auto=format"
  },
  {
    name: "Fresh Vegetables",
    count: "15 Varieties",
    img: "https://images.unsplash.com/photo-1572439409920-0b7111340de3?w=800&h=600&fit=crop&auto=format"
  },
  {
    name: "Seasonal Crops",
    count: "12 Varieties",
    img: "https://images.unsplash.com/photo-1666987571351-737b29874697?w=800&h=600&fit=crop&auto=format"
  },
  {
    name: "Grapes & Berries",
    count: "6 Varieties",
    img: "https://images.unsplash.com/photo-1601493700631-2b16ec4b4716?w=800&h=600&fit=crop&auto=format"
  }
];

const FEATURED_PRODUCTS = [
  { name: "Egyptian Mango", cat: "Fruits", season: "May – Sep", img: "https://images.unsplash.com/photo-1744035355878-222dc04f79f5?w=500&h=600&fit=crop&auto=format" },
  { name: "Pomegranate", cat: "Fruits", season: "Sep – Jan", img: "https://images.unsplash.com/photo-1701294878194-2aa42434e9af?w=500&h=600&fit=crop&auto=format" },
  { name: "Navel Orange", cat: "Citrus", season: "Nov – Apr", img: "https://images.unsplash.com/photo-1594143887697-fb87011a8b2a?w=500&h=600&fit=crop&auto=format" },
  { name: "Strawberry", cat: "Fruits", season: "Dec – Apr", img: "https://images.unsplash.com/photo-1601493700631-2b16ec4b4716?w=500&h=600&fit=crop&auto=format" },
  { name: "Potato", cat: "Vegetables", season: "Year-round", img: "https://images.unsplash.com/photo-1572439409920-0b7111340de3?w=500&h=600&fit=crop&auto=format" },
  { name: "White Onion", cat: "Vegetables", season: "Mar – Jul", img: "https://images.unsplash.com/photo-1720807740685-d9cdcb0836a7?w=500&h=600&fit=crop&auto=format" },
];

const TRUST_FEATURES = [
  { n: "01", title: "Traceable Origins", body: "Every shipment is tracked back to the exact farm, ensuring accountability and consistent quality." },
  { n: "02", title: "Export Grade", body: "We strictly adhere to EU and international MRLs (Maximum Residue Limits) and quality standards." },
  { n: "03", title: "Cold Chain", body: "Advanced cooling and packing facilities preserve freshness from harvest to destination." },
  { n: "04", title: "Reliability", body: "Accurate sizing, proper packing, and on-time shipping for long-term partnerships." },
];

const PROCESS_STEPS = [
  { n: "01", label: "Harvest" },
  { n: "02", label: "Selection" },
  { n: "03", label: "Cooling" },
  { n: "04", label: "Packing" },
  { n: "05", label: "Inspection" },
  { n: "06", label: "Export" },
];

const MARKETS = [
  { region: "Europe", desc: "Serving major distributors in the UK, Netherlands, Germany, and Italy with strict compliance to EU standards." },
  { region: "Gulf Countries", desc: "Rapid logistics via sea and air to Saudi Arabia, UAE, and neighboring nations." },
  { region: "Asia", desc: "Expanding reach into Eastern markets with long-shelf-life packing solutions." },
  { region: "Africa", desc: "Supporting neighboring regional markets with essential agricultural commodities." },
];

export default function HomePage() {
  return (
    <div className="bg-[#F6F3EC]">
      {/* ── HERO — Tubes Interactive Background ─── */}
      <section className="relative h-screen w-full overflow-hidden bg-[#050c0a]">
        <TubesBackground className="absolute inset-0">
          <div className="absolute inset-0 flex flex-col items-center justify-center text-center px-6 pointer-events-none mt-16">
            <Reveal as="div" type="letter" className="flex items-center gap-3 mb-6">
              <div className="w-6 h-px bg-[#8FAE5D]" />
              <p className="text-[12px] tracking-[0.3em] uppercase text-[#8FAE5D]">Egyptian Agricultural Exports</p>
              <div className="w-6 h-px bg-[#8FAE5D]" />
            </Reveal>

            <Reveal as="h1" type="letter" className="font-serif text-5xl sm:text-7xl lg:text-8xl text-[#F6F3EC] leading-[1.05] max-w-5xl mx-auto drop-shadow-2xl mb-8">
              From Egyptian Soil<br />To Global Markets.
            </Reveal>

            <Reveal as="p" type="word" className="text-[#F6F3EC]/70 text-lg lg:text-xl max-w-2xl mx-auto leading-relaxed mb-12 drop-shadow-md">
              Premium agricultural crops sourced, packed, and delivered with uncompromising quality.
            </Reveal>

            <Reveal as="div" type="fade" className="flex flex-col sm:flex-row gap-4 pointer-events-auto">
              <Link
                to="/products"
                className="group inline-flex items-center justify-center gap-2 px-8 py-4 bg-[#8FAE5D] text-[#173F35] text-[14px] font-medium tracking-wide rounded-full hover:bg-[#F6F3EC] hover:shadow-lg transition-all duration-300"
              >
                Explore Products
                <span className="group-hover:translate-x-1 transition-transform">→</span>
              </Link>
              <Link
                to="/contact"
                className="inline-flex items-center justify-center px-8 py-4 border border-[#F6F3EC]/30 text-[#F6F3EC] text-[14px] font-medium tracking-wide rounded-full hover:border-[#F6F3EC] hover:bg-[#F6F3EC]/10 transition-all duration-300 backdrop-blur-sm"
              >
                Request a Quote
              </Link>
            </Reveal>
            
            <Reveal as="div" type="fade" className="absolute bottom-12 flex flex-col items-center gap-2 text-[#F6F3EC]/40 animate-pulse">
              <span className="text-[10px] tracking-[0.2em] uppercase">Click anywhere to change colors</span>
            </Reveal>
          </div>
        </TubesBackground>
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
