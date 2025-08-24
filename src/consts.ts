// Metadata utama untuk SEO dan title template
export const SITE_METADATA = {
  title: "Codama - Web Solution for Your Business",
  titleTemplate: (pageTitle?: string) => pageTitle ? `${pageTitle} | Codama - Web Solution for Your Business` : "Codama - Web Solution for Your Business",
  description: "Codama provides comprehensive web solutions to help your business grow and succeed online. From web development, SEO optimization, to digital marketing, we empower your business for the digital age.",
  keywords: [
    "Codama",
    "Web Development",
    "SEO",
    "Digital Marketing",
    "Business Solutions",
    "Website Design",
    "Online Presence",
    "Tailwind CSS",
    "Next.js",
    "Indonesia"
  ],
  author: "zaadevofc",
  creator: "zaadevofc",
  publisher: "Codama",
  robots: "index, follow",
  canonical: "https://codama.jaa.web.id",
  themeColor: "#0f172a",
  openGraph: {
    title: "Codama - Web Solution for Your Business",
    description: "Codama provides comprehensive web solutions to help your business grow and succeed online.",
    url: "https://codama.jaa.web.id",
    siteName: "Codama",
    images: [
      {
        url: "/seo/og-image.png",
        width: 1200,
        height: 630,
        alt: "Codama - Web Solution for Your Business"
      }
    ],
    locale: "id_ID",
    type: "website"
  },
  twitter: {
    card: "summary_large_image",
    site: "@codamaid",
    creator: "@zaadevofc",
    title: "Codama - Web Solution for Your Business",
    description: "Codama provides comprehensive web solutions to help your business grow and succeed online.",
    image: "/seo/og-image.png"
  },
  icons: {
    icon: "/favicon.ico",
    shortcut: "/favicon.ico",
    apple: "/apple-touch-icon.png"
  }
};