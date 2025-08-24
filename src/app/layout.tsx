import { Onest } from 'next/font/google';
import './globals.css';

import { ThemeProvider } from '@/context/ThemeContext';
import SessionContext from '../context/SessionContext';

const onest = Onest({
  subsets: ["latin"],
});

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en">
      <head>
        <title>Codama - Your Best Web Solution</title>
        <meta name="description" content="Codama provides the best web solutions with high performance and SEO optimized features." />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="canonical" href="https://codama.jaa.web.id/" />
        <link rel="manifest" href="/seo/site.webmanifest" />
        <link rel="icon" href="/seo/favicon.ico" />
        <meta property="og:title" content="Codama - Your Best Web Solution" />
        <meta property="og:description" content="Codama provides the best web solutions with high performance and SEO optimized features." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://codama.jaa.web.id/" />
        <meta property="og:image" content="/seo/og-image.png" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Codama - Your Best Web Solution" />
        <meta name="twitter:description" content="Codama provides the best web solutions with high performance and SEO optimized features." />
        <meta name="twitter:image" content="/seo/twitter-image.png" />
      </head>
      <body className={`${onest.className} dark:bg-gray-900`}>
        <ThemeProvider>
          <SessionContext>{children}</SessionContext>
        </ThemeProvider>
      </body>
    </html>
  );
}