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
      <body className={`${onest.className} dark:bg-gray-900`}>
        <ThemeProvider>
          <SessionContext>{children}</SessionContext>
        </ThemeProvider>
      </body>
    </html>
  );
}
