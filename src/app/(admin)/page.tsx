import type { Metadata } from "next";
import { EcommerceMetrics } from "@/components/ecommerce/EcommerceMetrics";
import React from "react";
import MonthlyTarget from "@/components/ecommerce/MonthlyTarget";
import MonthlySalesChart from "@/components/ecommerce/MonthlySalesChart";
import StatisticsChart from "@/components/ecommerce/StatisticsChart";
import RecentOrders from "@/components/ecommerce/RecentOrders";
import DemographicCard from "@/components/ecommerce/DemographicCard";

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

export default function Ecommerce() {
  return (
    <div className="grid grid-cols-12 gap-4 md:gap-6">
      <div className="col-span-12 space-y-6 xl:col-span-7">
        <EcommerceMetrics />

        <MonthlySalesChart />
      </div>

      <div className="col-span-12 xl:col-span-5">
        <MonthlyTarget />
      </div>

      <div className="col-span-12">
        <StatisticsChart />
      </div>

      <div className="col-span-12 xl:col-span-5">
        <DemographicCard />
      </div>

      <div className="col-span-12 xl:col-span-7">
        <RecentOrders />
      </div>
    </div>
  );
}