import { useParams, Link, useNavigate } from "react-router-dom";
import { useState } from "react";

const PRODUCTS: Record<string, {
  name: string; cat: string; origin: string; season: string;
  packaging: string[]; sizes: string[]; desc: string; img: string; galleryImgs: string[];
}> = {
  "egyptian-mango": {
    name: "Egyptian Mango",
    cat: "Fruits",
    origin: "Egypt — Upper Egypt & Nile Valley",
    season: "May – September",
    packaging: ["4 kg Carton", "5 kg Carton", "10 kg Carton", "Custom Packaging"],
    sizes: ["Extra Large", "Large", "Medium", "Small"],
    desc: "Renowned for its exceptional sweetness and rich aroma, the Egyptian mango is among the world's most sought-after tropical fruits. Grown in the warm climate of Upper Egypt and the Nile Valley, our mangoes are harvested at peak ripeness and carefully sorted to international quality standards.",
    img: "https://images.unsplash.com/photo-1744035355878-222dc04f79f5?w=900&h=1100&fit=crop&auto=format",
    galleryImgs: [
      "https://images.unsplash.com/photo-1601493700631-2b16ec4b4716?w=500&h=400&fit=crop&auto=format",
      "https://images.unsplash.com/photo-1708417134108-f4d009383f44?w=500&h=400&fit=crop&auto=format",
      "https://images.unsplash.com/photo-1652211955967-99c892925469?w=500&h=400&fit=crop&auto=format",
    ],
  },
  "pomegranate": {
    name: "Pomegranate",
    cat: "Fruits",
    origin: "Egypt — Nile Delta",
    season: "September – January",
    packaging: ["4 kg Carton", "5 kg Carton", "Custom Packaging"],
    sizes: ["Extra Large", "Large", "Medium"],
    desc: "Egyptian pomegranates are celebrated for their vibrant ruby-red arils, exceptional juice content, and balanced sweet-tart flavor. Grown in ideal Mediterranean-adjacent conditions, they are exported to premium markets across Europe and the Middle East.",
    img: "https://images.unsplash.com/photo-1701294878194-2aa42434e9af?w=900&h=1100&fit=crop&auto=format",
    galleryImgs: [
      "https://images.unsplash.com/photo-1645190392820-fcc39e2f3585?w=500&h=400&fit=crop&auto=format",
      "https://images.unsplash.com/photo-1708417134108-f4d009383f44?w=500&h=400&fit=crop&auto=format",
      "https://images.unsplash.com/photo-1652211955967-99c892925469?w=500&h=400&fit=crop&auto=format",
    ],
  },
};

const FALLBACK = {
  name: "Fresh Product",
  cat: "Egyptian Produce",
  origin: "Egypt",
  season: "Seasonal",
  packaging: ["4 kg Carton", "5 kg Carton"],
  sizes: ["Large", "Medium"],
  desc: "Premium quality Egyptian agricultural produce, carefully selected and packed for international export markets.",
  img: "https://images.unsplash.com/photo-1605027990121-cbae9e0642df?w=900&h=1100&fit=crop&auto=format",
  galleryImgs: [
    "https://images.unsplash.com/photo-1708417134108-f4d009383f44?w=500&h=400&fit=crop&auto=format",
    "https://images.unsplash.com/photo-1652211955967-99c892925469?w=500&h=400&fit=crop&auto=format",
    "https://images.unsplash.com/photo-1759272840538-ae4b07214c71?w=500&h=400&fit=crop&auto=format",
  ],
};

