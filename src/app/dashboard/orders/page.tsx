"use client"

import React, { useEffect, useState } from "react";
import { Modal } from "@/components/ui/modal";
import { useModal } from "@/hooks/useModal";
import ComponentCard from "@/components/common/ComponentCard";
import PageBreadcrumb from "@/components/common/PageBreadCrumb";
import BasicTableOne from "@/components/tables/BasicTableOne";
import Button from "@/components/ui/button/Button";
import { Pencil, Trash2 } from "lucide-react";

const headers = [
  "Nama Pembeli",
  "Tipe Pesanan",
  "Status Pesanan",
  "Harga Produk",
  "Jumlah Produk",
  "Warna Produk",
  "Ketebalan Akrilik",
  "Keterangan",
  "Waktu Pemesanan",
  "Terakhir Update"
];

type Order = {
  id?: string;
  namaPembeli: string;
  tipePesanan: string;
  statusPesanan: string;
  hargaProduk: number;
  jumlahProduk: number;
  warnaProduk: string;
  ketebalanAkrilik: string;
  keterangan: string;
  waktuPemesanan: Date | string;
  terakhirUpdate: Date | string;
  index?: number;
};

export default function OrdersPage() {
  const [orders, setOrders] = useState<Order[]>([]);
  const { isOpen, openModal, closeModal } = useModal();
  const [form, setForm] = useState({
    namaPembeli: "",
    tipePesanan: "",
    statusPesanan: "",
    hargaProduk: "",
    jumlahProduk: "",
    warnaProduk: "",
    ketebalanAkrilik: "",
    keterangan: "",
    waktuPemesanan: "",
    terakhirUpdate: ""
  }); // terakhirUpdate will be set automatically by backend
  const [loading, setLoading] = useState(false);
  const [editModalOpen, setEditModalOpen] = useState(false);
  const [editForm, setEditForm] = useState<Order | null>(null);
  const [editLoading, setEditLoading] = useState(false);

  const openEditModal = (order: Order, index: number) => {
    // Ensure all fields are present and waktuPemesanan is formatted for input
    setEditForm({
      id: order.id ? order.id : undefined,
      namaPembeli: order.namaPembeli ?? "",
      tipePesanan: order.tipePesanan ?? "",
      statusPesanan: order.statusPesanan ?? "",
      hargaProduk: order.hargaProduk ?? "",
      jumlahProduk: order.jumlahProduk ?? "",
      warnaProduk: order.warnaProduk ?? "",
      ketebalanAkrilik: order.ketebalanAkrilik ?? "",
      keterangan: order.keterangan ?? "",
      waktuPemesanan: order.waktuPemesanan ? new Date(order.waktuPemesanan).toISOString().slice(0, 16) : "",
      terakhirUpdate: order.terakhirUpdate ?? "",
      index
    });
    setEditModalOpen(true);
  };
  const closeEditModal = () => {
    setEditModalOpen(false);
    setEditForm(null);
  };
  const handleEditChange = (e) => {
    if (!editForm) return;
    setEditForm({ ...editForm, [e.target.name]: e.target.value });
  };
  const handleUpdateOrder = async (e) => {
    e.preventDefault();
    if (!editForm || !editForm.id) {
      alert("ID pesanan tidak ditemukan. Tidak bisa update.");
      setEditLoading(false);
      return;
    }
    setEditLoading(true);
    const optimisticOrders = [...orders];
    const idx = editForm.index;
    // Preserve the id in optimistic update
    const updatedOrder = {
      ...editForm,
      hargaProduk: Number(editForm.hargaProduk),
      jumlahProduk: Number(editForm.jumlahProduk),
      waktuPemesanan: new Date(editForm.waktuPemesanan),
      terakhirUpdate: new Date(),
      id: editForm.id // Ensure id is present
    };
    optimisticOrders[idx] = updatedOrder;
    setOrders(optimisticOrders);
    closeEditModal();
    try {
      const { ...updateOrderPayload } = updatedOrder;
      const payload = {
        ...updateOrderPayload,
        id: editForm.id
      };
      const res = await fetch("/api/orders", {
        method: "PATCH",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      });
      if (res.ok) {
        const newOrder = await res.json();
        setOrders(current => {
          const copy = [...current];
          copy[idx] = newOrder;
          return copy;
        });
      } else {
        alert("Gagal mengupdate data. Silakan coba lagi.");
      }
    } catch {
      alert("Gagal mengupdate data. Silakan coba lagi.");
    }
    setEditLoading(false);
  };

  useEffect(() => {
    fetch("/api/orders")
      .then(res => res.json())
      .then(data => setOrders(data));
  }, []);

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleAddOrder = async (e) => {
    e.preventDefault();
    setLoading(true);
    // Optimistic update (do not include id)
    const optimisticOrder = {
      ...form,
      hargaProduk: Number(form.hargaProduk),
      jumlahProduk: Number(form.jumlahProduk),
      waktuPemesanan: new Date(form.waktuPemesanan),
      terakhirUpdate: new Date()
      // id will be set by backend
    };
    setOrders([...orders, optimisticOrder]);
    closeModal();
    setForm({
      namaPembeli: "",
      tipePesanan: "",
      statusPesanan: "",
      hargaProduk: "",
      jumlahProduk: "",
      warnaProduk: "",
      ketebalanAkrilik: "",
      keterangan: "",
      waktuPemesanan: "",
      terakhirUpdate: ""
    });
    try {
      const payload = {
        ...form,
        hargaProduk: Number(form.hargaProduk),
        jumlahProduk: Number(form.jumlahProduk),
        waktuPemesanan: new Date(form.waktuPemesanan)
      };
      const res = await fetch("/api/orders", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      });
      if (res.ok) {
        const newOrder = await res.json();
        setOrders(current => [
          ...current.slice(0, -1),
          newOrder
        ]);
      } else {
        // Rollback optimistic update
        setOrders(current => current.slice(0, -1));
        alert("Gagal menambah data. Silakan coba lagi.");
      }
    } catch {
      setOrders(current => current.slice(0, -1));
      alert("Gagal menambah data. Silakan coba lagi.");
    }
    setLoading(false);
  };

  // Ganti tableData dan BasicTableOne agar renderRowActions menerima objek order lengkap
  const tableData = orders;
  const handleDeleteOrder = async (orderId: string, index: number) => {
    if (!window.confirm("Yakin ingin menghapus pesanan ini?")) return;
    const optimisticOrders = [...orders];
    optimisticOrders.splice(index, 1);
    setOrders(optimisticOrders);
    try {
      const res = await fetch("/api/orders", {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: orderId })
      });
      if (!res.ok) {
        alert("Gagal menghapus pesanan. Silakan coba lagi.");
        setOrders([...orders]); // Rollback
      }
    } catch {
      alert("Gagal menghapus pesanan. Silakan coba lagi.");
      setOrders([...orders]); // Rollback
    }
  };

  return (
    <div>
      <PageBreadcrumb pageTitle="Orders" />
      <div className="space-y-6">
        <ComponentCard title="Data Pemesanan Akrilik">
          <Button size="sm" onClick={openModal} className="mb-4">Tambah Data</Button>
          <Modal isOpen={isOpen} onClose={closeModal} className="max-w-[600px] p-5 lg:p-8">
            <div className="w-full rounded-lg bg-white dark:bg-gray-900">
              <form onSubmit={handleAddOrder} className="space-y-8">
                <h2 className="text-2xl font-semibold mb-6 text-center text-gray-800 dark:text-white/90">Tambah Data Pemesanan</h2>
                <div className="grid grid-cols-1 gap-4">
                  <input name="namaPembeli" placeholder="Nama Pembeli" value={form.namaPembeli} onChange={handleChange} className="input w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-white" required />
                  <input name="tipePesanan" placeholder="Tipe Pesanan" value={form.tipePesanan} onChange={handleChange} className="input w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-white" required />
                  <input name="statusPesanan" placeholder="Status Pesanan" value={form.statusPesanan} onChange={handleChange} className="input w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-white" required />
                  <input name="hargaProduk" placeholder="Harga Produk" value={form.hargaProduk} onChange={handleChange} className="input w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-white" type="number" required />
                  <input name="jumlahProduk" placeholder="Jumlah Produk" value={form.jumlahProduk} onChange={handleChange} className="input w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-white" type="number" required />
                  <input name="warnaProduk" placeholder="Warna Produk" value={form.warnaProduk} onChange={handleChange} className="input w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-white" required />
                  <input name="ketebalanAkrilik" placeholder="Ketebalan Akrilik" value={form.ketebalanAkrilik} onChange={handleChange} className="input w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-white" required />
                  <input name="keterangan" placeholder="Keterangan" value={form.keterangan} onChange={handleChange} className="input w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-white" />
                  <input name="waktuPemesanan" placeholder="Waktu Pemesanan" value={form.waktuPemesanan} onChange={handleChange} className="input w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-white" type="datetime-local" required />
                </div>
                <div className="flex items-center justify-end gap-3 mt-6">
                  <Button onClick={closeModal} variant="outline" className="w-32" disabled={loading}>Batal</Button>
                  <Button className="w-32" disabled={loading}>{loading ? "Menyimpan..." : "Simpan"}</Button>
                </div>
                {loading && <div className="absolute inset-0 flex items-center justify-center bg-white dark:bg-gray-900 bg-opacity-60"><span className="text-lg font-medium text-gray-900 dark:text-white">Menyimpan...</span></div>}
              </form>
            </div>
          </Modal>
          <BasicTableOne headers={headers} tableData={tableData.map(order => [
            order.namaPembeli,
            order.tipePesanan,
            order.statusPesanan,
            order.hargaProduk,
            order.jumlahProduk,
            order.warnaProduk,
            order.ketebalanAkrilik,
            order.keterangan,
            order.waktuPemesanan ? new Date(order.waktuPemesanan).toLocaleString() : "",
            order.terakhirUpdate ? new Date(order.terakhirUpdate).toLocaleString() : ""
          ])} renderRowActions={(_, idx) => (
            <div className="flex gap-2">
              <button
                type="button"
                title="Edit"
                className="p-2 rounded hover:bg-gray-100"
                onClick={() => openEditModal(orders[idx], idx)}
              >
                <Pencil size={18} />
              </button>
              <button
                type="button"
                title="Delete"
                className="p-2 rounded hover:bg-gray-100 text-red-600"
                onClick={() => handleDeleteOrder(orders[idx].id)}
              >
                <Trash2 size={18} />
              </button>
            </div>
          )} />
          <Modal isOpen={editModalOpen} onClose={closeEditModal} className="max-w-[600px] p-5 lg:p-10">
            <div className="w-full rounded-lg bg-white dark:bg-gray-900">
              <form onSubmit={handleUpdateOrder} className="space-y-8">
                <h2 className="text-2xl font-semibold mb-6 text-center text-gray-800 dark:text-white/90">Edit Data Pemesanan</h2>
                <div className="grid grid-cols-1 gap-4">
                  <input name="namaPembeli" placeholder="Nama Pembeli" value={editForm?.namaPembeli || ""} onChange={handleEditChange} className="input w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-white" required />
                  <input name="tipePesanan" placeholder="Tipe Pesanan" value={editForm?.tipePesanan || ""} onChange={handleEditChange} className="input w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-white" required />
                  <input name="statusPesanan" placeholder="Status Pesanan" value={editForm?.statusPesanan || ""} onChange={handleEditChange} className="input w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-white" required />
                  <input name="hargaProduk" placeholder="Harga Produk" value={editForm?.hargaProduk || ""} onChange={handleEditChange} className="input w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-white" type="number" required />
                  <input name="jumlahProduk" placeholder="Jumlah Produk" value={editForm?.jumlahProduk || ""} onChange={handleEditChange} className="input w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-white" type="number" required />
                  <input name="warnaProduk" placeholder="Warna Produk" value={editForm?.warnaProduk || ""} onChange={handleEditChange} className="input w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-white" required />
                  <input name="ketebalanAkrilik" placeholder="Ketebalan Akrilik" value={editForm?.ketebalanAkrilik || ""} onChange={handleEditChange} className="input w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-white" required />
                  <input name="keterangan" placeholder="Keterangan" value={editForm?.keterangan || ""} onChange={handleEditChange} className="input w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-white" />
                  <input name="waktuPemesanan" placeholder="Waktu Pemesanan" value={editForm?.waktuPemesanan || ""} onChange={handleEditChange} className="input w-full border border-gray-300 dark:border-gray-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-white" type="datetime-local" required />
                </div>
                <div className="flex items-center justify-end gap-3 mt-6">
                  <Button onClick={closeEditModal} variant="outline" className="w-32" disabled={editLoading}>Batal</Button>
                  <Button className="w-32" disabled={editLoading}>{editLoading ? "Menyimpan..." : "Simpan"}</Button>
                </div>
                {editLoading && <div className="absolute inset-0 flex items-center justify-center bg-white dark:bg-gray-900 bg-opacity-60"><span className="text-lg font-medium text-gray-900 dark:text-white">Menyimpan...</span></div>}
              </form>
            </div>
          </Modal>
        </ComponentCard>
      </div>
    </div>
  );
}