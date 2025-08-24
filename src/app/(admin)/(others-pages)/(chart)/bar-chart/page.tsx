import BarChartOne from "@/components/charts/bar/BarChartOne";
import ComponentCard from "@/components/common/ComponentCard";
import PageBreadcrumb from "@/components/common/PageBreadCrumb";
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

export default function page() {
  return (
    <div>
      <PageBreadcrumb pageTitle="Bar Chart" />
      <div className="space-y-6">
        <ComponentCard title="Bar Chart 1">
          <BarChartOne />
        </ComponentCard>
      </div>
    </div>
  );
}