export default function ProductDetailPage() {
  const { slug } = useParams<{ slug: string }>();
  const navigate = useNavigate();
  const [selectedPack, setSelectedPack] = useState(0);
  const product = (slug && PRODUCTS[slug]) ? PRODUCTS[slug] : { ...FALLBACK, name: slug ? slug.replace(/-/g, " ").replace(/\b\w/g, (c) => c.toUpperCase()) : "Product" };

  return (
    <div className="bg-[#F6F3EC] min-h-screen">
      {/* Breadcrumb */}
      <div className="bg-[#F6F3EC] pt-28 pb-6 px-6 lg:px-10 border-b border-[#D8C7A1]/40">
        <div className="max-w-7xl mx-auto">
          <nav className="flex items-center gap-2 text-sm text-[#173F35]/50">
            <Link to="/" className="hover:text-[#173F35] transition-colors">Home</Link>
            <span>/</span>
            <Link to="/products" className="hover:text-[#173F35] transition-colors">Products</Link>
            <span>/</span>
            <span className="text-[#173F35]">{product.name}</span>
          </nav>
        </div>
      </div>

      {/* Product Hero */}
      <div className="max-w-7xl mx-auto px-6 lg:px-10 py-16">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">
          {/* Image */}
          <div className="relative">
            <div className="aspect-[3/4] rounded-2xl overflow-hidden bg-[#D8C7A1]/20">
              <img
                src={product.img}
                alt={product.name}
                className="w-full h-full object-cover"
              />
            </div>
          </div>

          {/* Info */}
          <div className="flex flex-col justify-center">
            <span className="text-xs tracking-[0.2em] uppercase text-[#8FAE5D] mb-4">{product.cat}</span>
            <h1 className="font-serif text-4xl lg:text-5xl text-[#173F35] leading-[1.1] mb-6">{product.name}</h1>
            <p className="text-[#173F35]/70 leading-relaxed mb-10">{product.desc}</p>

            {/* Details table */}
            <div className="space-y-4 mb-10">
              {[
                { label: "Origin", value: product.origin },
                { label: "Season", value: product.season },
              ].map((d) => (
                <div key={d.label} className="flex items-start gap-4 pb-4 border-b border-[#D8C7A1]/40">
                  <span className="text-xs tracking-[0.15em] uppercase text-[#173F35]/40 w-24 flex-shrink-0 mt-0.5">{d.label}</span>
                  <span className="text-sm text-[#173F35]">{d.value}</span>
                </div>
              ))}

              <div className="pb-4 border-b border-[#D8C7A1]/40">
                <span className="text-xs tracking-[0.15em] uppercase text-[#173F35]/40 block mb-3">Packaging</span>
                <div className="flex flex-wrap gap-2">
                  {product.packaging.map((p, i) => (
                    <button
                      key={p}
                      onClick={() => setSelectedPack(i)}
                      className={`px-4 py-2 rounded-full text-sm transition-colors ${
                        selectedPack === i
                          ? "bg-[#173F35] text-[#F6F3EC]"
                          : "border border-[#D8C7A1] text-[#173F35]/70 hover:border-[#173F35]"
                      }`}
                    >
                      {p}
                    </button>
                  ))}
                </div>
              </div>

              <div className="pb-4 border-b border-[#D8C7A1]/40">
                <span className="text-xs tracking-[0.15em] uppercase text-[#173F35]/40 block mb-3">Sizes / Grades</span>
                <div className="flex flex-wrap gap-2">
                  {product.sizes.map((s) => (
                    <span key={s} className="px-3 py-1 rounded-full text-xs bg-[#D8C7A1]/30 text-[#173F35]/70">{s}</span>
                  ))}
                </div>
              </div>
            </div>

            <Link
              to={`/contact?product=${encodeURIComponent(product.name)}`}
              className="inline-block w-full text-center py-4 bg-[#173F35] text-[#F6F3EC] font-medium tracking-wide rounded-full hover:bg-[#8FAE5D] hover:text-[#173F35] transition-colors duration-200"
            >
              Request This Product
            </Link>
          </div>
        </div>
      </div>

      {/* Gallery */}
      <div className="max-w-7xl mx-auto px-6 lg:px-10 py-12 border-t border-[#D8C7A1]/40">
        <p className="text-xs tracking-[0.25em] uppercase text-[#8FAE5D] mb-6">Product Highlights</p>
        <div className="grid grid-cols-3 gap-4">
          {product.galleryImgs.map((img, i) => (
            <div key={i} className="aspect-[4/3] rounded-xl overflow-hidden bg-[#D8C7A1]/20">
              <img src={img} alt={`${product.name} ${["Harvesting", "Sorting", "Packing"][i]}`} className="w-full h-full object-cover hover:scale-105 transition-transform duration-500" />
            </div>
          ))}
        </div>
        <div className="flex gap-8 mt-6">
          {["Harvesting", "Quality Sorting", "Export Packing"].map((label) => (
            <span key={label} className="text-xs text-[#173F35]/40 tracking-wide">{label}</span>
          ))}
        </div>
      </div>

      {/* Back */}
      <div className="max-w-7xl mx-auto px-6 lg:px-10 py-10">
        <button
          onClick={() => navigate(-1)}
          className="text-sm text-[#173F35]/50 hover:text-[#173F35] transition-colors flex items-center gap-2"
        >
          ← Back to Products
        </button>
      </div>
    </div>
  );
}
