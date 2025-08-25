import { NextResponse } from "next/server";
import prisma from "@/lib/prisma";
import { ROLE } from "@prisma/client";

export async function POST(request: Request, { params }) {
  const { role } = await request.json();
  const userId = Number(params.id);
  if (!userId || !role || !Object.values(ROLE).includes(role)) {
    return NextResponse.json({ error: "Invalid user ID or role" }, { status: 400 });
  }
  // Prevent self-role change
  // This check should be enforced on the client, but double-check on the server for security
  // You may want to get the session user from cookies or headers here

  try {
    const updatedUser = await prisma.user.update({
      where: { id: userId },
      data: { role },
    });
    return NextResponse.json({ success: true, user: updatedUser });
  } catch {
    return NextResponse.json({ error: "Failed to update user role" }, { status: 500 });
  }
}