import Calendar from "@/components/calendar/Calendar";
import PageBreadcrumb from "@/components/common/PageBreadCrumb";
import { SITE_METADATA } from "@/consts";
import React from "react";

export const metadata = {
  ...SITE_METADATA,
  title: SITE_METADATA.titleTemplate("Calendar"),
};

export default function page() {
  return (
    <div>
      <PageBreadcrumb pageTitle="Calendar" />
      <Calendar />
    </div>
  );
}