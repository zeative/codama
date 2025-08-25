import { NextResponse } from "next/server";
import { PrismaClient } from "@prisma/client";

const prisma = new PrismaClient();

export async function GET() {
  try {
    const users = await prisma.user.findMany();
    // Transform users to match Order interface if needed
    const orders = users.map((user) => ({
      id: user.id,
      user: {
        image: user.image,
        name: user.name,
        role: user.role,
      },
      status: user.isApproved ? "Active" : "Pending",
      email: user.email,
      team: {images: []},
    }));
    return NextResponse.json(orders);
  } catch (error) {
    return NextResponse.json({ error: "Failed to fetch users" }, { status: 500 });
  }
}