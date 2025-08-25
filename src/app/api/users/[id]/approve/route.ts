import { NextRequest, NextResponse } from "next/server";
import prisma from "@/lib/prisma";

export async function POST(req: NextRequest, { params }) {
  const id = params?.id;
  if (!id || isNaN(Number(id))) {
    return NextResponse.json({ error: "Invalid or missing user id" }, { status: 400 });
  }
  const { isApproved } = await req.json();
  if (typeof isApproved !== "boolean") {
    return NextResponse.json({ error: "isApproved must be boolean" }, { status: 400 });
  }
  try {
    const user = await prisma.user.update({
      where: { id: Number(id) },
      data: { isApproved },
    });
    return NextResponse.json({ success: true, user });
  } catch {
    return NextResponse.json({ error: "Failed to update user approval" }, { status: 500 });
  }
}