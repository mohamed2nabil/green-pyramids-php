import { useState } from "react";
import { Link } from "react-router-dom";
import { CardSwap } from "../components/motion/CardSwap";
import { HorizontalScroll } from "../components/motion/HorizontalScroll";

const ALL_PRODUCTS = [
  { name: "Egyptian Mango", slug: "egyptian-mango", cat: "Fruits", season: "May – Sep", img: "https://images.unsplash.com/photo-1744035355878-222dc04f79f5?w=500&h=600&fit=crop&auto=format" },
  { name: "Pomegranate", slug: "pomegranate", cat: "Fruits", season: "Sep – Jan", img: "https://images.unsplash.com/photo-1701294878194-2aa42434e9af?w=500&h=600&fit=crop&auto=format" },
  { name: "Navel Orange", slug: "navel-orange", cat: "Citrus", season: "Nov – Apr", img: "https://images.unsplash.com/photo-1594143887697-fb87011a8b2a?w=500&h=600&fit=crop&auto=format" },
  { name: "Strawberry", slug: "strawberry", cat: "Fruits", season: "Dec – Apr", img: "https://images.unsplash.com/photo-1601493700631-2b16ec4b4716?w=500&h=600&fit=crop&auto=format" },
  { name: "Potato", slug: "potato", cat: "Vegetables", season: "Year-round", img: "https://images.unsplash.com/photo-1572439409920-0b7111340de3?w=500&h=600&fit=crop&auto=format" },
  { name: "White Onion", slug: "white-onion", cat: "Vegetables", season: "Mar – Jul", img: "https://images.unsplash.com/photo-1720807740685-d9cdcb0836a7?w=500&h=600&fit=crop&auto=format" },
  { name: "Sweet Pepper", slug: "sweet-pepper", cat: "Vegetables", season: "Oct – May", img: "https://images.unsplash.com/photo-1708417134108-f4d009383f44?w=500&h=600&fit=crop&auto=format" },
  { name: "Lemon", slug: "lemon", cat: "Citrus", season: "Year-round", img: "https://images.unsplash.com/photo-1623930376395-0f3ad22cfac2?w=500&h=600&fit=crop&auto=format" },
  { name: "Mandarin", slug: "mandarin", cat: "Citrus", season: "Oct – Mar", img: "https://images.unsplash.com/photo-1663681240509-d9a1b7871898?w=500&h=600&fit=crop&auto=format" },
  { name: "Guava", slug: "guava", cat: "Fruits", season: "Aug – Nov", img: "https://images.unsplash.com/photo-1605027990121-cbae9e0642df?w=500&h=600&fit=crop&auto=format" },
  { name: "Garlic", slug: "garlic", cat: "Seasonal Crops", season: "Mar – Jul", img: "https://images.unsplash.com/photo-1666987571351-737b29874697?w=500&h=600&fit=crop&auto=format" },
  { name: "Sweet Potato", slug: "sweet-potato", cat: "Seasonal Crops", season: "Sep – Feb", img: "https://images.unsplash.com/photo-1649192537902-7b06265dd08f?w=500&h=600&fit=crop&auto=format" },
];

const FILTERS = ["All Products", "Fruits", "Vegetables", "Citrus", "Seasonal Crops"];

// Featured hero product images for the strip at the bottom of hero
const HERO_STRIP = [
  "https://images.unsplash.com/photo-1744035355878-222dc04f79f5?w=300&h=200&fit=crop&auto=format",
  "https://images.unsplash.com/photo-1701294878194-2aa42434e9af?w=300&h=200&fit=crop&auto=format",
  "https://images.unsplash.com/photo-1594143887697-fb87011a8b2a?w=300&h=200&fit=crop&auto=format",
  "https://images.unsplash.com/photo-1601493700631-2b16ec4b4716?w=300&h=200&fit=crop&auto=format",
  "https://images.unsplash.com/photo-1605027990121-cbae9e0642df?w=300&h=200&fit=crop&auto=format",
];

