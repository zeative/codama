'use client'

import PageBreadcrumb from "@/components/common/PageBreadCrumb";
import BasicTableOne from "@/components/tables/BasicTableOne";
import { useEffect, useState } from "react";
import { useSession } from "next-auth/react";
import Switch from "@/components/form/switch/Switch";

const roleOptions = ["KING","ADMIN", "USER"];


export default function UsersPage() {
  interface UserTableItem {
    id: string;
    user: {
      name?: string;
      email?: string;
      role?: string;
    };
    isApproved: boolean;
    status: string;
  }
  const [tableData, setTableData] = useState<UserTableItem[]>([]);
  const [loadingIds, setLoadingIds] = useState<string[]>([]);
  const headers = ["Nama", "Email", "Role", "Status", "Aksi"];
  const { data: session } = useSession();

  useEffect(() => {
    async function fetchData() {
      const res = await fetch("/api/users");
      if (res.ok) {
        const data = await res.json();
        setTableData(data);
      }
    }
    fetchData();
  }, []);

  async function handleToggle(userId: string, approved: boolean) {
    setLoadingIds(ids => [...ids, userId]);
    // Optimistic update
    setTableData(prev => prev.map(item => item.id === userId ? { ...item, isApproved: approved, status: approved ? "Active" : "Pending" } : item));
    try {
      await fetch(`/api/users/${userId}/approve`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ isApproved: approved })
      });
    } catch {
      // Rollback on error
      setTableData(prev => prev.map(item => item.id === userId ? { ...item, isApproved: !approved, status: !approved ? "Active" : "Pending" } : item));
    } finally {
      setLoadingIds(ids => ids.filter(id => id !== userId));
    }
  }

  async function handleRoleChange(userId: string, newRole: string) {
    setLoadingIds(ids => [...ids, userId]);
    // Optimistic update
    setTableData(prev => prev.map(item => item.id === userId ? { ...item, user: { ...item.user, role: newRole } } : item));
    try {
      await fetch(`/api/users/${userId}/role`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ role: newRole })
      });
    } catch {
      // Rollback on error
      setTableData(prev => prev.map(item => item.id === userId ? { ...item, user: { ...item.user, role: item.user.role } } : item));
    } finally {
      setLoadingIds(ids => ids.filter(id => id !== userId));
    }
  }

  return (
    <div>
      <PageBreadcrumb pageTitle="Users" />
      <div className="space-y-6">
        <BasicTableOne
          headers={headers}
          tableData={tableData.map((item: UserTableItem) => [
            item.user?.name || "",
            item.user?.email || "",
            (session?.user?.email === item.user?.email ? item.user?.role : <select
              key={item.id + "-role"}
              value={item.user?.role || ""}
              disabled={loadingIds.includes(item.id) || session?.user?.email === item.user?.email}
              onChange={e => handleRoleChange(item.id, e.target.value)}
              style={{ minWidth: 100 }}
            >
              {roleOptions.map(role => (
                <option key={role} value={role}>{role}</option>
              ))}
            </select>),
            item.status || "",
            <div key={item.id} hidden={session?.user?.email === item.user?.email}>
              <Switch
                disabled={loadingIds.includes(item.id)}
                label={""}
                defaultChecked={item.isApproved}
                onChange={checked => handleToggle(item.id, checked)}
              />
            </div>,
          ])}
        />x
      </div>
    </div>
  );
}