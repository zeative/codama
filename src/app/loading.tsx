import GridShape from "@/components/common/GridShape";
import { SITE_METADATA } from "@/consts";
import Image from "next/image";
import Link from "next/link";

export const metadata = {
  ...SITE_METADATA,
  title: SITE_METADATA.titleTemplate("Loading..."),
};

export default function Loading() {
  return (
    <div className="relative flex flex-col items-center justify-center min-h-screen p-6 overflow-hidden z-1">
      <GridShape />
      <div className="w-full max-w-[242px] text-center sm:max-w-[472px]">
        <div className="animate-spin rounded-full h-16 w-16 border-b-2 mx-auto border-gray-200"></div>
      </div>
    </div>
  );
}
