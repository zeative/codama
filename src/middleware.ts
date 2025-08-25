export { default } from "next-auth/middleware"


export const config = {
  matcher: ["/admin/:path*", "/profile/:path*", "/orders/:path*", "/support/:path*", "/income/:path*", "/moderate/:path*", "/ekstra/:path*", "/designers/:path*"],
};