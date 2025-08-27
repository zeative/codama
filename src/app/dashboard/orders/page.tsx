"use client"

import React, { useEffect, useState } from "react";
import { Modal } from "@/components/ui/modal";
import { useModal } from "@/hooks/useModal";
import ComponentCard from "@/components/common/ComponentCard";
import PageBreadcrumb from "@/components/common/PageBreadCrumb";
import BasicTableOne from "@/components/tables/BasicTableOne";
import Button from "@/components/ui/button/Button";

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
    // Optimistic update
    const optimisticOrder = {
      ...form,
      hargaProduk: Number(form.hargaProduk),
      jumlahProduk: Number(form.jumlahProduk),
      waktuPemesanan: new Date(form.waktuPemesanan),
      terakhirUpdate: new Date()
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

  const tableData = orders?.map(order => [
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
  ]);

  return (
    <div>
      <PageBreadcrumb pageTitle="Orders" />
      <div className="space-y-6">
        <ComponentCard title="Data Pemesanan Akrilik">
          <Button onClick={openModal} className="mb-4">Tambah Data</Button>
          <Modal isOpen={isOpen} onClose={closeModal} className="max-w-[600px] p-5 lg:p-10">
            <div className="w-full rounded-lg bg-white dark:bg-gray-900">
              <form onSubmit={handleAddOrder} className="space-y-5">
                <h2 className="text-2xl font-semibold mb-2 text-center text-gray-800 dark:text-white/90">Tambah Data Pemesanan</h2>
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
          <BasicTableOne headers={headers} tableData={tableData} />
        </ComponentCard>
      </div>
    </div>
  );
}