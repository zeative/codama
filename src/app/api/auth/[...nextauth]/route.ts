import NextAuth from "next-auth";
import GoogleProvider from "next-auth/providers/google";
import { PrismaClient, ROLE } from '@prisma/client';

const prisma = new PrismaClient();

declare module "next-auth" {
  interface Session {
    accessToken?: string;
    role?: ROLE;
    isApproved?: boolean;
  }
}


const handler = NextAuth({
  providers: [
    GoogleProvider({
      clientId: process.env.GOOGLE_CLIENT_ID || "",
      clientSecret: process.env.GOOGLE_CLIENT_SECRET || "",
    }),
  ],
  secret: process.env.NEXTAUTH_SECRET,
  session: {
    strategy: "jwt",
  },
  pages: {
    signIn: '/auth/signin',
  },
  callbacks: {
    async jwt({ token, account }) {
      if (account) {
        token.accessToken = account.access_token;
        // Cari atau buat user di database
        const dbUser = await prisma.user.findUnique({ where: { email: token.email! } });
        if (!dbUser) {
          await prisma.user.create({
            data: {
              email: token.email || "",
              name: token.name || "",
              image: token.picture || "",
              role: "USER",
              isApproved: false
            }
          });
        }
      }
      return token;
    },
    async session({ session, token }) {
      // Selalu update session dari database
      const email = session.user?.email;
      if (!email) return session;
      const dbUser = await prisma.user.findUnique({ where: { email } });
      if (dbUser) {
        session.user = {
          ...session.user,
          name: dbUser.name,
          image: dbUser.image,
          email: dbUser.email
        };
        session.role = dbUser.role;
        session.isApproved = dbUser.isApproved;
      }
      session.accessToken = token.accessToken as string;
      return session;
    },
    redirect({ baseUrl }) {
      return baseUrl;
    },
  },
});

export { handler as GET, handler as POST };