export default function ProductsPage() {
  const [active, setActive] = useState("All Products");
  const filtered = active === "All Products" ? ALL_PRODUCTS : ALL_PRODUCTS.filter((p) => p.cat === active);

  return (
    <div className="bg-[#F6F3EC] min-h-screen">

      <div className="bg-[#173F35] pt-[72px] relative overflow-hidden h-[80vh] flex flex-col lg:flex-row">
        {/* Left: Content */}
        <div className="lg:w-[45%] z-10 flex flex-col justify-center px-6 lg:px-16 pt-16 pb-10">
          <div className="flex items-center gap-3 mb-5">
            <div className="w-5 h-px bg-[#8FAE5D]" />
            <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Our Catalog</p>
          </div>
          <h1 className="font-serif text-5xl lg:text-[68px] text-[#F6F3EC] leading-[1.03] mb-6">
            Egyptian<br />Fresh Produce
          </h1>
          <p className="text-[#F6F3EC]/60 text-[15px] max-w-sm leading-relaxed mb-10">
            Explore our selection of premium agricultural crops prepared for international markets.
          </p>
          <div className="flex gap-8">
            <div>
              <p className="font-serif text-3xl text-[#F6F3EC]">{ALL_PRODUCTS.length}+</p>
              <p className="text-[10px] tracking-[0.18em] uppercase text-[#F6F3EC]/40 mt-1">Export-ready</p>
            </div>
            <div>
              <p className="font-serif text-3xl text-[#F6F3EC]">12</p>
              <p className="text-[10px] tracking-[0.18em] uppercase text-[#F6F3EC]/40 mt-1">Global Markets</p>
            </div>
          </div>
        </div>

        {/* Right: CardSwap */}
        <div className="lg:w-[55%] relative h-[60vh] lg:h-full bg-[#050c0a] overflow-hidden">
           <CardSwap />
        </div>
      </div>

      {/* Filters — sticky */}
      <div className="sticky top-[72px] z-30 bg-[#F6F3EC] border-b border-[#D8C7A1]/50">
        <div className="max-w-7xl mx-auto px-6 lg:px-10">
          <div className="flex items-center gap-2 overflow-x-auto py-3.5 scrollbar-hide">
            {FILTERS.map((f) => (
              <button
                key={f}
                onClick={() => setActive(f)}
                className={`flex-shrink-0 px-5 py-2 rounded-full text-[12px] tracking-wide transition-colors duration-200 ${
                  active === f
                    ? "bg-[#173F35] text-[#F6F3EC]"
                    : "bg-[#D8C7A1]/25 text-[#173F35]/65 hover:bg-[#D8C7A1]/50"
                }`}
              >
                {f}
              </button>
            ))}
          </div>
        </div>
      </div>

      {/* Product Grid */}
      <div className="max-w-7xl mx-auto px-6 lg:px-10 py-14">
        <p className="text-[11px] text-[#173F35]/35 mb-8 tracking-wide uppercase">{filtered.length} products</p>
        <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 lg:gap-5">
          {filtered.map((p, i) => (
            <Link key={p.slug} to={`/products/${p.slug}`} className="group">
              <div
                className={`relative overflow-hidden rounded-xl bg-[#D8C7A1]/20 mb-3.5 ${
                  i % 7 === 0 ? "aspect-[3/5]" : "aspect-[3/4]"
                }`}
              >
                <img
                  src={p.img}
                  alt={p.name}
                  className="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
                <div className="absolute top-3 left-3">
                  <span className="text-[9px] tracking-[0.15em] uppercase bg-[#F6F3EC]/88 text-[#173F35] px-2 py-0.5 rounded-full">
                    {p.cat}
                  </span>
                </div>
                <div className="absolute inset-0 bg-gradient-to-t from-[#173F35]/65 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                <div className="absolute bottom-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  <span className="text-[11px] text-[#F6F3EC] tracking-wide">View Product →</span>
                </div>
              </div>
              <h3 className="font-medium text-[#173F35] text-[13px] mb-0.5">{p.name}</h3>
              <p className="text-[11px] text-[#173F35]/45">{p.season}</p>
            </Link>
          ))}
        </div>
      </div>

      {/* Horizontal Storytelling Section */}
      <HorizontalScroll>
        {FILTERS.slice(1).map((cat, i) => {
          const catProducts = ALL_PRODUCTS.filter(p => p.cat === cat || p.cat === "Seasonal Crops").slice(0, 3);
          return (
            <div key={cat} className="flex flex-col w-[80vw] lg:w-[45vw] shrink-0">
              <div className="flex items-center gap-4 mb-10">
                <span className="font-serif text-5xl lg:text-7xl text-[#173F35]/20">0{i + 1}</span>
                <h2 className="font-serif text-4xl lg:text-5xl text-[#173F35]">{cat}</h2>
              </div>
              <div className="flex gap-6">
                {catProducts.map((p, idx) => (
                  <div key={p.slug} className={`relative flex-1 rounded-xl overflow-hidden aspect-[3/4] bg-[#D8C7A1]/20 ${idx === 1 ? 'mt-12' : ''}`}>
                    <img src={p.img} alt={p.name} className="absolute inset-0 w-full h-full object-cover" />
                    <div className="absolute inset-0 bg-gradient-to-t from-[#173F35]/80 to-transparent" />
                    <div className="absolute bottom-4 left-4">
                      <p className="text-[#F6F3EC] font-medium">{p.name}</p>
                      <p className="text-[#F6F3EC]/60 text-xs">{p.season}</p>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          );
        })}
      </HorizontalScroll>

      {/* CTA */}
      <div className="bg-[#173F35] py-20 text-center">
        <div className="flex items-center justify-center gap-3 mb-4">
          <div className="w-5 h-px bg-[#8FAE5D]" />
          <p className="text-[11px] tracking-[0.28em] uppercase text-[#8FAE5D]">Ready to Order?</p>
          <div className="w-5 h-px bg-[#8FAE5D]" />
        </div>
        <h2 className="font-serif text-3xl lg:text-4xl text-[#F6F3EC] mb-5">
          {"Don't see what you're looking for?"}
        </h2>
        <p className="text-[#F6F3EC]/50 mb-8 max-w-sm mx-auto text-[14px] leading-relaxed">
          Contact us — we source a wide range of Egyptian crops on request.
        </p>
        <Link
          to="/contact"
          className="inline-block px-8 py-4 bg-[#8FAE5D] text-[#173F35] font-medium tracking-wide rounded-full hover:bg-[#F6F3EC] transition-colors text-[13px]"
        >
          Send an Inquiry
        </Link>
      </div>
    </div>
  );
}
