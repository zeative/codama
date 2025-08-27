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

export default function OrdersPage() {
  const [orders, setOrders] = useState([]);
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
    setShowForm(false);
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

  const tableData = orders.map(order => [
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
          <Modal isOpen={isOpen} onClose={closeModal} className="max-w-md p-0">
            <form onSubmit={handleAddOrder} className="w-full bg-white p-8 rounded-lg shadow-lg space-y-4 relative">
              <h2 className="text-xl font-semibold mb-4 text-center">Tambah Data Pemesanan</h2>
              <input name="namaPembeli" placeholder="Nama Pembeli" value={form.namaPembeli} onChange={handleChange} className="input w-full" required />
              <input name="tipePesanan" placeholder="Tipe Pesanan" value={form.tipePesanan} onChange={handleChange} className="input w-full" required />
              <input name="statusPesanan" placeholder="Status Pesanan" value={form.statusPesanan} onChange={handleChange} className="input w-full" required />
              <input name="hargaProduk" placeholder="Harga Produk" value={form.hargaProduk} onChange={handleChange} className="input w-full" type="number" required />
              <input name="jumlahProduk" placeholder="Jumlah Produk" value={form.jumlahProduk} onChange={handleChange} className="input w-full" type="number" required />
              <input name="warnaProduk" placeholder="Warna Produk" value={form.warnaProduk} onChange={handleChange} className="input w-full" required />
              <input name="ketebalanAkrilik" placeholder="Ketebalan Akrilik" value={form.ketebalanAkrilik} onChange={handleChange} className="input w-full" required />
              <input name="keterangan" placeholder="Keterangan" value={form.keterangan} onChange={handleChange} className="input w-full" />
              <input name="waktuPemesanan" placeholder="Waktu Pemesanan" value={form.waktuPemesanan} onChange={handleChange} className="input w-full" type="datetime-local" required />
              <div className="flex gap-2 mt-4">
                <Button type="submit" disabled={loading} className="w-full">{loading ? "Menyimpan..." : "Simpan"}</Button>
                <Button type="button" onClick={closeModal} variant="secondary" className="w-full">Batal</Button>
              </div>
              {loading && <div className="absolute inset-0 flex items-center justify-center bg-white bg-opacity-60"><span className="text-lg font-medium">Menyimpan...</span></div>}
            </form>
          </Modal>
          <BasicTableOne headers={headers} tableData={tableData} />
        </ComponentCard>
      </div>
    </div>
  );
}