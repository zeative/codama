import { NextResponse } from "next/server";
import { PrismaClient } from "@prisma/client";

const prisma = new PrismaClient();
const STATUS_ENUM = ["PENDING", "PROGRESS", "FINISH", "DONE"];

export async function GET() {
  try {
    const orders = await prisma.order.findMany();
    return NextResponse.json(orders);
  } catch {
    return NextResponse.json({ error: "Failed to fetch orders" }, { status: 500 });
  }
}

export async function POST(req: Request) {
  try {
    const data = await req.json();
    if (!STATUS_ENUM.includes(data.statusPesanan)) {
      return NextResponse.json({ error: "Invalid statusPesanan value" }, { status: 400 });
    }
    // Validate waktuPemesanan
    let waktuPemesananDate: Date | null = null;
    if (typeof data.waktuPemesanan === "string" && !isNaN(Date.parse(data.waktuPemesanan))) {
      waktuPemesananDate = new Date(data.waktuPemesanan);
    } else {
      return NextResponse.json({ error: "Invalid waktuPemesanan value. Expected ISO-8601 DateTime." }, { status: 400 });
    }
    const order = await prisma.order.create({
      data: {
        ...data,
        waktuPemesanan: waktuPemesananDate,
        terakhirUpdate: new Date()
      }
    });
    return NextResponse.json(order);
 } catch (E) {
    console.log(E);
    return NextResponse.json({ error: "Failed to create order" }, { status: 500 });
  }
}

export async function PATCH(req: Request) {
  try {
    const data = await req.json();
    if (!STATUS_ENUM.includes(data.statusPesanan)) {
      return NextResponse.json({ error: "Invalid statusPesanan value" }, { status: 400 });
    }
    // Validate waktuPemesanan
    let waktuPemesananDate: Date | null = null;
    if (typeof data.waktuPemesanan === "string" && !isNaN(Date.parse(data.waktuPemesanan))) {
      waktuPemesananDate = new Date(data.waktuPemesanan);
    } else {
      return NextResponse.json({ error: "Invalid waktuPemesanan value. Expected ISO-8601 DateTime." }, { status: 400 });
    }
    const order = await prisma.order.update({
      where: { id: data.id },
      data: {
        ...data,
        waktuPemesanan: waktuPemesananDate,
        terakhirUpdate: new Date()
      }
    });
    return NextResponse.json(order);
  } catch {
    return NextResponse.json({ error: "Failed to update order" }, { status: 500 });
  }
}

export async function DELETE(req: Request) {
  try {
    const data = await req.json();
    const { id } = data;
    await prisma.order.delete({ where: { id } });
    return NextResponse.json({ success: true });
  } catch (E) {
    console.log(E);
    return NextResponse.json({ error: "Failed to delete order" }, { status: 500 });
  }
}