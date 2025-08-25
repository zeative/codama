"use client";
import GridShape from "@/components/common/GridShape";
import { signOut } from "next-auth/react";

export default function NotApproved() {
  return (
    <div className="relative flex flex-col items-center justify-center min-h-screen p-6 overflow-hidden z-1">
      <GridShape />
      <div className="mx-auto w-full max-w-[242px] text-center sm:max-w-[472px]">
        <h1 className="mb-4 font-bold text-gray-800 text-title-md dark:text-white/90 xl:text-title-lg">
          NOT APPROVED
        </h1>

        <p className="mb-6 text-base text-gray-700 dark:text-gray-400 sm:text-lg">
          Your account is not approved.
        </p>

        {/* make a logout button */}
        <button className="px-4 py-1 bg-red-500 text-white rounded-md" onClick={() => signOut()}>
          Logout
        </button>


      </div>
      {/* <!-- Footer --> */}
      <p className="absolute text-sm text-center text-gray-500 -translate-x-1/2 bottom-6 left-1/2 dark:text-gray-400">
        &copy; {new Date().getFullYear()} - Codama
      </p>
    </div>
  );
}