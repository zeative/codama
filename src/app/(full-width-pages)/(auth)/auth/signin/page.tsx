import SignInForm from "@/components/auth/SignInForm";
import { SITE_METADATA } from "@/consts";
import { Metadata } from "next";

export const metadata: Metadata = {
  ...SITE_METADATA,
  metadataBase: new URL("https://codama.jaa.web.id"),
};

export default function SignIn() {
  return <SignInForm />;
}