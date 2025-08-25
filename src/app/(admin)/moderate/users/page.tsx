'use client'

import PageBreadcrumb from "@/components/common/PageBreadCrumb";
import BasicTableOne from "@/components/tables/BasicTableOne";
import { SITE_METADATA } from "@/consts";
import { useEffect, useState } from "react";


export default function UsersPage() {
  const [tableData, setTableData] = useState([]);
  const headers = ["User", "Project Name", "Team", "Status", "Budget"];

  useEffect(() => {
    async function fetchData() {
      const res = await fetch("/api/users");
      if (res.ok) {
        const data = await res.json();
        // Transform data to match headers
        const transformed = data.map((item) => ({
          User: item.user,
          "Project Name": item.projectName || "-",
          Team: item.team,
          Status: item.status,
          Budget: item.budget || "-"
        }));
        setTableData(transformed);
      }
    }
    fetchData();
  }, []);
  return (
    <div>
      <PageBreadcrumb pageTitle="Users" />
      <div className="space-y-6">
          <BasicTableOne headers={headers} tableData={tableData} />
      </div>
    </div>
  );
}