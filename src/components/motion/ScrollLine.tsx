import { useEffect, useState } from "react";

export function ScrollLine() {
  const [scrollProgress, setScrollProgress] = useState(0);

  useEffect(() => {
    const handleScroll = () => {
      const scrollY = window.scrollY;
      const height = document.documentElement.scrollHeight - window.innerHeight;
      if (height > 0) {
        setScrollProgress(Math.min(1, Math.max(0, scrollY / height)));
      }
    };
    window.addEventListener("scroll", handleScroll, { passive: true });
    handleScroll();
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  // ponytail: Manual path length calculation instead of heavy libraries.
  // The path length here is approx 3000
  const pathLength = 3000;
  const strokeDashoffset = pathLength - scrollProgress * pathLength;

  return (
    <div className="fixed inset-0 pointer-events-none z-0 hidden lg:block overflow-hidden mix-blend-multiply opacity-20">
      <svg width="100%" height="100%" preserveAspectRatio="xMidYMid slice" viewBox="0 0 1000 3000">
        <path
          d="M 500,0 C 700,500 200,1000 500,1500 C 800,2000 300,2500 500,3000"
          fill="none"
          stroke="#8FAE5D"
          strokeWidth="4"
          strokeDasharray={pathLength}
          strokeDashoffset={strokeDashoffset}
          style={{ transition: "stroke-dashoffset 0.1s ease-out" }}
        />
      </svg>
    </div>
  );
}
