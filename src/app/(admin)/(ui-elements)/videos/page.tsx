import PageBreadcrumb from "@/components/common/PageBreadCrumb";
import VideosExample from "@/components/ui/video/VideosExample";
import { SITE_METADATA } from "@/consts";
import { Metadata } from "next";
import React from "react";

export const metadata: Metadata = {
  ...SITE_METADATA,
  title: SITE_METADATA.titleTemplate("Videos"),
  description: "Videos UI elements for Codama web solution.",
};


export default function VideoPage() {
  return (
    <div>
      <PageBreadcrumb pageTitle="Videos" />

      <VideosExample />
    </div>
  );
}