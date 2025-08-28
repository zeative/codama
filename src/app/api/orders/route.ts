import { NextResponse } from "next/server";
import { PrismaClient } from "@prisma/client";

const prisma = new PrismaClient();

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
    const order = await prisma.order.create({
      data: {
        ...data,
        terakhirUpdate: new Date()
      }
    });
    return NextResponse.json(order);
  } catch {
    return NextResponse.json({ error: "Failed to create order" }, { status: 500 });
  }
}

export async function PATCH(req: Request) {
  try {
    const data = await req.json();
    const { id, ...updateData } = data;
    const updatedOrder = await prisma.order.update({
      where: { id },
      data: {
        ...updateData,
        terakhirUpdate: new Date()
      }
    });
    return NextResponse.json(updatedOrder);
  } catch (E) {
    console.log(E)
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