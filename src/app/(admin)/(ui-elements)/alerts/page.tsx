import ComponentCard from "@/components/common/ComponentCard";
import PageBreadcrumb from "@/components/common/PageBreadCrumb";
import Alert from "@/components/ui/alert/Alert";
import { Metadata } from "next";
import React from "react";

export const metadata: Metadata = {
  title: "Codama - Web Solution for Your Business",
  description: "Codama provides comprehensive web solutions to help your business grow and succeed online.",
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
        alt: "Codama Web Solution",
      },
    ],
    locale: "en_US",
    type: "website",
  },
  twitter: {
    card: "summary_large_image",
    title: "Codama - Web Solution for Your Business",
    description: "Codama provides comprehensive web solutions to help your business grow and succeed online.",
    images: ["/seo/twitter-image.png"],
  },
  metadataBase: new URL("https://codama.jaa.web.id"),
};

export default function Alerts() {
  return (
    <div>
      <PageBreadcrumb pageTitle="Alerts" />
      <div className="space-y-5 sm:space-y-6">
        <ComponentCard title="Success Alert">
          <Alert
            variant="success"
            title="Success Message"
            message="Be cautious when performing this action."
            showLink={true}
            linkHref="/"
            linkText="Learn more"
          />
          <Alert
            variant="success"
            title="Success Message"
            message="Be cautious when performing this action."
            showLink={false}
          />
        </ComponentCard>
        <ComponentCard title="Warning Alert">
          <Alert
            variant="warning"
            title="Warning Message"
            message="Be cautious when performing this action."
            showLink={true}
            linkHref="/"
            linkText="Learn more"
          />
          <Alert
            variant="warning"
            title="Warning Message"
            message="Be cautious when performing this action."
            showLink={false}
          />
        </ComponentCard>{" "}
        <ComponentCard title="Error Alert">
          <Alert
            variant="error"
            title="Error Message"
            message="Be cautious when performing this action."
            showLink={true}
            linkHref="/"
            linkText="Learn more"
          />
          <Alert
            variant="error"
            title="Error Message"
            message="Be cautious when performing this action."
            showLink={false}
          />
        </ComponentCard>{" "}
        <ComponentCard title="Info Alert">
          <Alert
            variant="info"
            title="Info Message"
            message="Be cautious when performing this action."
            showLink={true}
            linkHref="/"
            linkText="Learn more"
          />
          <Alert
            variant="info"
            title="Info Message"
            message="Be cautious when performing this action."
            showLink={false}
          />
        </ComponentCard>
      </div>
    </div>
  );
}