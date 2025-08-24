import ComponentCard from "@/components/common/ComponentCard";
import PageBreadcrumb from "@/components/common/PageBreadCrumb";
import Avatar from "@/components/ui/avatar/Avatar";
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

export default function AvatarPage() {
  return (
    <div>
      <PageBreadcrumb pageTitle="Avatar" />
      <div className="space-y-5 sm:space-y-6">
        <ComponentCard title="Default Avatar">
          {/* Default Avatar (No Status) */}
          <div className="flex flex-col items-center justify-center gap-5 sm:flex-row">
            <Avatar src="/images/user/user-01.jpg" size="xsmall" />
            <Avatar src="/images/user/user-01.jpg" size="small" />
            <Avatar src="/images/user/user-01.jpg" size="medium" />
            <Avatar src="/images/user/user-01.jpg" size="large" />
            <Avatar src="/images/user/user-01.jpg" size="xlarge" />
            <Avatar src="/images/user/user-01.jpg" size="xxlarge" />
          </div>
        </ComponentCard>
        <ComponentCard title="Avatar with online indicator">
          <div className="flex flex-col items-center justify-center gap-5 sm:flex-row">
            <Avatar
              src="/images/user/user-01.jpg"
              size="xsmall"
              status="online"
            />
            <Avatar
              src="/images/user/user-01.jpg"
              size="small"
              status="online"
            />
            <Avatar
              src="/images/user/user-01.jpg"
              size="medium"
              status="online"
            />
            <Avatar
              src="/images/user/user-01.jpg"
              size="large"
              status="online"
            />
            <Avatar
              src="/images/user/user-01.jpg"
              size="xlarge"
              status="online"
            />
            <Avatar
              src="/images/user/user-01.jpg"
              size="xxlarge"
              status="online"
            />
          </div>
        </ComponentCard>
        <ComponentCard title="Avatar with Offline indicator">
          <div className="flex flex-col items-center justify-center gap-5 sm:flex-row">
            <Avatar
              src="/images/user/user-01.jpg"
              size="xsmall"
              status="offline"
            />
            <Avatar
              src="/images/user/user-01.jpg"
              size="small"
              status="offline"
            />
            <Avatar
              src="/images/user/user-01.jpg"
              size="medium"
              status="offline"
            />
            <Avatar
              src="/images/user/user-01.jpg"
              size="large"
              status="offline"
            />
            <Avatar
              src="/images/user/user-01.jpg"
              size="xlarge"
              status="offline"
            />
            <Avatar
              src="/images/user/user-01.jpg"
              size="xxlarge"
              status="offline"
            />
          </div>
        </ComponentCard>{" "}
        <ComponentCard title="Avatar with busy indicator">
          <div className="flex flex-col items-center justify-center gap-5 sm:flex-row">
            <Avatar
              src="/images/user/user-01.jpg"
              size="xsmall"
              status="busy"
            />
            <Avatar src="/images/user/user-01.jpg" size="small" status="busy" />
            <Avatar
              src="/images/user/user-01.jpg"
              size="medium"
              status="busy"
            />
            <Avatar src="/images/user/user-01.jpg" size="large" status="busy" />
            <Avatar
              src="/images/user/user-01.jpg"
              size="xlarge"
              status="busy"
            />
            <Avatar
              src="/images/user/user-01.jpg"
              size="xxlarge"
              status="busy"
            />
          </div>
        </ComponentCard>
      </div>
    </div>
  );
}